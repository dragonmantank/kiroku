<?php

class SystemConfig extends Zend_Db_Table_Abstract
{
	protected $_name	= 'cms_config';
	
	public function fetchConfig()
	{
		$rows	= $this->fetchAll();
		$config	= array();
		
		foreach($rows as $row) {
			$config[$row->key] = $row->value;
		}
		
		return new Zend_Config($config);
	}
}