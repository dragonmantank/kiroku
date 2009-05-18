<?php

/**
 * Parent class for Kiroku Sections
 *
 * Provides the base functionality for working with sections such as installing
 * and uninstalling them.
 *
 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
 * @copyright 2009 Chris Tankersley
 * @package Kiroku_Section
 */
abstract class Kiroku_Section
{
	protected $_sectionTable	= 'cms_sections';
	protected $_db;
	
	protected $_name;
	protected $_description;
	protected $_version;
	
	protected $_id;
	protected $_default;
	protected $_status;
	
	public function __construct()
	{
		$this->_db	= Zend_Registry::get('db');
		
		$data	= $this->_db->fetchRow($this->_db->select()->from('cms_sections')->where('name = ?', $this->_name));
		if( count($data) ) {
			$this->_id		= $data['id'];
			$this->_status	= $data['status'];
			$this->_default	= $data['default'];
		}
	}
	
	/**
	 * Adds an entry into the DB for this section
	 *
	 * This allows a section to set up things such as custom tables, modify
	 * existing information, etc.
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 * @returns int
	 */
	public function install()
	{
		$data	= array(
			'name'			=> $this->_name,
			'description'	=> $this->_description,
		);
		
		return $this->_db->insert($this->_sectionTable, $data);
	}
	
	/**
	 * Removes an entry into the DB for this section
	 *
	 * Cleans up anything special that was installed by the section
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 * @return bool
	 */
	public function uninstall()
	{
		return $this->_db->delete($this->_sectionTable, 'name = \'' . $this->_name . "'");
	}
	
	/**
	 * Turns a section off
	 *
	 * Disabls a section from being called by the CMS but does not remove
	 * anything that it installed.
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 */
	public function deactivate()
	{
		if( !$this->_default ) {
			$status			= ($this->_status ? 0 : 1);
			$this->_status	= $status;
		
			return $this->_db->update($this->_sectionTable, array('status' => $status), "name = '" . $this->_name . "'");
		} else {
			throw new Exception('Cannot delete the default section.');
		}
	}
	
	/**
	 * Sets the section as the default module for the CMS
	 *
	 * Since some people may not want the Page module to be the default module
	 * that is called, this will flag another section as being the default mod.
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 */
	public function setAsDefault()
	{
		if(!$this->_default) {
			$this->_db->update($this->_sectionTable, array('default' => 1), "name = '" . $this->_name . "'");
			$this->_db->update($this->_sectionTable, array('default' => 0), "name != '" . $this->_name . "'");
			$this->_default	= 1;
		}
		
		return true;
	}
}
