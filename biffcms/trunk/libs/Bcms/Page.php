<?php

class Bcms_Page
{
	protected $_db;
	
	// Page attributes
	protected $_id;
	protected $_name;
	protected $_linkName;
	protected $_title;
	protected $_description;
	protected $_parentPage;
	protected $_module;
	protected $_active;
	protected $_homepage;
	
	public function __construct($seed = null)
	{
		$this->_db = Zend_Registry::get('db');
		
		if($seed != null) {
			$this->load($seed);
		}
	}
	
	public function changeStatus()
	{
		if(!$this->_homepage) {
			$this->_db->update('cms_pages', array('active'=> ($this->_active ? 0 : 1) ), 'id = ' . $this->_id);
		}
	}
	
	public function delete()
	{
		if(!$this->_homepage) {
			$this->_db->delete('cms_pages', 'id = ' . $this->_id);
			$module	= Bcms_Module::factory($this->_module, $this->_id);
			$module->delete();
		}
	}
	
	public function edit()
	{
		$module = Bcms_Module::factory($this->_module, $this->_id);
		return $module->edit();
	}
	
	public function __get($var)
	{
		$name	= '_' . $var;
		
		return $this->{$name};
	}
	
	static public function findHomepage()
	{
		$db				= Zend_Registry::get('db');
		list($pageId)	= $db->fetchCol('SELECT `id` FROM `cms_pages` WHERE `homepage` = 1');
		
		return $pageId;
	}
	
	public function load($seed)
	{
		$column	= ( is_numeric($seed) ? 'id' : 'name');
		$page	= $this->_db->fetchRow('SELECT * FROM `cms_pages` WHERE `' . $column . '` = ?', $seed);
		
		if( $page != null ) {
			$this->_id			= $page['id'];
			$this->_name		= $page['name'];
			$this->_linkName	= $page['link_name'];
			$this->_title		= $page['title'];
			$this->_description	= $page['description'];
			$this->_parentPage	= $page['parent_page'];
			$this->_module		= $page['module'];
			$this->_active		= $page['active'];
			$this->_homepage	= $page['homepage'];
			
		} else {
			throw new Exception("Page '" . $seed . "' cannot be found!");
		}
	}
	
	public function updateText($data)
	{
		$module	= Bcms_Module::factory($this->_module, $this->_id);
		$module->updateText($data);
	}
	
	public function render()
	{
		$module = Bcms_Module::factory($this->_module, $this->_id);
		return $module->render();
	}
}
