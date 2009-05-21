<?php

class default_Section extends Kiroku_Section
{
	protected $_name	= 'default';
	
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
