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
		$select	= $db->select()->from('cms_pages', array('id', 'name', 'link_name', 'slug'))->where('active = 1')->where('display = 1')->where('parent_page = ?', $pageId)->order('link_name ASC');
		$result	= $db->fetchAll($select);
		
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
		$select	= $db->select()->from('cms_pages', array('id', 'name', 'link_name', 'slug'))->where('active = 1')->where('display = 1')->where('parent_page = 0')->order('homepage DESC')->order('link_name ' . $order);
		$result	= $db->fetchAll($select);
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
