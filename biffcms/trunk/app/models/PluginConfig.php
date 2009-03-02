<?php

class PluginConfig extends Zend_Db_Table_Abstract
{
	protected $_name	= 'cms_modules_config';
	
	public function fetchConfig($id)
	{
		$rows	= $this->fetchAll($this->select()->where('module_id = ?', $id));
		
		$config	= array();
		foreach($rows as $row) {
			$config[$row->var]	= $row->data;
		}
		
		return $config;
	}
}