<?php

class Pages extends Zend_Db_Table_Abstract
{
	protected $_name	= 'cms_pages';
	
	public function fetchPairs(array $pairs)
	{
		$db		= $this->getDefaultAdapter();
		$sql	= $db->quoteInto('SELECT ?, ? FROM `' . $this->_name . '` ORDER BY `name` ASC', array($pairs[0], $pairs[1])); 
		return $db->fetchAll($sql);
	}
	
	public function insert(array $data)
	{
		parent::insert($data);
		$id = $this->getAdapter()->lastInsertId();
		Bcms_Module::createDefaultPage($data['module'], $id);
	}
}
