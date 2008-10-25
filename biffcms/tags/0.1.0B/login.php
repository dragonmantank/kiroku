<?php

session_start();
require_once 'includes/bsb.header.php';

$sc = new securityCenter();

if($_POST['log_in'])
{
	if($sc->CheckCredentials("Any", $_GET['ref']))
	{
		$error = $sc->ProcessLogin($_POST['username'], $_POST['password']);
		create_page($error);
	}
}
else
{ create_page(); }

function create_page($error = "")
{
	$programObject = new programObject;

	$loginTemplate = new Template('login.tpl', $programObject->settings['themeTemplatePath']);

	$template = new Template('main.tpl', $programObject->settings['themeTemplatePath']);

	$template->replaceTags(array(
		"body"		=> $loginTemplate->text,
		"mainmenu"	=> $programObject->generateMenu(),
		"submenu"	=> $programObject->generateSubMenu($programObject->returnHomepageID()),
		"errors"	=> $error,
		"referrer"	=> "?ref=".$_GET['ref'],
	));

	$template = $programObject->replaceGlobalTags($template);
	$template->display();
}

?>
