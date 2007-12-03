<?php

function install()
{
	print "Installing module 'link_to_page'...<br>";

	$sql = new sqlInterface;

	print "Creating the tables for the module...<br>";
	$sql->query("CREATE TABLE `".$sql->settings['DB_PREFIX']."module_link_to_page` (".
		"`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,".
		"`page_id` INT NOT NULL ,".
		"`name` VARCHAR( 50 ) NOT NULL ,".
		"`url` VARCHAR( 255 ) NOT NULL".
		") TYPE = MYISAM ;");

	print "Registering the module with the system...<br>";
	$sql->query("INSERT INTO ".$sql->settings['DB_PREFIX']."modules (`name`, `description`, `active`) VALUES ('link_to_page', 'Sends the user to another page', 1)");

	$module = new Module("link_to_page");

	print "Saving the default settings into the system...<br>";
	$sql->query("INSERT INTO ".$sql->settings['DB_PREFIX']."modules_config (`module_id`, `var`, `data`) VALUES (".$module->settings['id'].", 'show_redirect_message', 'yes')");
	$sql->query("INSERT INTO ".$sql->settings['DB_PREFIX']."modules_config (`module_id`, `var`, `data`) VALUES (".$module->settings['id'].", 'redirect_message', 'You are visiting a site outside of {BA_PROGRAM_NAME}. Please wait while you are redirected.')");
	$sql->query("INSERT INTO ".$sql->settings['DB_PREFIX']."modules_config (`module_id`, `var`, `data`) VALUES (".$module->settings['id'].", 'redirect_timeout', '10')");

	print "The module ".MODULE_NAME." has been installed!";
}

?>
