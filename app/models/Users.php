<?php

class Users extends Zend_Db_Table_Abstract
{
	protected $_name	= 'cms_user_accounts';
	
	public function getForm()
	{
		return new AddEditUserForm;
	}
	
	public function insert(array $data)
	{
		$data['password']	= md5($data['password']);
		
		return parent::insert($data);	
	}
}