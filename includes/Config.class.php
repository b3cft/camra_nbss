<?php
/**
 * Last updated $Date: 2007-01-31 11:15:18 +0000 (Wed, 31 Jan 2007) $
 * by $Author: andy $
 *
 * This file is $Revision: 255 $
 * $HeadURL: http://svn.b3cft.net/camra/nbss/version2/includes/Config.class.php $
 *
 * @author Andrew Brockhurst, <andy.brockhurst@b3cft.com>
 * @package FERDoA
 */

/**
 * Utility class for storing and dealing with configuration data
 *
 * Stores config data in groups
 *
 * @author Andrew Brockhurst, <andy.brockhurst@b3cft.com>
 * @package FERDoA
 * @subpackage Main
 */
class Config
{
	private static $instance;
	private $properties = array();

	/**
	 * Singleton Constructor
	 *
	 * @access private
	 */
	private function __construct(){}

	/**
	 * Singleton
	 *
	 * @static
	 * @access public
	 * @return Config static instance of the Config object
	 */
	public static function & getInstance()
	{
		if (empty(self::$instance))
		{
			self::$instance = new Config();
		}
		return self::$instance;
	}

	/**
	 * Set a config value
	 *
	 * @access public
	 * @param string $group
	 * @param string $var
	 * @param mixed $value
	 */
	public function set($group, $var, $value)
	{
		if (!isset($this->properties[$group]))
		{
			$this->properties[$group] = array();
		}
		$this->properties[$group][$var] = $value;
	}

	/**
	 * Get a config value or Group
	 *
	 * If called without the value returns group as array.
	 *
	 * @access public
	 * @param string $group The group
	 * @param mixed $var The variable name, or false to get the group
	 * @return mixed The value, or false if non existant
	 */
	public function get($group, $var=false)
	{
		if ($var)
		{
			if (isset($this->properties[$group][$var]))
			{
				return $this->properties[$group][$var];
			}
			return false;
		}
		elseif (isset($this->$this->properties[$group]))
		{
			return $this->$this->properties[$group];
		}
		return false;
	}
}
?>