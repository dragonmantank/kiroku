<?php

require_once 'Bcms/Auth/Adapter.php';

class Bcms_Auth_Adapter_SMF extends Bcms_Auth_Adapter
{
	protected $_userName;
	protected $_tableName			= 'smf_live_members';
	protected $_identityColumn		= 'memberName';
	protected $_credentialColumn	= 'passwd';
	
	static public function getGroupId($name)
	{
		$db			= Zend_Registry::get('db');
		list($name)	= $db->fetchCol('SELECT `ID_GROUP` FROM `smf_live_membergroups` WHERE `groupName` = ?', $name);
		
		return $name;
	}

	static public function getGroupName($id)
	{
		$db			= Zend_Registry::get('db');
		list($name)	= $db->fetchCol('SELECT `groupName` FROM `smf_live_membergroups` WHERE `ID_GROUP` = ?', $id);
		
		return $name;
	}

	public function getHash($data)
	{
		return sha1(strtolower($this->_userName) . $data);
	}

	public function setIdentity($identity)
	{
		$this->_userName = $identity;
		parent::setIdentity($identity);
	}
}
