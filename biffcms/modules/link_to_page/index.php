<?php

function DisplayModule()
{
	$programObject = new programObject;
	$page = new Page($_GET["pid"]);
	$module = new Module($page->settings['module']);
	$module->setConfigVars();

	$sql = new sqlInterface();
	$result = $sql->select("module_link_to_page", "url", "page_id = ".$page->settings['id']);
	$url = $sql->fetch_row($result);

	if($module->settings['show_redirect_message'] == "yes")
	{
		$template = new Template('message.tpl', $programObject->settings['moduleDir']."link_to_page/templates/");
		$template->replaceTags(array(
			"redirect_timeout"	=> $module->settings['redirect_timeout'],
			"url"			=> $url[0],
			"redirect_message"	=> $module->settings['redirect_message'],
		));
		return $template->text;
	}
	else
	{
		header("Location: ".$url[0]);
	}
}

?>
