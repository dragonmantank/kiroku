<?php

class auth_Section extends Kiroku_Section
{
	protected $_name	= 'auth';
	
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
