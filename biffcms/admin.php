<?php
/******************************************************************************
 * Filename:		admin.php
 * Description:		Calls the appropriate admin section of the website.
 * Creation Date:	Unknown (before the 03/03/2007 modifications)
 * Original Author:	Chris Tankersley (dragonmantank@gmail.com)
 * 
 * Custom Modifications:
 * =====================
 *
 * MM/DD/YYYY	PRGMR	DESCRIPTION OF CHANGES
 * ----------	-----	----------------------
 *
 * BSB Modifications:
 * =====================
 * 
 * MM/DD/YYYY	PRGMR	DESCRIPTION OF CHANGES
 * ----------	-----	----------------------
 * 03/03/2007	CRT		Converted to use the new global vars for user groups
 *****************************************************************************/
 
session_start();
require_once 'includes/bsb.header.php';

$sc = new securityCenter();
$sc->CheckCredentials(CMS_GROUP_ADMINISTRATOR, "admin.php", true);

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

// ========================================================================
// Function:	pageInfo
// Purpose:		Includes the needed Admin section and sets up some info
// Parameters:	(none)
// Returns:		array(
//					str body
//					str title
//				)
// Created:		Unknown (Before 03/30/2007 Update)
// 
// Modifications:
// ==============
// CRT = Chris Tankersley
// 
// MM/DD/YYYY	PGRMR	DESCRIPTION OF CHANGES
// ----------	-----	----------------------
// 03/30/2007	CRT		Added 'User Admin' check and organized cases 
//						alphabetically
// ========================================================================
function pageInfo()
{
	$programObject = new programObject;
	
	switch($_GET['af'])
	{
		case "da":
			include_once $programObject->settings['BA_PROGRAM_DIR'].'admin/domain_admin.php';
			$pageInfo['body']	= afMain();
			$pageInfo['title']	= "Domain Administration";
			break;
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
		case "ua":
			include_once $programObject->settings['BA_PROGRAM_DIR'].'admin/user_admin.php';
			$pageInfo['body']	= afMain();
			$pageInfo['title']	= "User Administration";
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
