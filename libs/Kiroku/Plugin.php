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
		$select		= $db->select()->from('cms_modules', array('name'))->where('id = ?', $id);
		list($name)	= $db->fetchCol($select);
		$className	= ucfirst($name) . '_Plugin';

		return new $className($pageId);
	}

	protected function _getView()
	{
		$view   = new Zend_View();
		$view->addBasePath(dirname(dirname(dirname(__FILE__))) . '/plugins/' . ucfirst($this->_name) . '/views/');

	    return $view;
	}

	public function install()
	{
		$modules	= new Modules();
		$modules->insert(array('name' => strtolower($this->_name), 'description' => $this->_description));
	}

	public function uninstall()
	{
		$modules	= new Modules();
		$modules->delete("`name` = '" . strtolower($this->_name) . "'");
	}
}