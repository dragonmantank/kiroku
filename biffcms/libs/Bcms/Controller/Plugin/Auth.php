<?php

require_once 'Zend/Controller/Plugin/Abstract.php';

class Bcms_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
	protected $_authAdapter;
	protected $_db;
	
	public function __construct()
	{
		$this->_db			= Zend_Registry::get('db');
		$this->_authAdapter	= new Zend_Auth_Adapter_DbTable($this->_db, 'smf_live_members', 'memberName', 'passwd', 'AND `is_activated` = 1');
	}
	
	public function addCredentials($username, $password)
	{
		$this->_authAdapter->setIdentiy($username)
						   ->setCredential($password);
	}
	
	public function authenticate()
	{
		return $this->_authAdapter->authenticate();
	}
}
