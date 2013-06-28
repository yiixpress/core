<?php
define('APP_ID', 'backend');
// change the following paths if necessary
$yiiPath = '../../../../yii-1.1.13.e9e4a0/framework/yii.php';
require_once('../init.php');

Yii::createWebApplication($GLOBALS['config'])->run();