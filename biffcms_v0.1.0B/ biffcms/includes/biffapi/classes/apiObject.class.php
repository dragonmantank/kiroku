<?php

class apiObject
{
	var $settings;
	
	function returnAPISettings()
	{
		$apiSettings['BA_PROGRAM_NAME'] 		= BA_PROGRAM_NAME;
		$apiSettings['BA_ADMIN_EMAIL']			= BA_ADMIN_EMAIL;
		$apiSettings['BA_PROGRAM_DIR']			= BA_PROGRAM_DIR;
		$apiSettings['BA_PROGRAM_URL']			= BA_PROGRAM_URL;
		$apiSettings['BA_TEMPLATE_DIR']			= BA_TEMPLATE_DIR;
		$apiSettings['BA_THEME_DIR']			= BA_THEME_DIR;
		$apiSettings['BA_THEME_DEFAULT_DIR']	= BA_THEME_DEFAULT_DIR;
		
		$apiSettings['BA_API_DIR']				= BA_API_DIR;
		$apiSettings['BA_API_URL']				= BA_API_URL;
		$apiSettings['BA_API_STYLESHEET']		= BA_API_STYLESHEET;
		$apiSettings['BA_API_STYLESHEET_URL']	= BA_API_STYLESHEET_URL;
		$apiSettings['BA_API_TEMPLATE_DIR']		= BA_API_TEMPLATE_DIR;
		$apiSettings['BA_API_IMAGES_URL']		= BA_API_IMAGES_URL;
		
		$apiSettings['SITE_VISIBLE']	= SITE_VISIBLE;
		$apiSettings['DEBUG']			= DEBUG;
		
		return $apiSettings;
	}
}

?>
