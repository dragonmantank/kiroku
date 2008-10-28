<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');

// Directory setup and class loading
set_include_path(
	'.' . 
	PATH_SEPARATOR . '../app' . 
	PATH_SEPARATOR . '../libs/' . 
	PATH_SEPARATOR . '../app/models' . 
	PATH_SEPARATOR . '../app/forms' .
	PATH_SEPARATOR . get_include_path() );
	
define('BCMS_MODULE_VIEWPATH', dirname(dirname(__FILE__)) . '/app/cmsModuleViews/');

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

// Setup the controller
$bootstrap->throwExceptions(true);
$bootstrap->registerPlugin('Zend_Controller_Plugin_ErrorHandler');
$bootstrap->setControllerDirectory(array(
	'admin'		=> '../app/modules/admin/controllers',
	'auth'		=> '../app/modules/auth/controllers',
	'default'	=> '../app/modules/default/controllers',
));
$bootstrap->setParam('useDefaultControllerAlways', true);

// Add in our custom routes
$route	= new Zend_Controller_Router_Route('page/:page', array('module'=>'default', 'controller'=>'index', 'action'=>'index'));
$bootstrap->addRoute('page', $route);

// Load our layout paths
Zend_Layout::startMvc( array('layoutPath'	=> '../app/layouts') );

// Add View Helpers
$viewRenderer	= Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
$viewRenderer->initView();
$viewRenderer->view->addHelperPath('Bcms/View/Helper', 'Bcms_View_Helper');

// Run!
$bootstrap->dispatch();
