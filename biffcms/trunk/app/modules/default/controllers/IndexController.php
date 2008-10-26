<?php

class IndexController extends Zend_Controller_Action
{
	public function __call($method, $args)
	{
		$this->_request->setParam('page', $method);
		$this->_forward('index');
	}
	
	public function indexAction()
	{
		$seed	= $this->_request->getParam('page');
		$seed	= ( is_scalar($seed) ? $seed : Bcms_Page::findHomepage() );

		try {
			$page	= new Bcms_Page($seed);
		} catch (Exception $e) {
			$this->_redirect('notfound');
		}
		
		$this->view->title		= $page->title;
		$this->view->content	= $page->render();
	}

	public function notfoundAction()
	{
	}
}
