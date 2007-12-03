<?php

function displayModule()
{
	switch($_GET['f'])
	{
		default:
			$body = text_Main();
			break;
	}

	return $body;
}

function text_Main()
{
	$programObject = new programObject;
	$sql = new sqlInterface;

	if(is_numeric($_GET['pid']))
	{
		if($_POST['save_exit'])
		{
			$name = mysql_escape_string($_POST['name']);
			$url  = mysql_escape_string($_POST['url']);
			$id = $_GET['pid'];
			$sql->query("UPDATE `".$sql->settings['DB_PREFIX']."module_link_to_page` SET `name` = '$name', `url` = '$url' WHERE `page_id` = $id");
		}

		if($_POST['save_exit'] || $_POST['cancel'])
		{
			header("Location: admin.php?af=pa");
		}

		$page = new Page($_GET['pid']);
		$module = new Module($page->settings['module']);

		$data = $sql->fetch_assoc( $sql->query("SELECT * FROM `".$sql->settings['DB_PREFIX']."module_link_to_page` WHERE `page_id` = ".$page->settings['id']) );

		$template = new Template("edit.tpl", $programObject->settings['modulesDir']."link_to_page/templates/admin/");
		$template->replaceTags(array(
			"page_name"	=> $page->settings['name'],
			"name"		=> $data['name'],
			"url"		=> $data['url'],
		));

		return $template->text;

	}
	else
	{
		$e = new apiError("Edit Page", "A non-numeric Page ID was passed.", $programObject->settings);
	}
}

?>
