<?php

class Plog_Formatter 
{
	public $args;

	public function getTime()
	{
		static $time;
		empty($time) && $time = date('Y/m/d H:i:s');
		return $time;
	}

	public function getUri()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public function getIp()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

	public function getLevel()
	{
		return $this->args['level'];
	}

	public function getLogger()
	{
		return $this->args['logger'];
	}

	public function getMessage()
	{
		return $this->args['message'];
	}
}
