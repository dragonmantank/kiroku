<?php

function displayConfig()
{
	$programObject = new programObject;

	if( (trim($_GET['mid']) == "") || (!is_numeric($_GET['mid'])) )
	{
		header("Location: ".$programObject->settings['BA_PROGRAM_DIR']."admin.php?af=ma");
	}
	$module = new Module($_GET['mid']);

	if($_POST['saveEditor'])
	{
		$editor = $_POST['editor'];

		$sql = new sqlInterface;
		if($sql->query("UPDATE ".$sql->settings['DB_PREFIX']."modules_config SET `data` = '$editor' WHERE `module_id` = ".$module->settings['id']." AND `var` = 'editor'"))
		{
			$message = "Settings have been saved.";
		}
		else
		{
			$message = "Settings were not able to be saved.";
		}
	}

	$module->setConfigVars();

	$template = new Template('config.tpl', $programObject->settings['modulesDir'].$module->settings['name']."/templates/admin/");
	$template->replaceTags(array(
		"message"	=> $message,
		"editorOptions"	=> apiBuildOptions(array("basic"=>"basic"), $module->settings['editor']),
	));

	return $template->text;
}

?>
