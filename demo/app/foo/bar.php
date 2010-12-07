<?php

require '../../../plog/classes/plog.php';

Plog::set_config(include '../../../plog/config.php');
$log = Plog::factory(__FILE__);

$log->fatal('百转千折她将我围绕');
$log->warn('曾经以为人生就这样了');
