<?php
/**
 * Last updated $Date: 2007-03-05 16:11:20 +0000 (Mon, 05 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 310 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/includes/Request.class.php $
 *
 * @author Andy Brockhurst, <andy.brockhurst@b3cft.com>
 * @package FERDoA
 */

/**
 * An Request Object
 *
 * used by the application controller
 *
 * @package FERDoA
 * @subpackage Main
 * @access public
 * @author Andrew Brockhurst, <andy.brockhurst@b3cft.com>
 */
class Request
{
	private static $instance;
	private $data = array();
	private $filters = array(
		'_stripped',
		'_html'=>'h2 h3 h4 h5 h6 span div table tbody tr td th',
		'_cooked',
		'_raw'
		);


	/**
	 * Constructor
	 *
	 * @access private
	 */
	private function __construct()
	{
		/**
		 * @todo escape input, protect from xss, js/db insertion etc.
		 */
		$this->data['get_raw']=$_GET;
		$this->data['post_raw']=$_POST;
		$this->data['cookie']=$_COOKIE;
		$this->data['browser']['agent']=$_SERVER['HTTP_USER_AGENT'];
		$this->data['browser']['ip']=$_SERVER['REMOTE_ADDR'];
		$this->data['browser']['method']=$_SERVER['REQUEST_METHOD'];
		$this->data['browser']['accept']=$_SERVER['HTTP_ACCEPT'];
		$this->data['browser']['lang']=$_SERVER['HTTP_ACCEPT_LANGUAGE'];
		//$this->data['server']=$_SERVER;

		if ( isset($_SERVER['SCRIPT_URI']) ) {
			$Uri = $_SERVER['SCRIPT_URI'];
		} else
		{
			$Uri = $_SERVER['REQUEST_URI'];
		}

		if ( !is_null($Uri) ) {
			@$this->data['uri']=parse_url($Uri);
		}

		if ( !isset($this->data['uri']['port']) && isset($_SERVER['SERVER_PORT']) ) {
			$this->data['uri']['port'] = $_SERVER['SERVER_PORT'];
		}

		if ( !isset($this->data['uri']['host']) && isset($_SERVER['SERVER_NAME']) ) {
			$this->data['uri']['host'] = $_SERVER['SERVER_NAME'];
		}

		if ( isset($this->data['uri']['path']) ) {
			$this->data['path'] = explode('/', strtolower(trim($this->data['uri']['path'],'/')));
		}
	}

	/**
	 * Singleton initiator
	 *
	 * @return Request
	 */
	public static function & getInstance()
	{
		if ( empty(self::$instance) ) {
			self::$instance = new Request();
		}
		return self::$instance;
	}

	/**
	 * Get a request parameter, returns null on not found
	 *
	 * @param string $type
	 * @param string $parameter
	 * @return mixed
	 */
	public function get($type, $parameter = null)
	{
		if ( isset($this->data[$type][$parameter]) ) {
			return $this->data[$type][$parameter];
		}
		elseif ( isset($this->data[$type]) && is_null($parameter) ) {
			if ( false !== in_array($type, array('get','post')) )
			{
				return $this->get_filtered($type, $parameter);
			}
			return $this->data[$type];
		}
		else
		{
			if ( false !== in_array($type, array('get','post')) )
			{
				return $this->get_filtered($type, $parameter);
			}
		}
		return null;
	}

	/**
	 * Return the current host, including protocol port etc.
	 *
	 * @return string
	 */
	public function getHost()
	{
		$host = $this->data['uri']['scheme'].'://'.$this->data['uri']['host'];
		if ( isset($this->data['uri']['port']) && '80'!=$this->data['uri']['port'] ) {
			$host .= ':'.$this->data['uri']['protocol'];
		}
		return $host.'/';
	}

	/**
	 * Get a cookie's value (or null if not set)
	 *
	 * @param string $name
	 * @return string
	 */
	public function getCookie($name)
	{
	    if ( isset($this->data['cookie'][$name]) ) {
	        return $this->data['cookie'][$name];
	    }
	    return null;
	}

	/**
	 * Set a cookies value
	 *
	 * @param string $name
	 * @param string $value
	 * @param int $expires
	 * @param string $path
	 * @param int $secure
	 */
	public function setCookie($name, $value, $expires = null, $path ='/', $secure=0)
	{
		$domain = $this->data['uri']['host'];
		$this->data['cookie'][$name] = $value;
		$expires = is_null($expires) ? null : time()+$expires;
		setcookie($name, $value, $expires, $path, $domain, $secure);
	}

	/**
	 * Remove a cookie
	 *
	 * @param string $name
	 */
	public function deleteCookie($name, $path = '/', $secure=0)
	{
		$domain = $this->data['uri']['host'];
		setcookie ($name, '', time() - 3600, $path, $domain, $secure);
		unset($this->data['cookie'][$name]);
		unset($_COOKIE[$name]);
	}

	/**
	 * Return the next path command without removing it
	 * returns false if empty or not set
	 *
	 * @access public
	 * @return mixed string|boolean
	 */
	public function pathPeek()
	{
		if ( isset ($this->data['path'][0]) && strlen($this->data['path'][0]) ) {
			return $this->data['path'][0];
		}
		return false;
	}

	/**
	 * Return the next path command and removes it from the array
	 * returns false if empty or not set
	 *
	 * @access public
	 * @return mixed string|boolean
	 */
	public function pathPop()
	{
		if ( isset ($this->data['path'][0]) && strlen($this->data['path'][0]) ) {
			$pathBit = $this->data['path'][0];
			$this->data['path'] = array_slice($this->data['path'], 1);
			return $pathBit;
		}
		return false;
	}

	/**
	 * Get the rest of the path from the
	 *
	 * @return string
	 */
	public function pathGetRest()
	{
		return implode('/',$this->data['path']);
	}

	/**
	 * Reset the path parameters
	 *
	 * @access public
	 */
	public function pathClear()
	{
		$this->data['path'] = array();
	}

	/**
	 * Retrieve get and post input filtered
	 * Lazy function to process when needed
	 *
	 * @param string $type
	 * @param string $parameter
	 * @param string $filter
	 * @return mixed
	 */
	public function get_filtered($type, $parameter = null, $filter='_stripped')
	{
		if ( isset($this->data[$type.$filter][$parameter]) ) {
			return $this->data[$type.$filter][$parameter];
		}

		if ( isset($this->data[$type.'_raw'][$parameter]) ) {
			$this->data[$type.$filter][$parameter] = $this->filter($this->data[$type.'_raw'][$parameter], $filter);
			return $this->data[$type.$filter][$parameter];
		}
		elseif ( isset($this->data[$type.'_raw']) && is_null($parameter) ) {
			$this->data[$type.$filter] = $this->filter($this->data[$type.'_raw'], $filter);
			return $this->data[$type.$filter];
		}
	}

	/**
	 * Perform filtering operations
	 *
	 * @param string $data
	 * @param string $filter
	 * @return mixed
	 */
	private function filter($data, $filter='_stripped')
	{
		if ( is_array($data) )
		{
			foreach ($data as $key=>$value)
			{
				$data[$key] = $this->filter($value, $filter);
			}
			return $data;
		}
		if ( is_scalar($data) )
		{
			switch ($filter)
			{
				case '_stripped':
						$data = strip_tags($data);
					/* no break */

				case '_cooked':
						$data = htmlentities($data, ENT_COMPAT);
					break;

				case '_html':
						$data = strip_tags($data, $this->filters['_html']);
					break;
			}
			return $data;
		}
	}
}
?>