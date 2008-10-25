<?php

function uninstall()
{
	print "Removing module 'link_to_page'...<br>";

	$programObject = new programObject;

	$sql = new sqlInterface;
	$module = new Module($_GET['mid']);

	print "Deleting the settings for the module...<br>";
	$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."modules_config WHERE `module_id` = ".$module->settings['id']);

	print "Deleting the module from the module list...<br>";

	$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."modules WHERE `id` = ".$module->settings['id']." AND `name` = '".$module->settings['name']."'");

	print "Deleting the page entries for the module...<br>";
	$sql->query("DROP TABLE ".$sql->settings['DB_PREFIX']."module_link_to_age");

	print "Deleting the pages...<br>";
	$sql->query("DELETE FROM ".$sql->settings['DB_PREFIX']."pages WHERE `module` = ".$module->settings['id']);

	print "The module 'link_to_page' has been uninstalled!";
}

?>
