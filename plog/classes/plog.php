<?php

class Plog_Exception extends Exception {}

class Plog 
{
	private static $_instances = array();
	private static $_config = array();

	public static function set_config($config)
	{
		self::$_config = $config;
	}

	public static function get_config()
	{
		return self::$_config;
	}

	public static function factory($filepath)
	{
		static $include_path;

		if(empty($include_path))
		{
			set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
			$include_path = true;
		}

		$config = self::get_config();
		$dest_logger = $filepath;
		$base_path = $config['loggers']['base'];
		unset($config['loggers']['base']);
		foreach ($config['loggers'] as $logger => $path)
		{
			if (substr($filepath, 0, strlen($base_path.$path)) == $base_path.$path)
			{
				$filepath = substr(str_replace($base_path.$path.DIRECTORY_SEPARATOR, '', $filepath), 0, -4);
				$dest_logger = $logger.'.'.str_replace(DIRECTORY_SEPARATOR, '.', $filepath);
			}
		}

		if (empty(self::$_instances[$dest_logger]))
		{
			self::$_instances[$dest_logger] = new self($dest_logger);
		}

		return self::$_instances[$dest_logger];
	}

	private $_logger;
	private $_logger_handlers = array();

	public function __construct($logger)
	{
		$this->_logger = $logger;
		$config = self::get_config();
		foreach ($config['handlers'] as $handler)
		{
			if (!empty($handler['enabled']) && $handler['enabled'] == true)
			{
				$this->_logger_handlers[] = $handler;
			}
		}
	}

	public function __call($method, $args)
	{
		$config = self::get_config();
		if (!in_array(strtoupper($method), $config['levels']))
		{
			throw Plog_Exception(sprintf('method not allowed: %s', $method));
		}
		foreach ($this->_logger_handlers as $handler)
		{
			$class = 'Plog_Handler_'.strtoupper($handler['driver']);
			if (!class_exists($class))
				require strtolower(str_replace('_', DIRECTORY_SEPARATOR, $class)).'.php';
			$class = $class::instance($handler['driver']);
			$class->set_formatter_args(array(
				'message' => $args[0],
				'level' => strtoupper($method),
				'logger' => $this->_logger,
			));
			$class->save();
		}
	}
}
