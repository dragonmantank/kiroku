<?php

$sc = new securityCenter();
$sc->CheckCredentials("Domain Admin", "admin.php", true);

function afMain()
{
	switch($_GET['f'])
	{
		case "ad":
			$body = addDomain();
			break;
		case "d":
			$body = deleteDomain();
			break;
		case "e":
			$body = editDomain();
			break;
		case "sd":
			$body = setDefault();
			break;
		case "cs":
			$body = changeStatus();
			break;
		default:
			$body = listDomains();
			break;
	}

	return $body;
}

function listDomains()
{
	$programObject = new programObject;
	$sql = new sqlInterface;
	
	$result = $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."site_info`");
	apiSelectRowColor(true);
	while($domainID = $sql->fetch_row($result))
	{
		$domain = new Domain($domainID[0]);
		$html .= $domain->returnAdminListing();
	}
	
	$template = new Template('admin/domain_admin/domain_list.tpl', $programObject->settings['themeTemplatePath']);
	$template->replaceTags(array(
		"domains"	=> $html,
	));
	
	return $template->text;
}

function addDomain()
{
	$programObject = new programObject;
	
	if($_POST['saveSiteInfo'])
	{
		$site_name		= mysql_escape_string($_POST['site_name']);
		$url			= mysql_escape_string($_POST['url']);
		$server_path	= mysql_escape_string($_POST['server_path']);
		$site_tagline	= mysql_escape_string($_POST['site_tagline']);
		$admin_name		= mysql_escape_string($_POST['admin_name']);
		$admin_email	= mysql_escape_string($_POST['admin_email']);
		$current_theme	= mysql_escape_string($_POST['current_theme']);
		$default		= $_POST['default'];
		$active			= $_POST['active'];
		
		$columns	= array("site_name", "url", "server_path", "site_tagline", "admin_name", "admin_email", "current_theme", "default", "active");
		$values		= array($site_name, $url, $server_path, $site_tagline, $admin_name, $admin_email, $current_theme, $default, $active);
		
		$sql = new sqlInterface;
		$sql->query("INSERT INTO `".$sql->settings['DB_PREFIX']."site_info` (`url`, `server_path`, `site_name`, `site_tagline`, `admin_name`, `admin_email`, `current_theme`, `default`, `active`) VALUES ('$url', '$server_path', '$site_name', '$site_tagline', '$admin_name', '$admin_email', '$current_theme', $default, $active)");
		
		$domain = new Domain($sql->insert_id());
		$domain->createHomepage();
		
		if($default)
		{
			$domain->setDefault();
		}
		
		header("Location: admin.php?af=da");
	}
	elseif($_POST['cancel'])
	{
		header("Location: admin.php?af=da");
	}
	
	$template = new Template('admin/domain_admin/domain_info.tpl', $programObject->settings['themeTemplatePath']);
	$template->replaceTags(array(
		"do"			=> "Add New",
		"message"		=> "",
		"site_name"		=> "",
		"url"			=> "",
		"server_path"	=> $_SERVER['DOCUMENT_ROOT'],
		"site_tagline"	=> "",
		"admin_name"	=> "",
		"admin_email"	=> "",
		"theme_list"	=> $programObject->generateThemeList(),
		"defaultSelect"	=> apiBuildOptions(array("No", "Yes"), 0),
		"activeSelect"	=> apiBuildOptions(array("No", "Yes"), 0),
	));
	
	return $template->text;
}

function deleteDomain()
{
	$programObject = new programObject;

	if($_POST['confirmDelete'])
	{
		$sql = new sqlInterface;
		$domain = new Domain($_GET['did']);

		if($domain->settings['default'] !== 1)
		{
			$domain->deleteDomain();
			$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."site_info WHERE id = ".$domain->settings['id']);
		}
	}

	if($_POST['confirmDelete'] || $_POST['cancel'])
	{
		header("Location: admin.php?af=da");
	}

	$domain = new domain($_GET['did']);
	$template = new Template('admin/domain_admin/confDelete.tpl', $programObject->settings['themeTemplatePath']."");
	$template->replaceTags(array(
		"site_name"	=> $domain->settings['site_name'],
	));

	return $template->text;
}

function editDomain()
{
	$programObject = new programObject;
	
	if($_POST['saveSiteInfo'])
	{
		$site_name		= mysql_escape_string($_POST['site_name']);
		$url			= mysql_escape_string($_POST['url']);
		$server_path	= mysql_escape_string($_POST['server_path']);
		$site_tagline	= mysql_escape_string($_POST['site_tagline']);
		$admin_name		= mysql_escape_string($_POST['admin_name']);
		$admin_email	= mysql_escape_string($_POST['admin_email']);
		$current_theme	= mysql_escape_string($_POST['current_theme']);
		$default		= $_POST['default'];
		$active			= $_POST['active'];
		
		$columns	= array("site_name", "url", "server_path", "site_tagline", "admin_name", "admin_email", "current_theme", "default", "active");
		$values		= array($site_name, $url, $server_path, $site_tagline, $admin_name, $admin_email, $current_theme, $default, $active);
		
		for($i = 0; $i < count($columns); $i++)
		{
			$set[] = "`".$columns[$i]."` = '".$values[$i]."'";
		}
		
		$sql = new sqlInterface;
		$sql->update("site_info", $set, "id = ".$_GET['did']);
		
		if($default)
		{
			$domain = new Domain($_GET['did']);
			$domain->setDefault();
		}
		
		$message = "Domain settings have been saved.";
	}
	elseif($_POST['cancel'])
	{
		header("Location: admin.php?af=da");
	}
	
	$domain = new Domain($_GET['did']);
	$template = new Template('admin/domain_admin/domain_info.tpl', $programObject->settings['themeTemplatePath']);
	$template->replaceTags(array(
		"do"			=> "Edit",
		"message"		=> $message,
		"site_name"		=> $domain->settings['site_name'],
		"url"			=> $domain->settings['url'],
		"server_path"	=> $domain->settings['server_path'],
		"site_tagline"	=> $domain->settings['site_tagline'],
		"admin_name"	=> $domain->settings['admin_name'],
		"admin_email"	=> $domain->settings['admin_email'],
		"theme_list"	=> $programObject->generateThemeList($domain->settings['current_theme']),
		"defaultSelect"	=> apiBuildOptions(array("No", "Yes"), $domain->settings['default']),
		"activeSelect"	=> apiBuildOptions(array("No", "Yes"), $domain->settings['active']),
	));
	
	return $template->text;
}

function setDefault()
{
	$domain = new Domain($_GET['did']);
	$domain->setDefault();
	
	header("Location: admin.php?af=da");
}

function changeStatus()
{
	$programObject = new programObject;

	if(is_numeric($_GET['did']))
	{
		$domain = new Domain($_GET['did']);
		$domain->changeStatus();
	}
	else
	{
		$e = new apiError("Domain Admin - Change Status Error", "A non-numeric variable was passed: '".$_GET['did']."'. Please either pass a domain ID or check the script that called this page.", $programObject->settings);
	}

	header("Location: admin.php?af=da");
}

?>
