<?php

class Page extends programObject
{
	function Page($id = "NEW")
	{
		$this->settings = apiObject::returnAPISettings();

		if($id !== "NEW")
		{
			$sql = new sqlInterface;
			$result = $sql->query("SELECT * FROM ".$sql->settings['DB_PREFIX']."pages WHERE id = $id");

			if($sql->num_rows($result) == 1)
			{
				$data = $sql->fetch_assoc($result);

				foreach($data as $key => $value)
				{
					$this->settings[$key] = $value;
				}
			}
			else
			{
				$this->settings['Bad Page ID'] = $id;
				$e = new apiError("BiffSiteBuilder Page Error", "A page ID was specified that is not valid.", $this->settings);
			}
		}
	}

	function display()
	{
		$module = new Module($this->getModuleName());
		return $module->Display();
	}

	function adminDisplay()
	{
		$module = new Module($this->getModuleName());
		return $module->AdminDisplay();
	}

	function getModuleName()
	{
		$sql = new sqlInterface;
		$name = $sql->fetch_row( $sql->query("SELECT name FROM ".$sql->settings['DB_PREFIX']."modules WHERE id = ".$this->settings['module']) );
		return $name[0];
	}

	function changeStatus()
	{		
		if(!$this->settings['homepage'])
		{
			$sql = new sqlInterface;
			$newActive = ($this->settings['active'] ? 0 : 1);		
	
			$sql->query("UPDATE ".$sql->settings['DB_PREFIX']."pages SET active = $newActive WHERE id = ".$this->settings['id']);
		}
		
		return true;
	}

	function delete()
	{
		$sql = new sqlInterface();
		$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."pages WHERE id = ".$this->settings['id']);
		$module_name = strtolower($this->GetModuleName());
		$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."module_$module_name WHERE page_id = ".$this->settings['id']);	}

	function adminListing()
	{
		$programObject = new programObject;
	
		$template = new Template('admin/page_admin/adminListing.tpl', $programObject->settings['themeTemplatePath']."");
		$template->replaceTags(array(
			"rowClass"		=> apiSelectRowColor(),
			"id"			=> $this->settings['id'],
			"name"			=> stripslashes($this->settings['name']),
			"link_name"		=> stripslashes($this->settings['link_name']),
			"title"			=> stripslashes($this->settings['title']),
			"description"	=> stripslashes($this->settings['description']),
			"parent_page"	=> $this->returnParentName(),
			"module"		=> stripslashes(ucfirst($this->getModuleName())),
			"active"		=> $this->returnStatus(),
			"homepage"		=> $this->returnHomepageStatus(),
		));
		
		return $template->text;
	}

	function returnParentName()
	{
		$sql = new sqlInterface;
		$data = $sql->fetch_row( $sql->query("SELECT name FROM ".$sql->settings['DB_PREFIX']."pages WHERE id = ".$this->settings['parent_page']) );

		return ($data[0] == "" ? "No Parent" : stripslashes($data[0]));
	}

	function returnHomepageStatus()
	{
		return ($this->settings['homepage'] == 1 ? "Yes" : "No");
	}

	function setHomepage()
	{
		$programObject = new programObject;
		$sql = new sqlInterface;
		
		$sql->query("UPDATE ".$sql->settings['DB_PREFIX']."pages SET homepage = 1 WHERE id = ".$this->settings['id']." AND site_id = ".$programObject->settings['site_id']);
		$sql->query("UPDATE ".$sql->settings['DB_PREFIX']."pages SET active = 1 WHERE id = ".$this->settings['id']." AND site_id = ".$programObject->settings['site_id']);
		$sql->query("UPDATE ".$sql->settings['DB_PREFIX']."pages SET homepage = 0 WHERE id != ".$this->settings['id']." AND site_id = ".$programObject->settings['site_id']);
		
		return true;
	}

	function numChildPages()
	{
		$sql = new sqlInterface;
		$result = $sql->query("SELECT id FROM ".$sql->settings['DB_PREFIX']."pages WHERE parent_page = ".$this->settings['id']);
		return $sql->num_rows($result);
	}
}

?>
