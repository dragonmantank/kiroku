<?php

class AddEditUserForm extends Zend_Form
{
	public function init()
	{
		$id			= new Zend_Form_Element_Hidden('id');
		
		$username	= new Zend_Form_Element_Text('username');
		$username->setLabel('Username:');
		
		$password	= new Zend_Form_Element_Password('password');
		$password->setLabel('Password:');
		
		$first_name	= new Zend_Form_Element_Text('first_name');
		$first_name->setLabel('First Name:');
		
		$last_name	= new Zend_Form_Element_Text('last_name');
		$last_name->setLabel('Last Name:');
		
		$email		= new Zend_Form_Element_Text('email');
		$email->setLabel('Email Address:');
		
		$submit		= new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Save User');
		
		$this->addElements(array(
			$id, $username, $password, $first_name, $last_name, $email, $submit
		));
	}
}