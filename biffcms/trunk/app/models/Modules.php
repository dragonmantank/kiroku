<?php

class Modules extends Zend_Db_Table_Abstract
{
	protected $_name	= 'cms_modules';
	
	public function fetchInstalled()
	{
		return $this->fetchAll();
	}
	
	public function fetchUninstalled()
	{
		$installed			= $this->fetchAll()->toArray();
		$installedPlugins	= array();
		foreach($installed as $plugin) {
			$installedPlugins[]	= $plugin['name'];
		}
		
		$dir				= INSTALL_PATH . '/plugins/';
		$dirHandle			= opendir($dir);
		$uninstalledPlugins	= array();
		while($file = readdir($dirHandle)) {
			if(is_dir($file) && !in_array(strtolower($file), $installedPlugins)) {
				if( ($file != '.') && ($file != '..')) {
					$uninstalledPlugins[] = strtolower($file);
				}
			}
		}
		
		return $uninstalledPlugins;
	}
	
	public function changeStatus($id)
	{
		$pageTable	= new Pages();
		$pages		= $pageTable->fetchAll($pageTable->select()->where('module = ?', $id)->where('active = 1'));
		if(count($pages)) {
			throw new Exception('There are active pages using this module.');
		} else {
			$module			= $this->fetchRow($this->select()->where('id = ?', $id));
		
			$module->active	= ($module->active ? 0 : 1);
			$module->save();
		
			return $module->active;
		}
	}
}