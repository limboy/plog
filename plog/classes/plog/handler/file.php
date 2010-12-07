<?php

require_once 'plog/handler/abstract.php';

class Plog_Handler_File extends Plog_Handler_Abstract
{
	public function save()
	{
		$log_message = $this->_format().PHP_EOL;
		$dir = rtrim($this->_local_config['dir'], '/');
		$dest_dir = $dir.'/'.date('Y').'/'.date('m');
		if(!is_dir($dest_dir))
		{
			mkdir($dest_dir, 0777, true);
		}
		$dest_file = $dest_dir.'/'.date('Y-m-d').'.log';
		touch($dest_dir);
		chmod($dest_dir, 0777);
		file_put_contents($dest_file, $log_message, FILE_APPEND);
	}
}
