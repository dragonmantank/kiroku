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
			$page['parent_page']	= $this->_request->getParam('parent_page');
			$page['module']			= $this->_request->getParam('moduleId');
			$page['active']			= $this->_request->getParam('status');
			
			$table					= new Pages;
			$table->insert($page);
			
			$this->_redirect('admin/pages');
		}
		
		$this->view->form = new AddEditPageForm();
	}

	public function changestatusAction()
	{
		$page = new Bcms_Page($this->_request->getParam('page'));
		$page->changeStatus();
		$this->_redirect('admin/pages');
	}
	
	public function deleteAction()
	{
		$page = new Bcms_Page($this->_request->getParam('page'));
		$page->delete();
		$this->_redirect('admin/pages');
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

		$this->view->pages	= $table->fetchPages();
	}

    public function init()
    {
        $this->_helper->layout->setLayout('admin-layout');
    }
	
	public function sethomepageAction()
	{
	}
	
	public function settingsAction()
	{
		$table = new Pages;
		
		if($this->_request->isPost()) {
			$page['name']			= $this->_request->getParam('pageName');
			$page['title']			= $this->_request->getParam('pageTitle');
			$page['link_name']		= $this->_request->getParam('linkName');
			$page['description']	= $this->_request->getParam('pageDescription');
			$page['parent_page']	= $this->_request->getParam('parent_page');
			$page['module']			= $this->_request->getParam('moduleId');
			$page['active']			= $this->_request->getParam('status');
			
			$table->update($page, 'id = ' . $this->_request->getParam('id'));			
			$this->_redirect('admin/pages');
		}
		
		$data	= array();
		$row 	= $table->find($this->_request->getParam('page'))->current();
		
		$data['pageName']			= $row->name;
		$data['pageTitle']			= $row->title;
		$data['linkName']			= $row->link_name;
		$data['pageDescription']	= $row->description;
		$data['parent_page']		= $row->parent_page;
		$data['moduleId']			= $row->module;
		$data['status']				= $row->active;
		$data['id']					= $row->id;
				
		$this->view->form = new AddEditPageForm();
		$this->view->form->populate($data);
	}
}
