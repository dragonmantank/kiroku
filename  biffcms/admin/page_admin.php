<?php

$sc = new securityCenter();
$sc->CheckCredentials("Page Admin", "admin.php", true);

function afMain()
{
	switch($_GET['f'])
	{
		case "sh":
			setHomepage();
			break;
		case "del":
			$body = deletePage();
			break;
		case "ep":
			$body = editPage();
			break;
		case "es":
			$body = editSettings();
			break;
		case "cs":
			changeStatus();
			break;
		case "ap":
			$body = addPage();
			break;
		default:
			$body = listPages();
			break;
	}

	return $body;
}

function listPages()
{
	$programObject = new programObject;

	$sql = new sqlInterface;
	$result = $sql->query("SELECT id FROM ".$sql->settings['DB_PREFIX']."pages WHERE site_id = ".$programObject->settings['site_id']);

	apiSelectRowColor(true);
	while($pageID = $sql->fetch_row($result))
	{
		$page = new Page($pageID[0]);
		$html .= $page->adminListing();
	}

	$template = new Template('admin/page_admin/listPages.tpl', $programObject->settings['themeTemplatePath']."");
	$template->replaceTags(array(
		"pageList"	=> $html,
	));

	return $template->text;
}

function addPage()
{
	$programObject = new programObject;

	if($_POST['savePage'])
	{
		$name		= $_POST['name'];
		$title		= $_POST['title'];
		$link_name	= $_POST['link_name'];
		$description	= $_POST['description'];
		$parent		= $_POST['parent'];
		$module		= $_POST['module'];
		$active		= $_POST['active'];

		$sql = new sqlInterface;
		if( $sql->query("INSERT INTO `".$sql->settings['DB_PREFIX']."pages` (`site_id`, `name`, `link_name`, `title`, `description`, `parent_page`, `module`, `active`) VALUES ('".$programObject->settings['site_id']."', '$name', '$link_name', '$title', '".mysql_escape_string($description)."', '$parent', '$module', '$active')") )
		{
			$page = new Page($sql->insert_id());
			$newmodule = new Module($module);
			$newmodule->createNewPage($page);

			header("Location: admin.php?af=pa");
		}
		else
		{
			$e = new apiError("Page Administration - Addition Error", "There was a problem adding the page into the system. SQL information as follows", $sql->settings);
		}
	}
	elseif($_POST['cancel'])
	{
		header("Location: admin.php?af=pa");
	}
	else
	{
		$template = new Template('admin/page_admin/addEditPage.tpl', $programObject->settings['themeTemplatePath']."");
		$template->replaceTags(array(
			"function"		=> "Add New",
			"name"			=> "",
			"title"			=> "",
			"link_name"		=> "",
			"description"		=> "",
			"parentSelectOptions"	=> buildParentSelectBox(),
			"moduleSelectOptions"	=> buildModuleSelectBox(),
			"activeSelectOptions"	=> apiBuildOptions(array("1" => "Active", "0" => "Inactive")),
		));

		return $template->text;
	}
}

function changeStatus()
{
	$programObject = new programObject;

	if(is_numeric($_GET['pid']))
	{
		$page = new Page($_GET['pid']);
		$page->changeStatus();
	}
	else
	{
		$e = new apiError("Page Admin - Change Status Error", "A non-numeric variable was passed: '".$_GET['pid']."'. Please either pass a page ID or check the script that called this page.", $programObject->settings);
	}

	header("Location: admin.php?af=pa");
}

