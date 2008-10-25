<?php

function DisplayModule()
{
	$programObject = new programObject;

	$page = $_GET["pid"];
	if(empty($page))
		$page = $programObject->returnHomepageID();

	$sql = new sqlInterface();
	$result = $sql->select("module_text", "text", "page_id = $page");
	$text = $sql->fetch_row($result);

	$text = stripslashes($text[0]);
	return $text;
}

?>
