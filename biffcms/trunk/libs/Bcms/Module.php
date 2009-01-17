<?php

abstract class Bcms_Module
{
	protected $_db;
	
	// Module attributes
	protected $_id;
	protected $_name;
	protected $_description;
	protected $_active;
	
	abstract public function edit();
	abstract public function insertDefaultPage($pageId);
	abstract protected function _save();
	abstract public function updateText($data);
	abstract public function render();
	
	static public function createDefaultPage($moduleId, $pageId)
	{
		$module = self::factory($moduleId);
		$module->insertDefaultPage($pageId);
	}
	
	public function delete()
	{
		$this->_db->delete($this->_table, 'id = ' . $this->_id);
	}
	
	static public function factory($id, $pageId = null)
	{
		$db			= Zend_Registry::get('db');
		list($name)	= $db->fetchCol('SELECT `name` FROM `cms_modules` WHERE `id` = ?', $id);
		$className	= 'Bcms_Module_' . ucfirst($name);
		
		return new $className($pageId);
	}
	
	protected function _getView()
	{
		$config = Zend_Registry::get('config');
		$view   = new Zend_View();
		$view->addBasePath($config->modules->viewPath);
	    
	    return $view;             
	}
}
