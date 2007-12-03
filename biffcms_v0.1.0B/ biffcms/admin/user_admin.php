<?php
/******************************************************************************
 * Filename:		user_admin.php
 * Description:		Controls user and user group administration when using the 
 * 					BiffCMS authentication system. If another authentication
 * 					system is being used, this area will deactivate itself.
 * Creation Date:	03/30/2007
 * Original Author:	Chris Tankersley (dragonmantank@gmail.com)
 * 
 * Custom Modifications:
 * =====================
 *
 * MM/DD/YYYY	PRGMR	DESCRIPTION OF CHANGES
 * ----------	-----	----------------------
 *
 * BSB Modifications:
 * =====================
 * CRT = Chris Tankersley
 *
 * MM/DD/YYYY	PRGMR	DESCRIPTION OF CHANGES
 * ----------	-----	----------------------
 * 03/30/2007	CRT		Initial Creation
 *****************************************************************************/
 
$sc = new securityCenter();
$sc->CheckCredentials(CMS_GROUP_USER_ADMIN, "admin.php", true);

function afMain()
{
	$po = new programObject;
	$template = new Template('main.tpl', $po->settings['themeTemplatePath'].'admin/user_admin/');
	
	return $template->text; 
}
 
?>
