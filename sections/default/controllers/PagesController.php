<?php

class Admin_PagesController extends Zend_Controller_Action
{
	public function addAction()
	{
		if($this->_request->isPost()) {
			$page['name']			= $this->_request->getParam('pageName');
			$page['title']			= $this->_request->getParam('pageTitle');
			$page['link_name']		= $this->_request->getParam('linkName');
			$page['description']	= $this->_request->getParam('pageDescription');
			$page['parent_page']	= 0;
			$page['module']			= 1;
			$page['active']			= 1;
			
			$table					= new Pages;
			$table->insert($page);
			
			$this->_redirect('admin/pages');
		}
	}

	public function changestatusAction()
	{
	}
	
	public function deleteAction()
	{
	}
	
	public function editAction()
	{
		$page	= new Bcms_Page($this->_request->getParam('page'));
	
		if( $this->_request->isPost() ) {
			$page->updateText($_POST);
			$this->_redirect('admin/pages');
		} else {
			$this->view->editMode	= $page->edit();
		}
	}
	
	public function indexAction()
	{
		$table	= new Pages();

		$this->view->pages	= $table->fetchAll();
	}
	
	public function sethomepageAction()
	{
	}
	
	public function settingsAction()
	{
	}	
}
