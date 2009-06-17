<?php

/**
 * Administrative functions that relate to authentication like users and groups
 *
 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
 * @copyright 2009 Chris Tankersley
 * @package Admin
 */
class Admin_AuthController extends Zend_Controller_Action
{
	/**
	 * Allows a user to add new users to the system
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 */
	public function addUserAction()
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
	
	/**
	 * Displays the newest users and groups that have been added
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 */
	public function indexAction()
	{
		$table	= new Users;
		$users	= $table->fetchAll($table->select()->limit(5)->order('id DESC'));
		
		$groupsTable	= new UserGroups();
		$groups			= $groupsTable->fetchAll($groupsTable->select()->limit(5)->order('id DESC'));
		
		$this->view->users	= $users->toArray(); 
		$this->view->groups	= $groups->toArray();
	}

	/**
	 * Allows a user to view a specific user and update them
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 */
	public function viewUserAction()
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
				if($data['password'] != '') {
					$data['password']	= md5($data['password']);
				} else {
					unset($data['password']);
				}
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
