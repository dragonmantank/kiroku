<?php

function create($page)
{
	$sql = new sqlInterface;

	$sql->query("INSERT INTO ".$sql->settings['DB_PREFIX']."module_text (`page_id`) VALUES (".$page->settings['id'].")");
}

?>
