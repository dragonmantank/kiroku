<?php

class apiError extends apiObject
{
	function apiError($errorTitle, $errorMessage, $callerSettings = array())
	{
		$this->settings = apiObject::returnAPISettings();

		$this->settings['errorTitle']		= $errorTitle;
		$this->settings['errorMessage']		= $errorMessage;
		$this->settings['callerSettings']	= $callerSettings;

		$this->displayError();
	}

	function displayError()
	{
		// Add the caller's settings into the error message if we are in DEBUG mode
		if($this->settings['DEBUG'])
		{
			$this->settings['errorMessage'] .= "<p><b><u>Additional Information:</u></b><br>";
			$this->settings['errorMessage'] .= "<ul>";
			foreach($this->settings['callerSettings'] as $key => $data)
			{
				$this->settings['errorMessage'] .= "<li><b>$key:</b> $data</li>";
			}
			$this->settings['errorMessage'] .= "</ul>";
		}

		// Check to see if the template exists to display the nice error box

		if(file_exists($this->settings['BA_API_TEMPLATE_DIR']."systemError/error.tpl"))
		{
			$template = new apiTemplate('error.tpl', $this->settings['BA_API_TEMPLATE_DIR']."systemError/");
			$template->replaceTags(array(
				"BA_PROGRAM_NAME"	=> $this->settings['BA_PROGRAM_NAME'],
				"BA_API_STYLESHEET"	=> $this->settings['BA_API_STYLESHEET'],
				"BA_API_IMAGES_URL"	=> $this->settings['BA_API_IMAGES_URL'],
				"Error_Title"		=> $this->settings['errorTitle'],
				"Error_Message"		=> $this->settings['errorMessage'],
			));
			$html = $template->text;
		}
		else // No template, display the crappy error message
		{

			$html  = "<table width=80% border=1 align=center>";
			$html .= " <tr>";
			$html .= "  <td align=center><b>".$this->settings['errorTitle']."</b></td>";
			$html .= " </tr>";
			$html .= " <tr>";
			$html .= "  <td>".$this->settings['errorMessage']."</td>";
			$html .= " </tr>";
			$html .= "</table>";
		}
		
		print $html;
		die();
	}
}

?>
