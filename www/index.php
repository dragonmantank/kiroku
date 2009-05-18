<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');

define("INSTALL_PATH", dirname(dirname(__FILE__)));

// Directory setup and class loading
set_include_path(
	'.' . 
	PATH_SEPARATOR . INSTALL_PATH . '/app' . 
	PATH_SEPARATOR . INSTALL_PATH . '/libs/' . 
	PATH_SEPARATOR . INSTALL_PATH . '/app/models' . 
	PATH_SEPARATOR . INSTALL_PATH . '/app/forms' .
	PATH_SEPARATOR . INSTALL_PATH . '/plugins' . 
	PATH_SEPARATOR . INSTALL_PATH . '/sections' . 
	PATH_SEPARATOR . get_include_path() );
	
include 'Zend/Loader.php';
include 'Tws/Bootstrap.php';

$bootstrap = new Tws_Bootstrap();
$bootstrap->startSessions();

// Load configuration
$environment	= strtolower( array_key_exists('ENVIRONMENT', $_SERVER) ? $_SERVER['ENVIRONMENT'] : 'production' );
$config 		= new Zend_Config_Ini('../app/config/config.ini', $environment);
$registry		= Zend_Registry::getInstance();
$registry->set('config', $config);

// Setup the database
$db = Zend_Db::factory($config->db);
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('db', $db);

// Pull in the config from the DB
$systemConfigModel	= new SystemConfig();
$systemConfig		= $systemConfigModel->fetchConfig();
$registry->set('systemConfig', $systemConfig);

// Setup the controller
$bootstrap->throwExceptions(true);
$bootstrap->registerPlugin('Zend_Controller_Plugin_ErrorHandler');
$sections			= new Sections();
$installedSections	= $sections->fetchInstalled();
$loadedSections		= array();
foreach($installedSections as $sec) {
	$loadedSections[$sec->name]	= '../sections/' . $sec->name . '/controllers';
	if($sec->default) {
		$defaultSection	= $sec->name;
	}
}

$bootstrap->setControllerDirectory($loadedSections);
$bootstrap->setDefaultModule($defaultSection);
//$bootstrap->setControllerDirectory(array(
//	'admin'		=> '../sections/admin/controllers',
//	'auth'		=> '../sections/auth/controllers',
//	'default'	=> '../sections/default/controllers',
//));
$bootstrap->setParam('useDefaultControllerAlways', true);

// Add in our custom routes
$route	= new Zend_Controller_Router_Route('page/:page', array('module'=>'default', 'controller'=>'index', 'action'=>'index'));
$bootstrap->addRoute('page', $route);

// Load our layout paths
Zend_Layout::startMvc( array('layoutPath'	=> dirname(__FILE__) . '/themes/' . $systemConfig->theme) );

// Add View Helpers
$viewRenderer	= Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
$viewRenderer->initView();
$viewRenderer->view->addHelperPath('Bcms/View/Helper', 'Bcms_View_Helper');

// Run!
$bootstrap->dispatch();
