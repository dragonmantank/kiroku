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
		if($_POST['save_exit'] || $_POST['save_reload'])
		{
			$text = mysql_escape_string($_POST['page_text']);
			$id = $_GET['pid'];

			$sql->query("UPDATE `".$sql->settings['DB_PREFIX']."module_text` SET `text` = '$text' WHERE `page_id` = $id");
		}

		if($_POST['save_exit'] || $_POST['cancel'])
		{
			header("Location: admin.php?af=pa");
		}

		$page = new Page($_GET['pid']);
		$module = new Module($page->settings['module']);
		$module->setConfigVars();

		$data = $sql->fetch_assoc( $sql->query("SELECT `text` FROM ".$sql->settings['DB_PREFIX']."module_text WHERE `page_id` = ".$page->settings['id']) );

		$template = new Template($module->settings['editor']."_edit.tpl", $programObject->settings['modulesDir']."text/templates/admin/");
		$template->replaceTags(array(
			"page_name"	=> $page->settings['name'],
			"page_text"	=> stripslashes($data['text']),
		));

		return $template->text;

	}
	else
	{
		$e = new apiError("Edit Page", "A non-numeric Page ID was passed.", $programObject->settings);
	}
}

?>
