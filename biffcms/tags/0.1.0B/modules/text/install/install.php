<?php

function install()
{
	print "Installing module 'text'...<br>";

	$sql = new sqlInterface;

	print "Creating the tables for the module...<br>";
	$sql->query("CREATE TABLE `".$sql->settings['DB_PREFIX']."module_text` (".
		"`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,".
		"`page_id` INT NOT NULL ,".
		"`text` TEXT NOT NULL".
		") TYPE = MYISAM ;");

	print "Registering the module with the system...<br>";
	$sql->query("INSERT INTO ".$sql->settings['DB_PREFIX']."modules (`name`, `description`, `active`) VALUES ('text', 'Allows unfiltered text input', 1)");

	$module = new Module("text");

	print "Saving the default settings into the system...<br>";
	$sql->query("INSERT INTO ".$sql->settings['DB_PREFIX']."modules_config (`module_id`, `var`, `data`) VALUES (".$module->settings['id'].", 'editor', 'basic')");

	print "The module ".MODULE_NAME." has been installed!";
}

?>
