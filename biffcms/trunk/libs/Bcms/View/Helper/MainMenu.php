<?php

class Bcms_View_Helper_MainMenu
{
	protected function _addChildren($pageId)
	{
		$db		= Zend_Registry::get('db');
		$result	= $db->fetchAll('SELECT `id`, `name`, `link_name`, `slug` FROM `cms_pages` WHERE `active`=1 AND `display` = 1 AND `parent_page` = ' . $pageId . ' ORDER BY `link_name` ASC');
		
		if(count($result)) {
			$html	= '<ul>';
			foreach($result as $row) {
				$html	.= '<li><a href="/page/' . $row['slug'] . '">' . $row['link_name'] . '</a>';
				$html	.= $this->_addChildren($row['id']);
				$html 	.= '</li>' . "\r\n";
			}
			$html	.= '</ul>';
			
			return $html;
		} else {
			return '';
		}
	}
	
	public function mainMenu()
	{
		$db		= Zend_Registry::get('db');
		$result	= $db->fetchAll('SELECT `id`, `name`, `link_name`, `slug` FROM `cms_pages` WHERE `active`=1 AND `display` = 1 AND `parent_page` = 0 ORDER BY `homepage` DESC, `link_name` ASC');
		$html	= '';
		
		$classes		= array('first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth');
		$classCounter	= 0;
		foreach($result as $row) {
			$html	.= '<li class="' . $classes[$classCounter] . '"><a href="/page/' . $row['slug'] . '">' . $row['link_name'] . '</a>';
			$html	.= $this->_addChildren($row['id']);
			$html 	.= '</li>' . "\r\n";
			$classCounter++;
		}
		
		return $html;
	}
}
