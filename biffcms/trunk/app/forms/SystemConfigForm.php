<?php

class SystemConfigForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$currConfig	= Zend_Registry::get('systemConfig');
		
		$theme	= new Zend_Form_Element_Select('theme');
		$theme->setLabel('Current Theme:')
			   ->setMultiOptions($this->_getThemes())
			   ->setValue($currConfig->theme);
			   
		$submit	= new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Save Config');
			   
		$this->addElements(array(
			$theme, $submit,
		));
	}
	
	private function _getThemes()
	{
		$themesDir	= INSTALL_PATH . '/www/themes/';
		$dirHandle	= opendir($themesDir);
		$themes		= array();
		
		while($file = readdir($dirHandle)) {
			if(is_dir($themesDir . '/' . $file) && ($file != '..' && $file != '.')) {
				$themes[$file] = ucfirst($file);
			}
		}
		
		return $themes;
	}
}