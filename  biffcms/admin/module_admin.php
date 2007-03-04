<?php
/******************************************************************************
 * Filename:		module_admin.php
 * Description:		Module admin section. Allows modules to be installed, deleted,
 * 					and modified.
 * Creation Date:	Unknown (before the 03/03/2007 modifications)
 * Original Author:	Chris Tankersley (dragonmantank@gmail.com)
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
 * 03/03/2007	CRT		Converted to use the new global vars for user groups
 *****************************************************************************/
 
session_start();
$sc = new securityCenter();
$sc->CheckCredentials(CMS_GROUP_MODULE_ADMIN, "admin.php", true);

function afMain()
{
	switch($_GET['f'])
	{
		case "i":
			$body = moduleInstall();
			break;
		case "ui":
			$body = moduleUninstall();
			break;
		case "config":
			$body = moduleConfig();
			break;
		case "status":
			changeStatus();
			break;
		default:
			$body = listModules();
			break;
	}

	return $body;
}

function listModules()
{
	$programObject = new programObject;
	$sql = new sqlInterface;

	$result = $sql->query("SELECT id FROM ".$sql->settings['DB_PREFIX']."modules");
	
	apiSelectRowColor(true);
	while($modID = $sql->fetch_row($result))
	{
		$module = new Module($modID[0]);

		$installedMods .= $module->adminListing();
	}

	apiSelectRowColor(true);
	$modulesDir = opendir($programObject->settings['modulesDir']);
	while($file = readdir($modulesDir))
	{
		if( is_dir($programObject->settings['modulesDir'].$file) )
		{
			if( ($file !== ".") && ($file !== ".."))
			{
				$result2 = $sql->query("SELECT id FROM ".$sql->settings['DB_PREFIX']."modules WHERE `name` = '".$file."'");
				if($sql->num_rows($result2) == 0)
				{
					$mod = new Template('admin/module_admin/adminListing2.tpl',$programObject->settings['themeTemplatePath']."");
					$mod->replaceTags(array(
						"name"	=> $file,
					));
					$uninstalledMods .= $mod->text;
				}
			}
		}
	}

	$template = new Template("admin/module_admin/listModules.tpl", $programObject->settings['themeTemplatePath']."");
	$template->replaceTags(array(
		"installedMods"		=> $installedMods,
		"uninstalledMods"	=> $uninstalledMods,
	));
	
	return $template->text;
}

function changeStatus()
{
	if( trim($_GET['mid']) !== "" )
	{
		if( is_numeric($_GET['mid']) )
		{
			$module = new Module($_GET['mid']);
			if( !$module->changeStatus() )
			{
				$e = new apiError("Module Error", "Module ".$module->settings['name']." encountered an error while changing the status.", $module->settings);
			}
		}
		else
		{
			$e = new apiError("Module Error", "A variable was passed attempting to be a module ID. The GET variable that was passed contained '".$_GET['mid']."'", $module->settings);
		}
	}

	$programObject = new programObject;
	header("Location: ".$programObject->settings['BA_PROGRAM_URL']."admin.php?af=ma");
}

function moduleConfig()
{
	if( trim($_GET['mid']) !== "" )
	{
		if( is_numeric($_GET['mid']) )
		{
			$module = new Module($_GET['mid']);
			return $module->configDisplay();
		}
		else
		{
			$e = new apiError("Module Error", "A variable was passed attempting to be a module ID. The GET variable that was passed contained '".$_GET['mid']."'", $module->settings);
		}
	}
}

function moduleUninstall()
{
	if( trim($_GET['mid']) !== "" )
	{
		if( is_numeric($_GET['mid']) )
		{
			$module = new Module($_GET['mid']);
			return $module->uninstall();
		}
		else
		{
			$e = new apiError("Module Error", "A variable was passed attempting to be a module ID. The GET variable that was passed contained '".$_GET['mid']."'", $module->settings);
		}
	}
}

function moduleInstall()
{
	if( trim($_GET['mname']) !== "" )
	{
		if( !is_numeric($_GET['mname']) )
		{
			$programObject = new programObject;
			include_once($programObject->settings['BA_PROGRAM_DIR']."modules/".$_GET['mname']."/install/install.php");
			return install();
		}
		else
		{
			$e = new apiError("Module Error", "A variable was passed attempting to be a module ID. The GET variable that was passed contained '".$_GET['mid']."'", $module->settings);
		}
	}
}

?>
