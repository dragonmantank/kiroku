<?php

class Admin_ModulesController extends Zend_Controller_Action
{
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
		$modules	= new Modules;
		
		$this->view->installedModules	= $modules->fetchAll();
	}
}