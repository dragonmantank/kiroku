<?php

define("HEARTBEAT_DIR", dirname(__FILE__));

set_include_path('.' . PATH_SEPARATOR . 
				 HEARTBEAT_DIR . '/../libs/' . PATH_SEPARATOR . 
				 HEARTBEAT_DIR . '/../shared/' . PATH_SEPARATOR . 
				 HEARTBEAT_DIR . '/../app/' . PATH_SEPARATOR . 
				 HEARTBEAT_DIR . '/../app/model/' . PATH_SEPARATOR . 
				 get_include_path() . PATH_SEPARATOR
);

// Set up Zend
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

$registry	= Zend_Registry::getInstance();

$config = new Zend_Config_Ini('config/global.ini', 'development');
$registry->set('config', $config);

$db = Zend_Db::factory($config->db);
$registry->set('db', $db);

Tws_CronController::run();
