<?php

class securityCenter extends apiObject
{
	function securityCenter()
	{
		$this->settings = apiObject::returnAPISettings();
	}
	// ===================================================================
	// Function: 	CheckCredentials
	// Description:	Checks to see if the user is allowed to view a webpage
	// ===================================================================
	function CheckCredentials($allowed_groups = array(), $ref_page = "", $redirect = false)
	{
		// Check and see if we need to restrict it based on group ID
		if($allowed_groups !== "Any")
		{
			// Convert any string-based group names to their IDs
			$allowed_groups = $this->CheckAllowedGroups($allowed_groups);
		}
		
		// Search an array of groups to see if it matches
		if(is_array($allowed_groups))
		{
			// Do the actual search and pass or fail (Super Users will pass)
			if( (array_search($_SESSION['user_group'], $allowed_groups)) || ($_SESSION['user_group'] == $this->FindGroupID("Super Users")) )
			{
				$bool = true;
			}
			else
			{
				$bool = false;
			}
		}
		else
		{
			// Only one group was passed, make sure it's not the Any group
			if($allowed_groups !== "Any")
			{
				// See if they match the group or are a Super User
				if( ($_SESSION['user_group'] == $allowed_groups) || ($_SESSION['user_group'] == $this->FindGroupID("Super Users")) )
				{
					$bool = true;
				}
				else
				{
					$bool = false;
				}
			}
			else
			{
				// Anyone can view the page
				$bool = true;
			}
		}
		
		// See if failure results in being redirected to the login page
		if($redirect && ($bool == false))
		{
			$this->RedirectToLogin($ref_page);
		}
		else
		{
			// Let the caller know if we passed or failed
			return $bool;
		}
	}
	
	// ===================================================================
	// Function: 	CheckAllowedGroups
	// Description:	Converts string group names to their ID numbers
	// ===================================================================
	function CheckAllowedGroups($allowed_groups)
	{
		// Did we pass an array?
		if(is_array($allowed_groups))
		{
			//$fixed_groups = array();
			
			// Go through each of the groups passed
			foreach($allowed_groups as $group)
			{
				// Check and see if it's a string
				if(is_string($group))
				{
					// Find the ID of the group
					$fixed_groups[] = $this->FindGroupID($group);
				}
				else
				{
					// Nope, it's a number, dump it back in
					$fixed_groups[] = $group;
				}
			}
		}
		else
		{
			// Only passed on, see if its a string
			if(is_string($allowed_groups))
			{
				// It is, find the ID
				$fixed_groups = $this->FindGroupID($allowed_groups);
			}
		}
		
		// Send 'm back!
		return $fixed_groups;
	}
	
	// ===================================================================
	// Function: 	FindGroupID
	// Description:	Searches the DB for a group name and returns the ID
	// ===================================================================
	function FindGroupID($group_name)
	{
		// Search for the ID in the DB
		$sql = new sqlInterface;
		$result = $sql->select("user_groups", "id", "name = '$group_name'");
		
		if($sql->num_rows($result) == 1)
		{
			// Found only one, like we should have
			$id = $sql->fetch_row($result);
			return $id[0];
		}
		elseif($sql->num_rows($result) == 0)
		{
			// Didn't find any, so something screwed up. Lost group, mispelled group, etc
			$error = new apiError("Security Center Error", "Could not find an ID for a group named '$group_name'", $this->settings);
		}
		else
		{
			// Somehow we found more than one or less than 0, so something is really screwed up
			$error = new apiError("Security Center Error", "Somehow there were ".$sql->num_rows($result)." results returned while searching for an ID for '$group_name'", $this->settings);
		}
	}
	
	// ===================================================================
	// Function: 	RedirectToLogin
	// Description: Send the person to the login page
	// ===================================================================
	function RedirectToLogin($ref_page)
	{
		unset($_SESSION['user_id']);
		unset($_SESSION['user_group']);
		
		//header("Location: ".$this->settings['BA_PROGRAM_URL']."login.php?ref=$ref_page");
		header("Location: login.php?ref=$ref_page");
	}
	
	// ===================================================================
	// Function: 	ProcessLogin
	// Description:	Validate a login and, if good, set the session variables
	// ===================================================================
	function ProcessLogin($username, $password)
	{
		// Hash the login
		$md5ed_password = md5($password);
	
		// Search the DB for the username, hashed password, and active users
		$sql = new sqlInterface;
		$result = $sql->select("user_accounts", array("id", "user_group"), "username = '$username' AND password = '$md5ed_password' AND active = 1");	
		
		// Found only one, good!
		if($sql->num_rows($result) == 1)
		{
			// Get the data and set it into the session
			$data = $sql->fetch_row($result);
			
			$_SESSION['user_id']	= $data[0];
			$_SESSION['user_group']	= $data[1];
			
			// Send it to the ref page if passed, or to the homepag
			if(trim($_GET['ref']) != "")
			{
				//header("Location: ".$this->settings['BA_PROGRAM_URL'].$_GET['ref']);
				header("Location: ".$_GET['ref']);
			}
			else
			{
				//header("Location: ".$this->settings['BA_PROGRAM_URL']);
				header("Location: index.php");
			}
		}
		elseif($sql->num_rows($result) == 0)
		{
			// Found no matches, so it's an invalid username and pass
			return "The username or password entered was not valid.";
		}
		else
		{
			// We found more than one or less than 0 users, so something is screwed up.
			$error = new apiError("Security Center Error - Process Login", "Somehow there were ".$sql->num_rows($result)." results returned while searching for a match for the username of '$username'", $this->settings);
		}
	}
}

?>
