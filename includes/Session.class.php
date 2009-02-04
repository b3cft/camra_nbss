<?php
/**
 * Last updated $Date: 2007-03-07 09:19:43 +0000 (Wed, 07 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 314 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/includes/Session.class.php $
 *
 * @author Andy Brockhurst, <andy.brockhurst@b3cft.com>
 * @package FERDoA
 */

/**
 * Name of a profile property for a recipient
 *
 * @package FERDoA
 * @subpackage Session
 * @access public
 * @author Andrew Brockhurst, <andy.brockhurst@b3cft.com>
 */
class Session
{
    private static $instance;
    private $data = array();
    private $id;
    private $key;
    private $_dirty = false;

    /**
     * Private Constructor
     *
     */
	private function __construct()
	{
		$request = Request::getInstance();
		$config = Config::getInstance();
		$this->id = session_id()!='' ? session_id() : $request->getCookie($config->get('session', 'cookieName'));
		if ( !is_null($this->id) ) {
			session_id($this->id);
			session_start();
	    }
	    else {
	    	session_start();
	    	session_regenerate_id(true);
	    	$this->id = session_id();
	    }
	    $request->setCookie($config->get('session', 'cookieName'), $this->id, $config->get('session', 'duration'));
	    $this->key = md5($this->id.$config->get('session', 'salt').$request->get('browser', 'agent'));
	    $this->load();
	    register_shutdown_function('ferdoa_session_saver');
	    register_shutdown_function('ferdoa_session_tidy', $config->get('path', 'session'), $config->get('session', 'duration'));
	}

	/**
	 * Public singleton instanciator
	 *
	 * @return Session
	 */
	public static function & getInstance()
	{
		if ( empty(self::$instance) ) {
			self::$instance = new Session();
		}
		return self::$instance;
	}

	/**
	 * Get a session variable
	 *
	 * @param string $name
	 * @return mixed
	 */
	public static function get($name)
	{
		$me =& self::getInstance();
		if ( isset($me->data[$name]) ) {
			return $me->data[$name];
		}
		return null;
	}

	/**
	 * Set a session variable
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public static function set($name, $value)
	{
		$me =& self::getInstance();
		$me->data[$name] = $value;
		$me->_dirty = true;
	}

	/**
	 * Remove a value from the session
	 *
	 * @param string $name
	 */
	public static function remove($name)
	{
		$me =& self::getInstance();
		unset($me->data[$name]);
		$me->_dirty = true;
	}

	/**
	 * Delete a session variable
	 *
	 * @param string $name
	 */
	public static function delete($name)
	{
		$me =& self::getInstance();
		unset($me->data[$name]);
		$me->_dirty = true;
	}

	/**
	 * Private function to load and decrypt stored session data
	 *
	 */
	private function load()
	{
		$filename = Config::getInstance()->get('path', 'session').$this->id;
		if ( is_file($filename) )
		{
			$fileHandle = fopen($filename, "rb");
			$fileData = fread($fileHandle, filesize($filename));
			fclose($fileHandle);
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

			$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, $fileData, MCRYPT_MODE_ECB, $iv);

			if ( is_serialized($decrypted) )
			{
				$this->data = unserialize($decrypted);
				if ( !is_array($this->data) )
				{
					Session::destroy($this->id);
				}
			}
			else
			{
				Session::destroy($this->id);
			}
			$this->_dirty = false;
		}
	}

	/**
	 * Save a session to disk
	 *
	 */
	public static function save()
	{
		$me =& self::getInstance();
		if ($me->_dirty)
		{
			$filename = $filename = Config::getInstance()->get('path', 'session').$me->id;
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

			$data = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $me->key, serialize($me->data), MCRYPT_MODE_ECB, $iv);
			$fileHandle = fopen($filename, 'wb');
			fwrite($fileHandle, $data);
			fclose($fileHandle);
			$me->_dirty = false;
		}
	}

	/**
	 * Destroy current or named session
	 *
	 * @param string $session
	 */
	public static function destroy($session=null, $clearCookie=true)
	{
		if (is_null($session))
		{
			$session = session_id();
		}
		$config = Config::getInstance();
		if ( $clearCookie ) {
			self::$instance = null;
			Request::getInstance()->deleteCookie(session_name());
			Request::getInstance()->deleteCookie($config->get('session', 'cookieName'));
			session_destroy();
		}
		$filename = $config->get('path', 'session').$session;
		if ( is_file($filename) )
		{
			unlink($filename);
		}
	}
}

/**
 * Shutdown function to save Session on page completion
 *
 */
function ferdoa_session_saver()
{
	Session::save();
}

/**
 * Shutdown function to remove session from folder older than age
 *
 * @param string $path
 * @param int $age
 */
function ferdoa_session_tidy($path, $age)
{
	if (is_dir($path) && $dh = opendir($path))
	{
        while (($file = readdir($dh)) !== false)
        {
        	if ( is_file($path.$file) && filemtime($path.$file) < time()-$age )
        	{
            	unlink($path.$file);
        	}
        }
        closedir($dh);
	}
}
?>