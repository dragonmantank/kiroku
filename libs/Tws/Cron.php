<?php

/**
 * Abstract class for a cron job
 * 
 * @author Chris Tankersley
 * @copyright 2008 Chris Tankersley
 * @package Tws_Cron
 *
 */
abstract class Tws_Cron
{
	/**
	 * Config file for the cron
	 *
	 * @var Zend_Config
	 */
	protected $_config;
	
	/**
	 * Base database connection
	 *
	 * @var Zend_Db
	 */
	protected $_db;
	
	/**
	 * Interval at which the cron should be run
	 *
	 * @var string|int
	 */
	protected $_interval;
	
	/**
	 * Full name of the cron
	 *
	 * @var string
	 */
	protected $_name			= 'UNDEFINED';
	
	/**
	 * Earliest point in the day the cron can run
	 *
	 * @var string
	 */
	protected $_scheduleStart;
	
	/**
	 * Latest point in the day the cron can run
	 *
	 * @var string
	 */
	protected $_scheduleStop;
	
	/**
	 * Base name for the cron
	 *
	 * @var string
	 */
	protected $_shortName;
	
	/**
	 * Sets up config and database connection
	 *
	 * @param Zend_Config $config
	 */
	public function __construct($config = null)
	{
		$this->_db		= Zend_Registry::get('db');
		
		if( $config == null ) {
			$this->_config	= new Zend_Config_Ini('config/crons/' . $this->_shortName . '.ini', $_SERVER['ENVIRONMENT']);
		} else {
			$this->_config = $config; 
		}
	}
	
	/**
	 * Sets the cron's status to active in the database
	 *
	 * @return bool
	 */
	public function activate()
	{
		return $this->_changeStatus(1);
	}
	
	/**
	 * Installs the cron into the database and activates it
	 *
	 * @return bool
	 */
	public function install()
	{
		try {
			$this->_alreadyInstalled();
		} catch (Exception $e) {
			return true;
		}
		
		$data = array(
			'name'			=> $this->_name,
			'shortName'		=> $this->_shortName,
			'scheduleStart'	=> $this->_scheduleStart,
			'scheduleStop'	=> $this->_scheduleStop,
			'interval'		=> $this->_interval,
			'active'		=> 1,
		);
		
		$this->_db->insert('Cron_Jobs', $data);
	}
	
	/**
	 * Checks to see if the cron is allowed to run
	 * Compares the Start and Stop times to the current time to see if the cron
	 * is within its time limits.
	 *
	 * @return bool
	 */
	protected function _canRun()
	{
		$start	= strtotime($this->_scheduleStart);
		$stop	= strtotime($this->_scheduleStop);
		$now	= time();
		
		if($now > $start && $now < $stop) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Change the status of a cron job
	 * Runs an SQL query to change the status of the cron based upon $newStatus
	 *
	 * @param int $newStatus
	 * @return bool
	 */
	protected function _changeStatus($newStatus)
	{
		if( $this->_db->update('Cron_Jobs', array('active' => $newStatus), '`id` = ' . $this->_getId()) ) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Checks to see if a cron is already installed
	 * Checks to see if a cron is already installed. It will throw an exception
	 * if the cron is already listed as installed in the database.
	 *
	 */
	protected function _alreadyInstalled()
	{
		$select		= $this->_db->select()->from('Cron_Jobs', array('id'))->where('name = ?', $this->_name);
		$results	= $this->_db->fetchAll($select);
		
		if(count($results)) {
			throw new Exception('Cron job ' . $this->_name . 'is already installed');
		}
	}
	
	/**
	 * Deactivates a cron job
	 *
	 * @return bool
	 */
	public function deactivate()
	{
		return $this->_changeStatus(0);
	}
	
	/**
	 * Returns the database ID of the cron job
	 *
	 * @return int
	 */
	protected function _getId()
	{
		$select		= $this->_db->select()->from('Cron_Jobs', array('id'))->where('name = ?', $this->_name);
		list($id)	= $this->_db->fetchCol($select);
		
		return $id;
	}
	
	/**
	 * Returns the current status of the cron
	 *
	 * @return int
	 */
	public function getStatus()
	{
		$select	= $this->_db->select()->from('Cron_Jobs', array('active'))->where('id = ?', $this->_getId());
		$status	= $this->_db->fetchRow($select);
		
		return $status['active'];
	}
	
	
	/**
	 * Main process for the cron
	 * Contains the main code for the cron job.
	 *
	 */
	abstract public function run();
	
	/**
	 * Anything that needs set up before the cron runs
	 *
	 */
	private function setup()
	{ }
	
	/**
	 * Removes the cron from the database
	 *
	 * @return bool
	 */
	public function uninstall()
	{
		return $this->_db->delete('Cron_Jobs', '`id` = ' . $this->_getId());
	}
}
