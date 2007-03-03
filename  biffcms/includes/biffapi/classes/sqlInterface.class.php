<?php

// Globals
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_SERVER", "localhost");
define("DB_DATABASE", "biffcms");
define("DB_PREFIX", "");
define("DB_TYPE", "mysql");

class sqlInterface extends apiObject
{
	function sqlInterface($connect = true)
	{
		$this->settings = apiObject::returnAPISettings();

		$this->settings['DB_USERNAME']	= DB_USERNAME;
		$this->settings['DB_PASSWORD']	= DB_PASSWORD;
		$this->settings['DB_SERVER']	= DB_SERVER;
		$this->settings['DB_DATABASE']	= DB_DATABASE;
		$this->settings['DB_PREFIX']	= DB_PREFIX;
		$this->settings['DB_TYPE']	= strtolower(DB_TYPE);

		//register_shutdown_function(array(&$this, $this->settings['DB_TYPE'].'_closeConnection'));

		if($connect)
		{
			$this->openConnection();
		}
	}

	function mysql_closeConnection()
	{
		if(@mysql_close())
		{
			return true;
		}
		else
		{
			$this->GenerateErrorStats("Closing MySQL Connection");
			
			$e = new apiError("SQL Subsystem - Could Not Close Connection", "The SQL Subsystem was unable to close the connection to the server.", $this->settings);
		}
	}

	function GenerateErrorStats($lastQuery)
	{
		$this->settings['LAST_QUERY'] 		= (trim($lastQuery) == "" ? $this->settings['LAST_QUERY'] : $lastQuery);
		$this->settings['DB_PASSWORD'] 		= "******";
		//$this->settings['MySQL Error No.']	= mysql_errorno();
		$this->settings['MySQL Error'] 		= mysql_error();
		

		return true;
	}

	function openConnection()
	{
		switch($this->settings['DB_TYPE'])
		{
			case "mysql":
				$this->mysql_OpenConnection();
				break;
		}
	}
	
	function mysql_OpenConnection()
	{
		$return = true;
		
		if( $this->settings['connection'] = mysql_connect($this->settings['DB_SERVER'], $this->settings['DB_USERNAME'], $this->settings['DB_PASSWORD']) )
		{
			if( !@mysql_select_db($this->settings['DB_DATABASE'], $this->settings['connection']) )
			{
				$this->GenerateErrorStats("Selecting database");
				$error = new apiError("SQL Subsystem - Could not select Database", "There was a problem selecting the MySQL database", $this->settings);
				
				$return = false;
			}
		}
		else
		{
			$this->GenerateErrorStats("MySQL Database Connection");
			$error = new apiError("SQL Subsystem - Could not connect to Database", "There was a problem connecting to the MySQL database", $this->settings);

			$return = false;
		}
		
		return $return;
	}

	function query($query)
	{
		$this->settings['LAST_QUERY'] = $query;
		return $this->mysqlQuery($query);
	}

	function mysqlQuery($query)
	{
		$this->settings['LAST_QUERY'] = $query;
		$result = mysql_query($query);
		
		if($result)
		{
			return $result;
		}
		else
		{
			$this->GenerateErrorStats();
			$error = new apiError("SQL Subsystem", "Error with your query", $this->settings);
		}
	}

	function num_rows($result)
	{
		switch($this->settings['DB_TYPE'])
		{
			case "mysql":
				return mysql_num_rows($result);
				break;
		}
	}

	function fetch_assoc($result)
	{
		switch($this->settings['DB_TYPE'])
		{
			case "mysql":
				return mysql_fetch_assoc($result);
				break;
		}
	}

	function fetch_row($result)
	{
		switch($this->settings['DB_TYPE'])
		{
			case "mysql":
				return mysql_fetch_row($result);
				break;
		}
	}

	function select($table, $fields = "*", $conditions = "", $limit = "", $order = "")
	{
		// Add commas to the fields if they are passed as an array
		if( is_array($fields) )
		{
			$fields = implode(",", $fields);
		}
		
		// Begin to build the SQL statement
		$sql = "SELECT $fields FROM ".$this->settings['DB_PREFIX']."$table";
		
		// Add conditions if there are any
		if( !(empty($conditions)) )
		{
			$sql .= " WHERE $conditions";
		}
		
		// Add the order if there is one
		if( !(empty($order)) )
		{
			$sql .= " ORDER BY $order";
		}
		
		// Add the return Limit if there is one
		if( !(empty($limit)) )
		{
			$sql .= " LIMIT $limit";
		}
		
		// Run the completed SQL query and return the result, or error out
		$this->settings['LAST_QUERY'] = $sql;
		if( $result = mysql_query($sql) )
		{
			return $result;
		}
		else
		{
			$this->GenerateErrorStats();
			$error = new apiError("SQL Abstraction Layer - Select Error", "There was a problem selecting information from the database", $this->settings);		
		}
	}

	function update($table, $set, $conditions, $limit = "")
	{
		// Add commas to the set if $set is an array
		if( is_array($set) )
		{
			$set = implode(",", $set);
		}
		
		// Begin to build the SQL statement
		$sql = "UPDATE ".$this->settings['DB_PREFIX']."$table SET $set WHERE $conditions";
		
		// Add the update limit if there is one
		if( !(empty($limit)) )
		{
			$sql .= " LIMIT $limit";
		}
		
		// Run the query and return true, or error out
		$this->settings['LAST_QUERY'] = $sql;
		if( !(mysql_query($sql)) )
		{
			$error = new apiError("MySQL Database Error", "There was a problem updating information in the database", $this->settings);
			die();
		}
		else
		{
			return true;
		}
	}

	function insert_id()
	{
		return mysql_insert_id();
	}
}

?>
