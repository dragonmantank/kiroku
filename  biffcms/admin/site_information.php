<?php

$sc = new securityCenter();
$sc->CheckCredentials("Site Admin", "admin.php", true);

function afMain()
{
	$programObject = new programObject;

	if($_POST['saveSiteInfo'])
	{
		$site_name	= $_POST['site_name'];
		$site_tagline	= $_POST['site_tagline'];
		$admin_name	= $_POST['admin_name'];
		$admin_email	= $_POST['admin_email'];
		$current_theme	= $_POST['current_theme'];

		$set = array(
			"site_name = '".mysql_escape_string($site_name)."'",
			"site_tagline = '".mysql_escape_string($site_tagline)."'",
			"admin_name = '".mysql_escape_string($admin_name)."'",
			"admin_email = '".mysql_escape_string($admin_email)."'",
			"current_theme = '".mysql_escape_string($current_theme)."'",
		);
		$sql = new sqlInterface;
		$imploded = implode(",", $set);
		$sql->query("UPDATE `".$sql->settings['DB_PREFIX']."site_info` SET $imploded WHERE `id` = ".$programObject->settings['site_id']);
		
		$message = "Settings have been saved.";
	}
	elseif($_POST['Cancel'])
	{
		header("Location: admin.php");
	}

	$template = new Template('site_info.tpl', $programObject->settings['themeTemplatePath']."admin/");
	$template->replaceTags(array(
		"message"	=> $message,
		"site_name"	=> $programObject->GetWebsiteInfo("site_name"),
		"site_tagline"	=> $programObject->GetWebsiteInfo("site_tagline"),
		"admin_name"	=> $programObject->GetWebsiteInfo("admin_name"),
		"admin_email"	=> $programObject->GetWebsiteInfo("admin_email"),
		"theme_list"	=> $programObject->generateThemeList($programObject->GetWebsiteInfo("current_theme")),
	));
	return $template->text;
}

?>
