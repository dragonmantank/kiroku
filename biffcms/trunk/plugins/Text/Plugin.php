<?php

class Text_Plugin extends Bcms_Module
{
	protected $_db;
	protected $_table	= 'cms_module_text';
	protected $_name	= 'Text';
	
	// Page Attributes
	protected $_id;
	protected $_pageId;
	protected $_text;
	
	public function __construct($pageId = null)
	{
		if($pageId != null) {
			$this->_db	= Zend_Registry::get('db');
			$row		= $this->_db->fetchRow('SELECT * FROM `' . $this->_table . '` WHERE `page_id` = ?', $pageId);
			
			if($row != null) {
				$this->_id			= $row['id'];
				$this->_pageId		= $row['page_id'];
				$this->_text		= $row['text'];
			} else {
				throw new Exception('Unable to find a match for page ' . $pageId . ' in Text Module');
			}
		}
	}
	
	public function edit()
	{
		$view = $this->_getView();
		
		$view->existingText	= stripslashes($this->_text);
		
		return $view->render('edit.phtml');
	}
	
	public function insertDefaultPage($pageId)
	{
		$db = Zend_Registry::get('db');
		$db->insert($this->_table, array('page_id'=>$pageId));
	}
	
	protected function _save()
	{
		$this->_db->update($this->_table, array('text'=>$this->_text), 'page_id = ' . $this->_pageId);
	}
	
	public function updateText($data)
	{
		$this->_text	= $data['pageText'];
		$this->_save();
	}
	
	public function render() 
	{
		return $this->_text;
	}
}
