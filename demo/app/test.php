<?php

require '../../plog/classes/plog.php';

Plog::set_config(include '../../plog/config.php');
$log = Plog::factory(__FILE__);

$log->debug('hello world');
$log->info('今晚打老虎');