function editSettings($error = "")
{
	$programObject = new programObject;

	if($_POST['savePage'] )
	{
		if(is_numeric($_GET['pid']))
		{
			$name		= trim($_POST['name']);
			$title		= trim($_POST['title']);
			$link_name	= trim($_POST['link_name']);
			$description	= trim($_POST['description']);
			$parent		= $_POST['parent'];
			$module		= $_POST['module'];
			$active		= $_POST['active'];

			if( ($name !== "") || ($title !== "") || ($link_name !== "") )
			{
				$sql = new sqlInterface;
				if( $sql->query("UPDATE `".$sql->settings['DB_PREFIX']."pages` SET `name` = '".mysql_escape_string($name)."', `link_name` = '".mysql_escape_string($link_name)."', `title` = '".mysql_escape_string($title)."', `description` = '".mysql_escape_string($description)."', `parent_page` = $parent, `module` = $module, `active` = $active WHERE id = ".$_GET['pid']) )
				{
					header("Location: admin.php?af=pa");
				}
				else
				{
					$sql->generateErrorStats($sql->settings['LAST_QUERY']);
					$e = new apiError("Page Administration - Settings Error", "There was a problem adding the page into the system. SQL information as follows", $sql->settings);
				}
			}
			else
			{
				editSettings("Either the name, title, or the link name was left blank");
			}
		}
		else
		{
			$e = new apiError("Page Admin - Save Settings Error", "A non-numeric variable was passed: '".$_GET['pid']."'. Please either pass a page ID or check the script that called this page.", $programObject->settings);
		}
	}
	elseif($_POST['cancel'])
	{
		header("Location: admin.php?af=pa");
	}
	else
	{
		if(is_numeric($_GET['pid']))
		{
			$page = new Page($_GET['pid']);

			$template = new Template('admin/page_admin/addEditPage.tpl', $programObject->settings['themeTemplatePath']."");
			$template->replaceTags(array(
				"function"		=> "Edit",
				"name"			=> $page->settings['name'],
				"title"			=> $page->settings['title'],
				"link_name"		=> $page->settings['link_name'],
				"description"		=> stripslashes($page->settings['description']),
				"parentSelectOptions"	=> buildParentSelectBox($page->settings['parent_page']),
				"moduleSelectOptions"	=> buildModuleSelectBox($page->settings['module']),
				"activeSelectOptions"	=> apiBuildOptions(array("1" => "Active", "0" => "Inactive"), $page->settings['active']),
			));

			return $template->text;
		}
		else
		{
			$e = new apiError("Page Admin - Edit Settings Error", "A non-numeric variable was passed: '".$_GET['pid']."'. Please either pass a page ID or check the script that called this page.", $programObject->settings);
		}
	}
}

function editPage()
{
	if(is_numeric($_GET['pid']))
	{
		if($_POST['save_exit'] || $_POST['save_reload'])
		{
//print_r($_POST);
			$sql = new sqlInterface;
			$text = mysql_escape_string($_POST['page_text']);

			$sql->query("UPDATE `".$sql->settings['DB_PREFIX']."module_text` SET `text` = '$text' WHERE `page_id` = ".$_GET['pid']);
//print $sql->settings['LAST_QUERY'];
		}
		
		if($_POST['cancel'] || $_POST['save_exit'])
		{
			header("Location: admin.php?af=pa");
		}

		$page = new Page($_GET['pid']);
		return $page->adminDisplay();
	}
	else
	{
		$e = new apiError("Page Admin - Edit Page Error", "A non-numeric variable was passed: '".$_GET['pid']."'. Please either pass a page ID or check the script that called this page.", $programObject->settings);
	}
}

function deletePage()
{
	$programObject = new programObject;

	if($_POST['confirmDelete'])
	{
		$sql = new sqlInterface;
		$page = new Page($_GET['pid']);
		$module = new Module($page->settings['module']);

		$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."pages WHERE id = ".$page->settings['id']);
		$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."module_".$module->settings['name']." WHERE page_id = ".$page->settings['id']);
		header("Location: admin.php?af=pa");
	}

	if($_POST['confirmDelete'] || $_POST['cancel'])
	{
		header("Location: admin.php?af=pa");
	}

	$page = new Page($_GET['pid']);
	$template = new Template('admin/page_admin/confDelete.tpl', $programObject->settings['themeTemplatePath']."");
	$template->replaceTags(array(
		"page_name"	=> $page->settings['name'],
	));

	return $template->text;
}

function setHomepage()
{
	$page = new Page($_GET['pid']);
	$page->setHomepage();

	$programObject = new programObject;

	header("Location: admin.php?af=pa");
}

// ============================================================================

function buildParentSelectBox($default = "")
{
	$programObject = new programObject;
	$sql = new sqlInterface;

	//$result = $sql->query("SELECT `id`, `name` FROM ".$sql->settings['DB_PREFIX']."pages WHERE `parent_page` = 0 ORDER BY `name` ASC");

	$result = $sql->query("SELECT `id`, `name` FROM ".$sql->settings['DB_PREFIX']."pages WHERE `active` = 1 AND site_id = ".$programObject->settings['site_id']." ORDER BY `name` ASC");

	$pages[0] = "No Parent";

	while($data = $sql->fetch_assoc($result))
	{
		$pages[$data['id']] = $data['name'];
	}

	return apiBuildOptions($pages, $default);
}

function buildModuleSelectBox($default = "")
{
	$programObject = new programObject;
	$sql = new sqlInterface;

	$result = $sql->query("SELECT `id`, `name` FROM ".$sql->settings['DB_PREFIX']."modules WHERE `active` = 1 ORDER BY `name` ASC");

	while($data = $sql->fetch_assoc($result))
	{
		$pages[$data['id']] = ucfirst($data['name']);
	}

	return apiBuildOptions($pages, $default);
}

?>
