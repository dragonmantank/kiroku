<?php

class Cmslink_Plugin extends Bcms_Module
{
	protected $_db;
	protected $_table	= 'cms_module_cmslink';
	protected $_name	= 'Cmslink';
	
	// Page Attributes
	protected $_id;
	protected $_pageId;
	protected $_linkName;
	protected $_module;
	protected $_controller;
	protected $_action;
	protected $_params;
	
	public function __construct($pageId = null)
	{
		if($pageId != null) {
			$this->_db	= Zend_Registry::get('db');
			$select		= $this->_db->select()->from($this->_table)->where('pageId = ?', $pageId);
			$row		= $this->_db->fetchRow($select);
			
			if($row != null) {
				$this->_id			= $row['id'];
				$this->_pageId		= $row['pageId'];
				$this->_linkName	= $row['linkName'];
				$this->_module		= $row['module'];
				$this->_controller	= $row['controller'];
				$this->_action		= $row['action'];
				$this->_params		= $row['params'];
			} else {
				throw new Exception('Unable to find a match for page ' . $pageId . ' in CmsLink Module');
			}
		}
	}
	
	public function edit()
	{
		$view 				= $this->_getView();
		$view->linkName		= $this->_linkName;
		$view->module		= $this->_module;
		$view->controller	= $this->_controller;
		$view->action		= $this->_action;
		$view->params		= $this->_params;
		
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
		$this->_module		= $f->filter($data['module']);
		$this->_controller	= $f->filter($data['controller']);
		$this->_action		= $f->filter($data['action']);
		$this->_params		= $data['params'];
		
		$this->_save();
	}
	
	public function render() 
	{
		$link[]	= $this->_module;
		$link[]	= $this->_controller;
		$link[]	= $this->_action;
		$link[]	= $this->_params;

		for($i = 0; $i < count($link); $i++) {
			if($link[$i] == '') {
				unset($link[$i]);
			}
		}
		
		header('Location: /' . implode('/', $link));
	}
	
	protected function _save()
	{
		$data = array(
			'linkName'		=> $this->_linkName,
			'module'		=> $this->_module,
			'controller'	=> $this->_controller,
			'action'		=> $this->_action,
			'params'		=> $this->_params,
		);
		
		$this->_db->update($this->_table, $data, 'pageId = ' . $this->_pageId);
	}
	
	public function uninstall()
	{
		throw new Exception('Plugin `cmslink` is a system plugin and cannot be installed.');
	}
}
