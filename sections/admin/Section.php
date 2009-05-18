<?php

class admin_Section extends Kiroku_Section
{
	protected $_name	= 'admin';
	
	public function install()
	{
		return true;
	}
	
	public function uninstall()
	{
		return true;
	}
	
	public function deactivate()
	{
		return true;
	}
}
