<?php

class Admin_SectionsController extends Zend_Controller_Action
{
	public function changestatusAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		try {
			$name				= $_GET['id'] . '_Section';
			$modules			= new $name;
			$message['status']	= $modules->setAsDefault();
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
		$table			= new Sections();
		$installed		= $table->fetchInstalled();
		$uninstalled	= $table->fetchUninstalled();
		
		$this->view->installedModules	= $installed;
		$this->view->uninstalledModules	= $uninstalled;
	}
	
    public function installAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $moduleName = strtolower($this->_request->getParam('name')) . '_Section';
        $module     = new $moduleName;

        echo json_encode(array('status' =>$module->install()));
    }

    public function uninstallAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $moduleName = strtolower($this->_request->getParam('name')) . '_Section';
        $module     = new $moduleName;

        try {
            $module->uninstall();
            $message['status']  = true;
        } catch (Exception $e) {
            $message['status']  = false;
            $message['message'] = $e->getMessage();
        }
        echo json_encode($message);
	}
}
