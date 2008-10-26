<?php

/**
 * Controller for Cron subsystem
 * Searches for any active crons in the database and runs them.
 * 
 * @author Chris Tankersley
 * @copyright 2008 Chris Tankersley
 * @package Tws_Cron
 *
 */
class Tws_CronController
{
	/**
	 * Runs all active cron jobs
	 * Runs an SQL query to search for active cron jobs and then calls their
	 * run() function.
	 *
	 */
	static public function run()
	{
		$db		= Zend_Registry::get('db');
		$jobs	= $db->fetchAll('SELECT `shortName` FROM `Cron_Jobs` WHERE `active` = 1');

		foreach($jobs as $job) {
			$className	= 'Tws_Cron_' . $job['shortName'];
			$cron		= new $className;
			$cron->run();
		}
	}
}
