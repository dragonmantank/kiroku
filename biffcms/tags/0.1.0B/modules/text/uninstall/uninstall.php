<?php

function uninstall()
{
	// Begin the uninstall script. This is old code but still works, so just encased it in output buffering. If you are enabling this module to be uninstalled, uncommment all
	// of the code until you see 'END UNINSTALL SCRIPT'
	/*
	ob_start();
	
		print "Removing module 'text'...<br>";

		$programObject = new programObject;

		$sql = new sqlInterface;
		$module = new Module($_GET['mid']);

		print "Deleting the settings for the module...<br>";
		$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."modules_config WHERE `module_id` = ".$module->settings['id']);

		print "Deleting the module from the module list...<br>";

		$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."modules WHERE `id` = ".$module->settings['id']." AND `name` = '".$module->settings['name']."'");

		print "Deleting the page entries for the module...<br>";
		$sql->query("DROP TABLE ".$sql->settings['DB_PREFIX']."module_text");

		print "Deleting the pages...<br>";
		$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."pages WHERE `module` = ".$module->settings['id']);

		print "The module 'text' has been uninstalled!";
	
	$text = ob_get_contents();
	ob_end_clean();
	*/
	// END UNINSTALL SCRIPT
	
	$text  = "<h2><font color=red>!! WARNING - UNINSTALL DISABLED !!</font></h2>";
	$text .= "<p>The uninstallation for this module has been disabled as it is considered a core module. If you would like to uninstall this module, you will need to edit the 'modules/text/uninstall/uninstall.php' script on your installation and uncommment the uninstall code, and comment out this warning message.";
	$text .= "<p>If you uninstall this module, the script will remove <b>ALL</b> pages that are linked to the Text module. <b>There is no way to recover the information unless you restore the database.</b>";
	$text .= "<p>You have been warned";
	
	return $text;
}

?>
