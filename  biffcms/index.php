<?php

session_start();
require_once 'includes/bsb.header.php';

$programObject = new programObject;
$_SESSION['site_id'] = $programObject->settings['site_id'];

$pageID = (trim($_GET['pid']) == "" ? $programObject->returnHomepageID() : $_GET['pid']);
$page = new Page($pageID);

$template = new Template('main.tpl', $programObject->settings['themeTemplatePath']);

$template->replaceTags(array(
	"mainmenu"	=> $programObject->generateMenu(),
	"submenu"	=> $programObject->generateSubMenu($page->settings['id']),
	"body"		=> $page->display(),
	"page_title"	=> $page->settings['title'],
));

$template = $programObject->replaceGlobalTags($template);
$template->display();

?>
