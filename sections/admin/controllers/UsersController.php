<?php

class Admin_UsersController extends Zend_Controller_Action
{
	public function addAction()
	{
		$users	= new Users;
		$form	= $users->getForm();
		
		if($this->_request->isPost()) {
			$data	= $this->_request->getPost();
			unset($data['submit']);
			
			if($form->isValid($data)) {
				$users->insert($data);
				$this->_redirect('/admin/users');
			} else {
				$form->populate($data);
				echo 'There was a problem!';
			}
		}
		
		$this->view->form	= $form;
	}
	
	public function init()
	{
		$auth	= Zend_Auth::getInstance();
		if(!$auth->hasIdentity()) {
			$this->_redirect('auth');
		} else {
			if($auth->getIdentity()->user_group != Bcms_Auth_Adapter_Bcms::getGroupId('Super Users')) {
			$this->_redirect('auth');
			}
		}

        $this->_helper->layout->setLayout('admin-layout');
	}
	
	public function indexAction()
	{
		$table	= new Users;
		$users	= $table->fetchAll($table->select()->limit(5)->order('username DESC'));
		
		$this->view->users	= $users->toArray(); 
	}
	
	public function viewAction()
	{
		$users		= new Users;
		$user		= $users->find($this->_request->getParam('uid'))->current();
		$form		= $users->getForm();
		$userArray	= $user->toArray();
		unset($userArray['password']);
		
		if($this->_request->isPost()) {
			$data	= $this->_request->getPost();
			unset($data['submit']);
			
			if($form->isValid($data)) {
				$data['password']	= md5($data['password']);
				$users->update($data, 'id = ' . $data['id']);
				$this->_redirect('/admin/users');
			} else {
				$form->populate($data);
				echo 'There was a problem!';
			}
		} else {
			$form->populate($userArray);
		}
				
		$this->view->form	= $form;
	}
}