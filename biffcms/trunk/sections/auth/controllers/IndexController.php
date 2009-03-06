<?php

class Auth_IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->_redirect('auth/index/login');
	}
	
	public function loginAction()
	{
		if( $this->_request->isPost() ) {
			$f			= new Zend_Filter_StripTags();
			$username	= $f->filter(trim($this->_request->getParam('username')) );
			$password	= $f->filter(trim($this->_request->getParam('passwd')) );
			
			if( empty($username) ) {
				$this->view->errorMessage = 'Please enter a username';
			} else {
				$auth	= new Bcms_Auth_Adapter_Bcms;
				$auth->setIdentity($username);
				$auth->setCredential($password);
				$auth->authenticate();
				
				if( $auth->isValid() ) {
					$auth->writeStorage();
					$this->_redirect('admin');
				} else {
					$this->view->errorMessage = 'Invalid username and/or password';
				}
			}
		}
	}
	
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		session_destroy();
		$this->_redirect('/');
	}
}
