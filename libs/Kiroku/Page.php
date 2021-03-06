<?php

/* 
 * Generates a page based on a plugin
 *
 * Kiroku_Page pulls in the appropriate page plugin from the database and
 * renders the page.
 *
 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
 * @copyright 2009 Chris Tankersley
 * @package Kiroku_Plugin
 */

class Kiroku_Page
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
	protected $_display;
	protected $_homepage;

	public function __construct($seed = null)
	{
		$this->_db = Zend_Registry::get('db');

		if($seed != null) {
			$this->load($seed);
		}
	}

	public function changeDisplayStatus()
	{
		$this->_db->update('cms_pages', array('display'=> ($this->_display ? 0 : 1) ), 'id = ' . $this->_id);
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
			$module	= Kiroku_Plugin::factory($this->_module, $this->_id);
			$module->delete();
		}
	}

	public function edit()
	{
		$module = Kiroku_Plugin::factory($this->_module, $this->_id);
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
		$select			= $db->select()->from('cms_pages')->where('homepage = ?', 1);
		list($pageId)	= $db->fetchCol($select);

		return $pageId;
	}

	public function load($seed)
	{
		$column	= ( is_numeric($seed) ? 'id' : 'slug');
		$select	= $this->_db->select()->from('cms_pages')->where($column . ' = ?', $seed);
		$page	= $this->_db->fetchRow($select);

		if( $page != null ) {
			$this->_id			= $page['id'];
			$this->_name		= $page['name'];
			$this->_linkName	= $page['link_name'];
			$this->_title		= $page['title'];
			$this->_description	= $page['description'];
			$this->_parentPage	= $page['parent_page'];
			$this->_module		= $page['module'];
			$this->_active		= $page['active'];
			$this->_display		= $page['display'];
			$this->_homepage	= $page['homepage'];
		} else {
			throw new Exception("Page '" . $seed . "' cannot be found!");
		}
	}

	public function updateText($data)
	{
		$module	= Kiroku_Plugin::factory($this->_module, $this->_id);
		$module->updateText($data);
	}

	public function render()
	{
		$module = Kiroku_Plugin::factory($this->_module, $this->_id);
		return stripslashes($module->render());
	}
}
