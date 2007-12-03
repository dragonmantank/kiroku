<?php

/******************************************************************************
 * Filename:		programObject.class.php
 * Description:		Default class for all objects used in the CMS. This class
 					contains a lot of members that many objects use
 * Creation Date:	Unknown (Before 02/28/2007 update)
 * Original Author:	Chris Tankersley
 * 
 * Custom Modifications:
 * =====================
 *
 * MM/DD/YYYY	PRGMR	DESCRIPTION OF CHANGES
 * ----------	-----	----------------------
 *
 * BCMS Modifications:
 * =====================
 * CRT = Chris Tankersley
 * 
 * MM/DD/YYYY	PRGMR	DESCRIPTION OF CHANGES
 * ----------	-----	----------------------
 * 02/28/2007	CRT		Added 'Domain Admin' link to the admin sub menu 
 *****************************************************************************/

class programObject extends apiObject
{
	function programObject()
	{
		$this->settings = apiObject::returnAPISettings();

		$this->settings['site_id']				= $this->getSiteID();
		$this->settings['themesDir']			= $this->settings['BA_PROGRAM_DIR']."themes/";
		$this->settings['themePath'] 			= $this->settings['themesDir'].$this->getWebsiteInfo("current_theme")."/";
		$this->settings['themeTemplatePath']	= $this->settings['themePath']."templates/";
		$this->settings['themeURL']				= $this->settings['BA_PROGRAM_URL']."themes/".$this->getWebsiteInfo("current_theme")."/";
		$this->settings['themeTemplateURL']		= $this->settings['themeURL']."templates/";
		$this->settings['themeImages']			= $this->settings['themeURL']."images/";

		$this->settings['modulesDir']			= $this->settings['BA_PROGRAM_DIR']."modules/";
		$this->settings['modulesURL']			= $this->settings['BA_PROGRAM_URL']."modules/";
	}
	
	function returnProgramSettings()
	{
		return $this->settings;
	}

	function generateSubMenu($callerPageID)
	{
		// Doing a lot of SQL, making the object now
		$sql = new sqlInterface;

		// Create a page object for the page we are working with
		$callingPage = new Page($callerPageID);
		
		// Does this page have any child pages?
		if($callingPage->numChildPages() == 0)
		{
			// No. Select the main Navbar nodes 
			$childPages = $sql->query("SELECT id FROM ".$sql->settings['DB_PREFIX']."pages WHERE parent_page = 0 AND active = 1 ORDER BY homepage DESC, link_name ASC");
		}
		else
		{
			// Yes. Select those child pages
			$childPages = $sql->query("SELECT id FROM ".$sql->settings['DB_PREFIX']."pages WHERE parent_page = ".$callingPage->settings['id']." AND active = 1 AND id !=".$callingPage->settings['id']." ORDER BY link_name ASC");
		}

		// Dump the child information into the Submenu Link Template
		while($pageID = $sql->fetch_row($childPages))
		{
			$childPage = new Page($pageID[0]);
			$childTemplate = new Template('submenuLink.tpl', $this->settings['themeTemplatePath']);
			$childTemplate->replaceTags(array(
				"pid"		=> $childPage->settings['id'],
				"link_name"	=> $childPage->settings['link_name'],
			));

			// Save the template text into the main HTML dump
			$submenuHTML .= $childTemplate->text;
		}

		// Return the HTML
		return $submenuHTML;
	}

	function generateMainMenu()
	{
		// Doing a lot of SQL, making the object now
		$sql = new sqlInterface;
		
		$childPages = $sql->query("SELECT id FROM ".$sql->settings['DB_PREFIX']."pages WHERE parent_page = 0 AND active = 1 ORDER BY homepage DESC, link_name ASC");

		// Dump the child information into the Submenu Link Template
		while($pageID = $sql->fetch_row($childPages))
		{
			$childPage = new Page($pageID[0]);
			$childTemplate = new Template('mainmenuLink.tpl', $this->settings['themeTemplatePath']);
			$childTemplate->replaceTags(array(
				"pid"		=> $childPage->settings['id'],
				"link_name"	=> $childPage->settings['link_name'],
			));

			// Save the template text into the main HTML dump
			$submenuHTML .= $childTemplate->text;
		}

		// Return the HTML
		return $submenuHTML;
	}

	function replaceGlobalTags($templateObject)
	{
		$templateObject->replaceTags(array(
			"templateStyleSheet"	=> $this->settings['themePath']."style.css",
			"themeImages"		=> $this->settings['themeImages'],
			"site_name"		=> $this->getWebsiteInfo("site_name"),
			"site_tagline"		=> $this->getWebsiteInfo("site_tagline"),
			"themePath"		=> $this->settings['themePath'],
			"themeURL"		=> $this->settings['themeURL'],
			"BA_PROGRAM_URL"	=> $this->settings['BA_PROGRAM_URL'],
			"header-tagline"	=> "",
			"header-message"	=> $this->getWebsiteInfo("site_tagline"),
		));
		return $templateObject;
	}

