<?php

class Module extends programObject
{
	function Module($id = "NEW")
	{
		$this->settings = apiObject::returnAPISettings();

		if($id !== "NEW")
		{
			$sql = new sqlInterface;
			$where = (is_numeric($id) ? "`id` = $id" : "`name` = '$id'");

			// Load the information from the modules database
			$result = $sql->select("modules", "*", $where);
			if($sql->num_rows($result) == 1)
			{
				$settings = $sql->fetch_assoc($result);
				foreach($settings as $key => $data)
				{
					$this->settings[$key] = $data;
				}
	
				$this->settings['name'] = strtolower($this->settings['name']);
			}			
		}
	}
	
	function Display()
	{
		include_once($this->settings['BA_PROGRAM_DIR']."modules/".$this->settings['name']."/index.php");
		return DisplayModule();
	}

	function AdminDisplay()
	{
		include_once($this->settings['BA_PROGRAM_DIR']."modules/".$this->settings['name']."/admin.php");
		return DisplayModule();
	}

	function adminListing()
	{
		$programObject = new programObject;

		$template = new Template('admin/module_admin/adminListing.tpl', $programObject->settings['themeTemplatePath']."");
		$template->replaceTags(array(
			"rowClass"		=> apiSelectRowColor(),
			"id"			=> $this->settings['id'],
			"name"			=> ucfirst($this->settings['name']),
			"description"	=> $this->settings['description'],
			"active"		=> $this->returnStatus(),
		));
		
		return $template->text;
	}

	function changeStatus()
	{
		$newStatus = ($this->settings['active'] ? 0 : 1);
	
		$sql = new sqlInterface;
		
		if( $sql->update("modules", "active = $newStatus", "id = ".$this->settings['id'], "1") )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function configDisplay()
	{
		include_once($this->settings['BA_PROGRAM_DIR']."modules/".$this->settings['name']."/config.php");
		return displayConfig();	
	}

	function setConfigVars()
	{
		$sql = new sqlInterface;
		$result = $sql->query("SELECT `var`, `data` FROM ".$sql->settings['DB_PREFIX']."modules_config WHERE `module_id` = ".$this->settings['id']);

		while($config = $sql->fetch_assoc($result))
		{
			$this->settings[$config['var']] = $config['data'];
		}
	}

	function uninstall()
	{
		include_once($this->settings['BA_PROGRAM_DIR']."modules/".$this->settings['name']."/uninstall/uninstall.php");
		return uninstall();
	}

	function install()
	{
		include_once($this->settings['BA_PROGRAM_DIR']."modules/".$this->settings['name']."/install/install.php");
		return install();
	}

	function createNewPage($page)
	{
		include_once($this->settings['BA_PROGRAM_DIR']."modules/".$this->settings['name']."/create.php");
		return create($page);
	}
}

?>
