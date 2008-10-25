<?php

function apiBuildOptions($options = array(), $autoselect = "")
{
	foreach($options as $key => $option)
	{
		$html .= "<option value=\"$key\"";
		if($autoselect == $key)
		{
			$html .= " SELECTED";
		}
		$html .= ">$option</option>";
	}
	
	return $html;
}

function apiSelectRowColor($reset = false)
{
	if($reset)
	{
		$class = "oddRow";
	}

	static $class = "oddRow";

	if($class == "evenRow")
	{
		$class = "oddRow";
	}
	else
	{
		$class = "evenRow";
	}
		
	return $class;
}

?>
