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
		$clientIp = '0.0.0.0';
		if (isset($_SERVER['CLIENT_IP']))
		{
			$clientIp = $_SERVER['CLIENT_IP'];
		}
		elseif (isset($_SERVER['X_FORWARDED_FOR']))
		{
			$clientIp = $_SERVER['X_FORWARDED_FOR'];
		}
		elseif (isset($_SERVER['REMOTE_ADDR']))
		{
			$clientIp = $_SERVER['REMOTE_ADDR'];
		}

		return $clientIp;
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
