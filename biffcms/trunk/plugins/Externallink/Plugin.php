<?php

class Externallink_Plugin extends Bcms_Module
{
	protected $_db;
	protected $_table	= 'cms_module_externallink';
	protected $_name	= 'Externallink';
	
	// Page Attributes
	protected $_id;
	protected $_pageId;
	protected $_linkName;
	protected $_url;
	
	public function __construct($pageId = null)
	{
		if($pageId != null) {
			$this->_db	= Zend_Registry::get('db');
			$row		= $this->_db->fetchRow('SELECT * FROM `' . $this->_table . '` WHERE `pageId` = ?', $pageId);
			
			if($row != null) {
				$this->_id			= $row['id'];
				$this->_pageId		= $row['pageId'];
				$this->_linkName	= $row['linkName'];
				$this->_url			= $row['url'];
			} else {
				throw new Exception('Unable to find a match for page ' . $pageId . ' in Externallink Module');
			}
		}
	}
	
	public function edit()
	{
		$view 				= $this->_getView();
		$view->linkName		= $this->_linkName;
		$view->url			= $this->_url;
		
		return $view->render('edit.phtml');
	}
	
	public function insertDefaultPage($pageId)
	{
		$db = Zend_Registry::get('db');
		$db->insert($this->_table, array('pageId'=>$pageId));
	}
	
	public function updateText($data)
	{
		$f					= new Zend_Filter_Alnum();
		$this->_linkName	= $f->filter($data['linkName']);
		$this->_url			= $data['url'];
		
		$this->_save();
	}
	
	public function render() 
	{
		header('Location: ' . $this->_url);
	}
	
	protected function _save()
	{
		$data = array(
			'linkName'		=> $this->_linkName,
			'url'			=> $this->_url,
		);
		
		$this->_db->update($this->_table, $data, 'pageId = ' . $this->_pageId);
	}
}
