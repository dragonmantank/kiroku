<?php

class AddEditPageForm extends Zend_Form
{
	public function init()
	{
		$db					= Zend_Registry::get('db');
		$pageName			= new Zend_Form_Element_Text('pageName');
		$pageTitle			= new Zend_Form_Element_Text('pageTitle');
		$linkName			= new Zend_Form_Element_Text('linkName');
		$pageDescription	= new Zend_Form_Element_Textarea('pageDescription');
		$parent_page		= new Zend_Form_Element_Select('parent_page');
		$module				= new Zend_Form_Element_Select('moduleId');
		$status				= new Zend_Form_Element_Select('status');
		$submit				= new Zend_Form_Element_Submit('submit');
		$id					= new Zend_Form_Element_Hidden('id');
		
		$this->setAttrib('id', 'addEditForm');
		$pageName->setLabel('Page Name:');
		$pageTitle->setLabel('Page Title:');
		$linkName->setLabel('Link Name:');
		
		$pageDescription->setLabel('Page Description:')
						->setAttribs(array('cols'=>50, 'rows'=>10));
		
		$pages	= $db->fetchPairs('SELECT `id`,`name` FROM `cms_pages` ORDER BY `name` ASC');
		$pages	= Tws_Functions::array_merge(array('0'=>'None'), $pages);
		$parent_page->setLabel('Parent:')
					->setMultiOptions($pages);
					
		$modules	= $db->fetchPairs('SELECT `id`,`name` FROM `cms_modules` WHERE `active` = 1 ORDER BY `name` ASC');
		$module->setLabel('Module:')
			   ->setMultiOptions($modules);
			   
		$status->setLabel('Status:')
			   ->setMultiOptions(array('Inactive', 'Active'));
			   
		$submit->setLabel('Save');
		
		$this->addElements(array(
			$pageName, $pageTitle, $linkName, $pageDescription, $parent_page,
			$module, $status, $submit, $id,
		));
	}
}