	function GetWebsiteInfo($search)
	{
		$sql = new sqlInterface;
		$info = $sql->fetch_row( $sql->select("site_info", $search, "id = ".$this->settings['site_id']) );
	
		return stripslashes($info[0]);
	}

	function generateThemeList($select = "")
	{
		if($select == "")
		{
			$current_theme = $this->GetWebsiteInfo("current_theme");
		}
		else
		{
			$current_theme = $select;
		}
		$themes = array();
		$themes[$current_theme] = $current_theme;
		
		$themes_dir = opendir($this->settings['themesDir']);
		while($file = readdir($themes_dir))
		{
			if( is_dir($this->settings['themesDir'].$file) )
			{
				if( ($file !== ".") && ($file !== ".."))
				{
					$themes[$file] = $file;
				}
			}
		}

		$built_options = apiBuildOptions($themes, $current_theme);
		
		return $built_options;
	}

	function returnStatus()
	{
		return ($this->settings['active'] ? "Active" : "Inactive");
	}

	function returnHomepageID()
	{
		$sql = new sqlInterface;
		$id = $sql->fetch_row( $sql->query("SELECT id FROM ".$sql->settings['DB_PREFIX']."pages WHERE homepage = 1 AND `site_id` = ".$this->settings['site_id']) );

		return $id[0];
	}

	/*
	function generateMenu()
	{
		$sql = new sqlInterface;
		
		$result = $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."pages` WHERE `active` = 1 AND `parent_page` = 0 ORDER BY `link_name` ASC");

		$html = "<ul>";
		while($pageID1 = $sql->fetch_row($result))
		{
			$childCheck = $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."pages` WHERE `active` = 1 AND `parent_page` = ".$pageID1[0]." ORDER BY `link_name` ASC");

			if($sql->num_rows($childCheck) > 0)
			{
				$page = new Page( $pageID1[0] );
				$html .= '<li class="nav_has_child"><a href="index.php?pid='.$page->settings['id'].'">'.$page->settings['link_name'].'</a>'."\r\n";

				$html .= "<ul>\r\n";
				while($childID = $sql->fetch_row($childCheck))
				{
					$page = new Page( $childID[0] );
					$html .= '<li><a href="index.php?pid='.$page->settings['id'].'">'.$page->settings['link_name'].'</a></li>'."\r\n";
				}
				$html .= "</ul></li>\r\n";
			}
			else
			{
				$page = new Page( $pageID1[0] );
				$html .= '<li><a href="index.php?pid='.$page->settings['id'].'">'.$page->settings['link_name'].'</a></li>'."\r\n";
			}
		}

		return $html;
	}
	*/
	
	function generateMenu()
	{
		$sql = new sqlInterface;

		// Start the parentPage array
		$parentPages = array();
		
		// Grab the Homepage and set it as the first parentPage
		$homepageID = $sql->fetch_row( $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."pages` WHERE `homepage` = 1 AND `site_id` = ".$this->settings['site_id']) );
		$parentPages[] = $homepageID[0];
		
