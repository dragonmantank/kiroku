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
		$select		= $db->select()->from('smf_live_membergroups', array('ID_GROUP'))->where('groupName = ?', $name);
		list($name)	= $db->fetchCol($select);
		
		return $name;
	}

	static public function getGroupName($id)
	{
		$db			= Zend_Registry::get('db');
		$select		= $db->select()->from('smf_live_membergroups', array('groupName'))->where('ID_GROUP = ?', $id);
		list($name)	= $db->fetchCol($select);
		
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
