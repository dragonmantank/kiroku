<?php

/**
 * Random functions that did not belong to any specific class
 * 
 * This class is a wrapper for helper functions that would normally be
 * designed at the function level. They have been wrapped as static methods
 * to make them as easily portable and callable as functions but still keeping
 * with OO patterns. 
 * 
 * @author 		Chris Tankersley
 * @copyright 	2008 Chris Tankersley
 * @package 	Tws
 *
 */
class Tws_Functions
{
	/**
	 * Returns an array with all keys kept intact, even numeric ones.
	 * 
	 * The normal array_merge() will reset any numeric keys. This method
	 * overrides that behavior and keeps ALL keys that are passed unless they
	 * match, in which case the last called key will take precedence.
	 * 
	 * Code was originally found here:
	 * http://www.talkincode.com/php-array_merge-funcion-improvement-114.html
	 *
	 * @return array
	 */
	static public function array_merge()
	{
		$args 		= func_get_args();
		$results	= array();
		foreach($args as $array) {
			foreach($array as $key => $value) {
				$results[$key] = $value;
			}
		}
		
		return $results;
	}
}