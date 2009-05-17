<?php

class Admin_SystemController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$form		= new SystemConfigForm();
		$sysConfig	= new SystemConfig();
		
		if($this->_request->isPost()) {
			$data	= $this->_request->getPost();
			if($form->isValid($data)) {
				unset($data['submit']);

				foreach($data as $key => $value) {
					$newData	= array('value' => $value);
					$where		= $sysConfig->getAdapter()->quoteInto('`key` = ?', $key); 

					$sysConfig->update($newData, $where);
				}
				header('Location: /admin/system');
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
}