<?php

class Sections extends Zend_Db_Table_Abstract
{
	protected $_name	= 'cms_sections';
	
	public function fetchId($name)
	{
		$id	= $this->fetchRow(
			$this->select()
				 ->from($this, array('id'))
				 ->where('name = ?', $name)
			);
			
		return $id->id;
	}
	
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
		
		$dir				= INSTALL_PATH . '/sections/';
		$dirHandle			= opendir($dir);
		$uninstalledPlugins	= array();
		while($file = readdir($dirHandle)) {
			if(is_dir($dir . $file) && !in_array(strtolower($file), $installedPlugins)) {
				if( ($file != '.') && ($file != '..')) {
					$uninstalledPlugins[] = array('name' => strtolower($file));
				}
			}
		}
		
		return $uninstalledPlugins;
	}
	
	public function changeStatus($id)
	{
		$module			= $this->fetchRow($this->select()->where('id = ?', $id));
		
		$module->active	= ($module->active ? 0 : 1);
		$module->save();
	
		return $module->active;
	}
}
