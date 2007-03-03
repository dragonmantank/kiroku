<?php
session_start();
require_once 'includes/bsb.header.php';

$sc = new securityCenter();
$sc->CheckCredentials("Administrators", "admin.php", true);

$programObject = new programObject;

$pageInfo 	= PageInfo();
$subMenu	= buildSubMenu();

$template = new Template('main.tpl', $programObject->settings['themePath']."templates/");
$template->replaceTags(array(
	"body"			=> $pageInfo['body'],
	"submenu"		=> $subMenu,
	"mainmenu"		=> $programObject->generateAdminMenu(),
	"page_title"	=> $pageInfo['title'],
	"pid"			=> $_GET['pid'],
));
$template = $programObject->replaceGlobalTags($template);
$template->display();

// ============================================================================
function pageInfo()
{
	$programObject = new programObject;
	
	switch($_GET['af'])
	{
		case "ma":
			include_once $programObject->settings['BA_PROGRAM_DIR'].'admin/module_admin.php';
			$pageInfo['body']	= afMain();
			$pageInfo['title']	= "Module Admin";
			break;
		case "pa":
			include_once $programObject->settings['BA_PROGRAM_DIR'].'admin/page_admin.php';
			$pageInfo['body']	= afMain();
			$pageInfo['title']	= "Page Administration";
			break;
		case "sa":
			include_once $programObject->settings['BA_PROGRAM_DIR'].'admin/site_information.php';
			$pageInfo['body'] 	= afMain();
			$pageInfo['title']	= "Site Information";
			break;
		case "da":
			include_once $programObject->settings['BA_PROGRAM_DIR'].'admin/domain_admin.php';
			$pageInfo['body']	= afMain();
			$pageInfo['title']	= "Domain Administration";
			break;
		default:
			$pageInfo['body'] 	= adminMain();
			$pageInfo['title']	= "Administration";
			break;
	}

	return $pageInfo;
}

function buildSubMenu()
{
	$programObject = new programObject;

	$template = new Template('submenu.tpl', $programObject->settings['themeTemplatePath']."admin/");
	$template = $programObject->replaceGlobalTags($template);

	return $template->text;
}

function adminMain()
{
	$programObject = new programObject;

	$template = new Template('admin.tpl', $programObject->settings['themeTemplatePath']."admin/");
	$template = $programObject->replaceGlobalTags($template);

	return $template->text;
}

?>
