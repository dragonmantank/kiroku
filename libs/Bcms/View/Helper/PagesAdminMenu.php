<?php

/**
 * Generates a nested UL for the Page admin
 *
 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
 * @copyright 2009 Chris Tankersley
 * @package Bcms_View_Helper
 */
class Bcms_View_Helper_PagesAdminMenu
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
		$select	= $db->select()->from('cms_pages', array('display', 'active', 'id', 'name'))->where('parent_page = ?', $pageId)->order('name ASC');
//		$select	= $db->select()->from('cms_pages', array('id', 'name', 'link_name', 'slug'))->where('active = 1')->where('display = 1')->where('parent_page = ?', $pageId)->order('link_name ASC');
		$result	= $db->fetchAll($select);
		
		if(count($result)) {
			$html	= '<ul>';
			foreach($result as $row) {
				$html	.= '<li><a class="' . (!$row['display'] ? 'hiddenPage' : '') . ' ' . (!$row['active'] ? 'inactivePage' : '') . '" href="/admin/pages/edit/page/' . $row['id'] . '" onClick="loadPage(' . $row['id'] . '); return false;">' . $row['name'] . '</a>';
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
	 * Generates the menu
	 *
	 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
	 * @param bool $reverse
	 * @returns string
	 */
	public function pagesAdminMenu( $reverse = false )
	{
		$order	= ($reverse ? 'DESC' : 'ASC');
		$db		= Zend_Registry::get('db');
		$select	= $db->select()->from('cms_pages', array('display', 'active', 'id', 'name'))->where('parent_page = 0')->order('name ASC');
		$result	= $db->fetchAll($select);
		$html	= '';
		
		foreach($result as $row) {
			$html	.= '<li><a class="' . (!$row['display'] ? 'hiddenPage' : '') . ' ' . (!$row['active'] ? 'inactivePage' : '') . '" href="/admin/pages/edit/page/' . $row['id'] . '" onClick="loadPage(' . $row['id'] . '); return false;">' . $row['name'] . '</a>';
			$html	.= $this->_addChildren($row['id']);
			$html 	.= '</li>' . "\r\n";
		}
		
		return $html;
	}
}
