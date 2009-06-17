<?php

/**
 * Sets the layout for the Admin module
 *
 * Does this as a Front Controller plugin to make it easier if the name of the 
 * layout filename ever changes.
 *
 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
 * @copyright 2009 Chris Tankersley
 * @package Tws_Controller_Plugin
 */
class Tws_Controller_Plugin_AdminLayout extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Sets the layout to the Admin layout if the called module is 'admin'
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$module	= $request->getModuleName();
		if($module == 'admin') {
			Zend_Layout::getMvcInstance()->setLayout('admin-layout');
		}
	}
}
