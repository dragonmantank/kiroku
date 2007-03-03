<?php

function displayConfig()
{
	$programObject = new programObject;

	if( (trim($_GET['mid']) == "") || (!is_numeric($_GET['mid'])) )
	{
		header("Location: ".$programObject->settings['BA_PROGRAM_DIR']."admin.php?af=ma");
	}
	$module = new Module($_GET['mid']);

	if($_POST['saveConfig'])
	{
		$show_redirect_message = $_POST['show_redirect_message'];
		$redirect_message = $_POST['redirect_message'];
		$redirect_timeout = $_POST['redirect_timeout'];

		$sql = new sqlInterface;
		if($sql->query("UPDATE ".$sql->settings['DB_PREFIX']."modules_config SET `data` = '$show_redirect_message' WHERE `module_id` = ".$module->settings['id']." AND `var` = 'show_redirect_message'"))
		{
			if($sql->query("UPDATE ".$sql->settings['DB_PREFIX']."modules_config SET `data` = '$redirect_message' WHERE `module_id` = ".$module->settings['id']." AND `var` = 'redirect_message'"))
			{
				if($sql->query("UPDATE ".$sql->settings['DB_PREFIX']."modules_config SET `data` = '$redirect_timeout' WHERE `module_id` = ".$module->settings['id']." AND `var` = 'redirect_timeout'"))
				{
					$message = "Settings have been saved.";
				}
			}
			else
			{
				$message = "Settings were not able to be saved.";
			}
		}
		else
		{
			$message = "Settings were not able to be saved.";
		}
	}

	$module->setConfigVars();

	$template = new Template('config.tpl', $programObject->settings['modulesDir'].$module->settings['name']."/templates/admin/");
	$template->replaceTags(array(
		"message"		=> $message,
		"redirectOptions"	=> apiBuildOptions(array("yes"=>"Yes", "no"=>"No"), $module->settings['show_redirect_message']),
		"redirect_message"	=> $module->settings['redirect_message'],
		"redirect_timeout"	=> $module->settings['redirect_timeout'],
	));

	return $template->text;
}

?>
