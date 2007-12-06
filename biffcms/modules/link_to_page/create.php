<?php

function create($page)
{
	$sql = new sqlInterface;
	
	$sql->query("INSERT INTO ".$sql->settings['DB_PREFIX']."module_link_to_page (`page_id`, `name`, `url`) VALUES ('".$page->settings['id']."', 'Homepage', '".$page->settings['BA_PROGRAM_URL']."')");
}

?>
