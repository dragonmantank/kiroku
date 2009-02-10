<?php

class Admin_ModulesController extends Zend_Controller_Action
{
	public function changestatusAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$modules	= new Modules();
		
		try {
			$message['status']	= $modules->changeStatus($this->_request->getParam('id'));
			$message['success'] = 1;
			$message['message']	= 'Status was changed';
		} catch (Exception $e) {
			$message['success']	= 0;
			$message['message']	= $e->getMessage();
		}
		
		echo json_encode($message);
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
		$modulesTable		= new Modules;
		$installedModules	= $modulesTable->fetchInstalled();
		$uninstalledModules	= $modulesTable->fetchUninstalled();
		
		$this->view->installedModules	= $installedModules;
		$this->view->uninstalledModules	= $uninstalledModules;
	}
	
	public function installAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$moduleName	= ucfirst($this->_request->getParam('name')) . '_Plugin';
		$module 	= new $moduleName;
		
		echo json_encode(array('status' =>$module->install()));
	}
	
	public function uninstallAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$moduleName	= ucfirst($this->_request->getParam('name')) . '_Plugin';
		$module 	= new $moduleName;
		
		try {
			$module->uninstall();
			$message['status']	= true;
		} catch (Exception $e) {
			$message['status']	= false;
			$message['message']	= $e->getMessage();
		}
		echo json_encode($message);
	}
}