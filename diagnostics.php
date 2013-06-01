<?php
define('APP_ID','diagnostic');

error_reporting(E_ALL); // let PHP suggest code changes
set_time_limit(0);
ini_set('display_errors', 1);
define('YII_TRACE_LEVEL',20);

define('YII_DEBUG',true);
// change the following paths if necessary
$yiiPath = '../../../yii-1.1.13.e9e4a0/framework/yii.php';
require_once($yiiPath);

$config = require_once(dirname(__FILE__).'/protected/config/diagnostics.php');

Yii::createWebApplication($config)->run();