		// Grab the rest of the pages and set them into the array
		$parentResult = $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."pages` WHERE `parent_page` = 0 AND `active` = 1 AND `homepage` = 0  AND `site_id` = ".$this->settings['site_id']." ORDER BY `link_name` ASC");
		while($id = $sql->fetch_row($parentResult))
		{
			$parentPages[] = $id[0];
		}
		
		// Start building the main UL
		$html = "<ul>";
		
		// Print out all of the parent pages
		foreach($parentPages as $pageID)
		{
			// Check and see if they have children
			if( $this->generateMenu_hasChildren($pageID) )
			{
				// This page does have children, so make the parent page and display it
				$page = new Page( $pageID );
				//$html .= '<li><table border="0" cellpadding="0" cellspacing=0><tr><td width="100%"><a href="index.php?pid='.$page->settings['id'].'">'.stripslashes($page->settings['link_name']).'</a></td><td>&#9654;</td></tr></table>'."\r\n";
				$html .= '<li><a href="index.php?pid='.$page->settings['id'].'"><div style="display: inline; width: 100%; text-align: left">'.stripslashes($page->settings['link_name']).'</div><div style="display: inline; width: 100%; text-align: right;"> &#9654;</div></a>'."\r\n";
				
				// Print out the UL for the children
				$html .= "<ul>\r\n";
				
				// Build all the children
				$html .= $this->generateMenu_buildChildren($pageID);
				
				// Close out the UL for the children
				$html .= "</ul></li>\r\n";
			}
			else
			{
				// This page does not have children
				$page = new Page( $pageID );
				//$html .= '<li><table border="0" cellpadding="0" cellspacing=0><tr><td width="100%"><a href="index.php?pid='.$page->settings['id'].'">'.stripslashes($page->settings['link_name']).'</a></td><td>&nbsp</td></tr></table></li>'."\r\n";
				$html .= '<li><a href="index.php?pid='.$page->settings['id'].'">'.stripslashes($page->settings['link_name']).'</a></li>'."\r\n";
			}
		}
		
		// Finish the main UL
		$html .= "</ul>";
		
		// Return all of the HTML
		return $html;
	}
	
	function generateMenu_hasChildren($pageID)
	{
		$sql = new sqlInterface;
		$num = $sql->num_rows( $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."pages` WHERE `parent_page` = $pageID AND `active` = 1") );
		
		return ($num > 0 ? true : false);
	}
	
	function generateMenu_buildChildren($pageID)
	{
		$sql = new sqlInterface;
		$childrenResult = $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."pages` WHERE `parent_page` = $pageID AND `active` = 1");
		
		while($pageID =  $sql->fetch_row($childrenResult))
		{
			// Check and see if they have children
			if( $this->generateMenu_hasChildren($pageID[0]) )
			{
				// This page does have children, so make the parent page and display it
				$page = new Page( $pageID[0] );
				$html .= '<li><a href="index.php?pid='.$page->settings['id'].'" class="nav_has_child">'.stripslashes($page->settings['link_name']).' &#9654;</a>'."\r\n";
				
				// Print out the UL for the children
				$html .= "<ul>\r\n";
				
				// Build all the children
				$html .= $this->generateMenu_buildChildren($pageID[0]);
				
				// Close out the UL for the children
				$html .= "</ul></li>\r\n";
			}
			else
			{
				// This page does not have children
				$page = new Page( $pageID[0] );
				$html .= '<li><a href="index.php?pid='.$page->settings['id'].'">'.($page->settings['link_name']).'</a></li>'."\r\n";
			}
		}
		
		return $html;
	}
	

	// ========================================================================
	// Function:	generateAdminMenu
	// 
	// Purpose:		Creates the admin sub menu in the admin section
	// 
	// Parameters:	(None)
	// 
	// Returns:		str $html
	// 
	// Created:		Unknown (Before 02/28/2007 update)
	// 
	// Modifications:
	// ==============
	// CRT = Chris Tankersley
	// 
	// MM/DD/YYYY	PGRMR	DESCRIPTION OF CHANGES
	// ----------	-----	----------------------
	// 02/28/2007	CRT		Added 'Domain Admin' link
	// ========================================================================
	function generateAdminMenu()
	{
		$html = "<ul>";
		$html .= '<li><a href="{BA_PROGRAM_URL}">Site Home</a></li>';
		$html .= '<li><a href="{BA_PROGRAM_URL}admin.php">Admin Home</a></li>';
		$html .= '<li><a href="{BA_PROGRAM_URL}admin.php?af=da">Domain Admin</a></li>';
		$html .= '<li><a href="{BA_PROGRAM_URL}admin.php?af=sa">Site Admin</a></li>';
		$html .= '<li><a href="{BA_PROGRAM_URL}admin.php?af=pa">Page Admin</a></li>';
		$html .= '<li><a href="{BA_PROGRAM_URL}admin.php?af=ma">Module Admin</a></li>';
		$html .= '<li><a href="{BA_PROGRAM_URL}logout.php">Log Out</a></li>';
		$html .= "</ul>";
		
		return $html;
	}

	
	/*
	function generateAdminMenu()
	{
		$html .= '<a class="sidelink" href="index.php">Site Home</a><span class="hide"> | </span>';
		$html .= '<a class="sidelink" href="admin.php">Admin Home</a><span class="hide"> | </span>';
		$html .= '<a class="sidelink" href="admin.php?af=sa">Site Admin</a><span class="hide"> | </span>';
		$html .= '<a class="sidelink" href="admin.php?af=pa">Page Admin</a><span class="hide"> | </span>';
		$html .= '<a class="sidelink" href="admin.php?af=ma">Module Admin</a><span class="hide"> | </span>';
		$html .= '<a class="sidelink" href="admin.php?af=da">Domain Admin</a><span class="hide"> | </span>';
		$html .= '<a class="sidelink" href="logout.php">Log Out</a><span class="hide"> | </span>';
		
		return $html;
	}
	*/
	
	function getSiteID()
	{
		$sql = new sqlInterface;
		$result = $sql->query("SELECT `id`, `active` FROM `".$sql->settings['DB_PREFIX']."site_info` WHERE `url` = '".$_SERVER['HTTP_HOST']."'");
		
		if($sql->num_rows($result) > 0)
		{
			$data = $sql->fetch_row($result);
			
			if($data[1] == 0)
			{
				$result = $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."site_info` WHERE `default` = 1");
				$id_temp = $sql->fetch_row($result);
				$id = $id_temp[0];
			}
			else
			{
				$id = $data[0];
			}
		}
		else
		{
			$result = $sql->query("SELECT `id` FROM `".$sql->settings['DB_PREFIX']."site_info` WHERE `default` = 1");
			$id_temp = $sql->fetch_row($result);
			$id = $id_temp[0];
		}
		
		return $id;
	}
}

?>
