<?php

class Admin_IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{ 
        $pages  = new Pages;

        $this->view->pages = $pages->fetchSummaryList();
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
	
	public function logoutAction()
	{
	}
}
