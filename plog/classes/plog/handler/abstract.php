<?php

require_once 'plog/formatter.php';

abstract class Plog_Handler_Abstract
{
	protected static $_instances = array();

	public static function instance($driver)
	{
		if (empty(self::$_instances[$driver]))
		{
			$class = 'Plog_Handler_'.$driver;
			self::$_instances[$driver] = new $class($driver);
		}

		return self::$_instances[$driver];
	}

	protected $_formatter;
	protected $_local_config = array();
	protected $_plog_formatter;

	public function __construct($driver)
	{
		$config = Plog::get_config();
		$class = get_class($this);
		foreach ($config['handlers'] as $handler)
		{
			if (strtolower($handler['driver']) == $driver)
			{
				$this->_formatter = $config['formatters'][$handler['formatter']];
				$this->_local_config = $handler['config'];
			}
		}
		$this->_plog_formatter = new Plog_Formatter();
	}

	public function set_formatter_args($args)
	{
		$this->_plog_formatter->args = $args;
	}

	protected function _format()
	{
		preg_match_all('/\{([a-zA-Z_-]+)\}/u', $this->_formatter, $matches);
		$replace_arr = array();
		foreach($matches[1] as $key)
		{
			$replace_arr['{'.$key.'}'] = $this->_plog_formatter->{'get'.$key}();
		}
		return strtr($this->_formatter, $replace_arr);
	}

	abstract function save();
}
