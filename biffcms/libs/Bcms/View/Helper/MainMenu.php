<?php

class Bcms_View_Helper_MainMenu
{
	public function mainMenu()
	{
		$db		= Zend_Registry::get('db');
		$result	= $db->fetchAll('SELECT `name`, `link_name` FROM `cms_pages` WHERE `active`=1 ORDER BY `homepage` DESC, `link_name` ASC');
		$html	= '';
		
		foreach($result as $row) {
			$html	.= '<li><a href="/page/' . strtolower($row['name']) . '">' . $row['link_name'] . '</a></li>';
		}
		
		return $html;
	}
}
