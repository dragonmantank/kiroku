<?php

/**
 * Generates the main menu for the site
 *
 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
 * @copyright 2009 Chris Tankersley
 * @package Bcms_View_Helper
 */
class Bcms_View_Helper_MainMenu
{
	/**
	 * Adds children pages to menu
	 *
	 * Searchs through the DB and pulls up all the children pages for the 
	 * current page. It recursively searchs for all further children
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 * @param int $pageId
	 * @returns string
	 */
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
	
	/**
	 * Generates the main menu
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 * @param bool $reverse
	 * @returns string
	 */
	public function mainMenu( $reverse = false )
	{
		$order	= ($reverse ? 'DESC' : 'ASC');
		$db		= Zend_Registry::get('db');
		$result	= $db->fetchAll('SELECT `id`, `name`, `link_name`, `slug` FROM `cms_pages` WHERE `active`=1 AND `display` = 1 AND `parent_page` = 0 ORDER BY `homepage` DESC, `link_name` ' . $order);
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
