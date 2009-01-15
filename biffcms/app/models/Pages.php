<?php

class Pages extends Zend_Db_Table_Abstract
{
	protected $_name	= 'cms_pages';

    public function fetchPages()
    {
        $select = $this->getDefaultAdapter()->select();
        $select->from(array('p' => $this->_name))
               ->joinLeft(array('p2' => $this->_name), 'p.parent_page = p2.id', array('parent_name' => 'name'))
               ->joinLeft(array('m' => 'cms_modules'), 'p.module = m.id', array('module_name' => 'name'))
               ->order('p.name ASC');

        return $select->query()->fetchAll();       
    }

	public function fetchPairs(array $pairs)
	{
		$db		= $this->getDefaultAdapter();
		$sql	= $db->quoteInto('SELECT ?, ? FROM `' . $this->_name . '` ORDER BY `name` ASC', array($pairs[0], $pairs[1])); 
		return $db->fetchAll($sql);
	}

    public function fetchSummaryList($limit = 5)
    {
        $select = $this->getDefaultAdapter()->select();
        $select->from($this->_name)
               ->limit('5')
               ->order('id DESC');

        return $select->query()->fetchAll();
    }
	
	public function insert(array $data)
	{
		parent::insert($data);
		$id = $this->getAdapter()->lastInsertId();
		Bcms_Module::createDefaultPage($data['module'], $id);
	}
}
