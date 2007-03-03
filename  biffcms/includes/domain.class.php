<?php

class Domain extends programObject
{
	function Domain($id = "NEW")
	{
		$this->settings = programObject::returnProgramSettings();
		
		if($id !== "NEW")
		{
			$sql = new sqlInterface;
			$result = $sql->query("SELECT * FROM ".$sql->settings['DB_PREFIX']."site_info WHERE id = $id");

			if($sql->num_rows($result) == 1)
			{
				$data = $sql->fetch_assoc($result);

				foreach($data as $key => $value)
				{
					$this->settings[$key] = stripslashes($value);
				}
			}
			else
			{
				$this->settings['Bad Site ID'] = $id;
				$e = new apiError("BiffSiteBuilder Domain Error", "A domain ID was specified that is not valid.", $this->settings);
			}
		}
	}
	
	function returnAdminListing()
	{
		$template = new Template('admin/domain_admin/admin_listing.tpl', $programObject->settings['themeTemplatePath']);
		$template->replaceTags($this->settings);
		$template->replaceTags(array(
			"rowClass"		=> apiSelectRowColor(),
			"status"		=> $this->returnStatus(),
			"defaultStatus"	=> $this->returnDefaultStatus(),
		));
		
		return $template->text;
	}
	
	function returnDefaultStatus()
	{
		return ($this->settings['default'] == 1 ? "Yes" : "No");
	}
	
	function setDefault()
	{
		$sql = new sqlInterface;
		
		$sql->query("UPDATE ".$sql->settings['DB_PREFIX']."site_info SET `default` = 1 WHERE id = ".$this->settings['id']);
		$sql->query("UPDATE ".$sql->settings['DB_PREFIX']."site_info SET `active` = 1 WHERE id = ".$this->settings['id']);
		$sql->query("UPDATE ".$sql->settings['DB_PREFIX']."site_info SET `default` = 0 WHERE id != ".$this->settings['id']);
		
		return true;
	}
	
	function changeStatus()
	{		
		if(!$this->settings['default'])
		{
			$sql = new sqlInterface;
			$newActive = ($this->settings['active'] ? 0 : 1);		
	
			$sql->query("UPDATE ".$sql->settings['DB_PREFIX']."site_info SET active = $newActive WHERE id = ".$this->settings['id']);
		}
		
		return true;
	}
	
	function createHomepage()
	{
		$programObject = new programObject;
		$sql = new sqlInterface;
		$module = new Module("text");
		
		$sql->query("INSERT INTO `".$sql->settings['DB_PREFIX']."pages` (`site_id`, `name`, `link_name`, `title`, `module`, `active`, `homepage`) VALUES (".$this->settings['id'].", 'Homepage', 'Home', 'Home of ".$this->settings['site_name']."', ".$module->settings['id'].", 1, 1)");
		$page = new Page(mysql_insert_id());
		$module->createNewPage($page);
	}
	
	function deleteDomain()
	{
		$sql = new sqlInterface;
		
		$result = $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."pages` WHERE `site_id` = ".$this->settings['id']);
		while($pageID = $sql->fetch_row($result))
		{
			$page = new Page($pageID[0]);
			$page->delete();
		}
	}
}

?>