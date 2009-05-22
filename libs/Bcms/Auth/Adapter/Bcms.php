<?php

require_once 'Bcms/Auth/Adapter.php';

class Bcms_Auth_Adapter_Bcms extends Bcms_Auth_Adapter
{
	protected $_hashAlgorithm		= 'md5';
	protected $_userName;
	protected $_tableName			= 'cms_user_accounts';
	protected $_identityColumn		= 'username';
	protected $_credentialColumn	= 'password';
	
	static public function getGroupId($name)
	{
		$db			= Zend_Registry::get('db');
		$select		= $db->select()->from('cms_user_groups')->where('name = ?', $name);
		list($name)	= $db->fetchCol($select);
		
		return $name;
	}

	static public function getGroupName($id)
	{
		$db			= Zend_Registry::get('db');
		$select		= $db->select()->from('cms_user_groups', array('name'))->where('id = ?', $id);
		list($name)	= $db->fetchCol($select);
		
		return $name;
	}

	public function setIdentity($identity)
	{
		$this->_userName = $identity;
		parent::setIdentity($identity);
	}
}
