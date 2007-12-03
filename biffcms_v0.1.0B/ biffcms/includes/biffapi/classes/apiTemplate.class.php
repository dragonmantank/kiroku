<?php

class apiTemplate extends apiObject
{
	var $text;

	// Constructor
	function apiTemplate($templateName, $templatePath = "")
	{
		// Set the basics settings for the object
		$this->settings = apiObject::returnAPISettings();
		$this->settings['templateName'] = $templateName;
		$this->settings['templatePath'] = (trim($templatePath) == "") ? $this->settings['BA_TEMPLATE_DIR'] : $templatePath;

		// Is the site visible?
		if($this->settings['SITE_VISIBLE']) // Site is visible
		{
			// Is the template actually there?
			if(file_exists($this->settings['templatePath'].$this->settings['templateName']))
			{
				$this->text = join('', file($this->settings['templatePath'].$this->settings['templateName']));
			}
			else // Template does not exist as passed
			{
				// Does the file exist in the default theme?
				if(file_exists($this->settings['BA_THEME_DEFAULT_DIR']."templates/".$templateName))
				{
					$this->text = join('', file($this->settings['BA_THEME_DEFAULT_DIR']."templates/".$this->settings['templateName']));
				}
				else // Not even here. Error and die.
				{
					if(file_exists($this->settings['BA_THEME_DEFAULT_DIR']."templates/admin/".$templateName))
					{
						$this->text = join('', file($this->settings['BA_THEME_DEFAULT_DIR']."templates/admin/".$this->settings['templateName']));
					}
					else
					{
						$error = new apiError("API Template Subsystem - Could Not Find Desired Template","The API Subsystem was unable to find the specified template. Please make sure that the file is readable by the web server, or that the file exists.",$this->settings);
					}
				}
			}
		}
		else // Site is not visible
		{
			// Is the site disabled template available?
			if(file_exists($this->settings['BA_API_TEMPLATE_DIR']."siteDisabled.tpl"))
			{
				$this->template = join('', file($this->settings['BA_API_TEMPLATE_DIR']."siteDisabled.tpl"));
				$this->replaceTags(array(
					"BA_PROGRAM_NAME"	=> $this->settings['BA_PROGRAM_NAME'],
					"BA_API_STYLESHEET"	=> $this->settings['BA_API_STYLESHEET'],
					"BA_ADMIN_EMAIL"	=> $this->settings['BA_ADMIN_EMAIL'],
				));
				$this->display();
			}
			else // No, it's not. Error out and die
			{
				$error = new apiError("API Template Subsystem - Could Not Find Site Disabled Template","The API Subsystem was unable to find the Site Disabled template. Please make sure that the file is readable by the web server, or that the file exists.",$this->settings);
			}
		}
	}

	// Replace the tags in the template
	function replaceTags($tags = array())
	{
		if(sizeof($tags) > 0)
		{
			foreach($tags as $tag => $data)
			{
				$data = (file_exists($data)) ? $this->parseFile($data) : $data;
				$this->text = eregi_replace('{'.$tag.'}',$data, $this->text);
			}
		}
	}

	// Parse an entire file into the template
	function parseFile($filename)
	{
		if( is_file($filename) )
		{
			ob_start();
			include($filename);
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		}
		else
		{
			return $filename;
		}
	}

	// Print the page to the display
	function display()
	{
		print $this->text;
	}
}

// Wrapper class
class Template extends apiTemplate
{}

?>
