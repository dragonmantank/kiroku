<?php
/* 
 * Abstract class to extend when creating Plugins
 *
 * This defines the basic construct of a plugin that is developed for Kiroku
 * so that all the appropriate functions are defined. Plugins are usually page-
 * level bits of functionality that do not require a lot of code to work, for
 * example, displaying a simple HTML page.
 *
 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
 * @copyright 2009 Chris Tankersley
 * @package Kiroku_Plugin
 */

abstract class Kiroku_Plugin
{
	protected $_db;

	// Module attributes
	protected $_id;
	protected $_name			= null;
	protected $_description		= null;
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
		$select		= $db->select()->from('cms_modules', array('name'))->where('id = ?', $id);
		list($name)	= $db->fetchCol($select);
		$className	= ucfirst($name) . '_Plugin';

		return new $className($pageId);
	}

	/**
	 * Returns a Zend_View object for rendering HTML output
	 *
	 * Generates and returns a Zend_View object that is pointing to the correct
	 * script directory for the plugin.
	 * 
	 * @return Zend_View
	 */
	protected function _getView()
	{
		$view   = new Zend_View();
		$view->addBasePath(dirname(dirname(dirname(__FILE__))) . '/plugins/' . ucfirst($this->_name) . '/views/');

	    return $view;
	}

	public function install()
	{
		if($this->_name == null || $this->_description == null) {
			throw new Exception('Plugin is missing name or description. Please check source for this plugin.');
		} else {
			$modules	= new Modules();
			$modules->insert(array('name' => strtolower($this->_name), 'description' => $this->_description));
		}
	}

	public function uninstall()
	{
		$modules	= new Modules();
		$modules->delete("`name` = '" . strtolower($this->_name) . "'");
	}
}