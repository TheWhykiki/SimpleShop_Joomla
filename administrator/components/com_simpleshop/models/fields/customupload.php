<?php

class JFormFieldCustomUpload extends JFormField
{
	/**
	* The field type.
	*
	* @var         string
	*/
	protected $type = 'CustomUpload';

	/**
	* Method to get a list of options for a list input.
	*
	* @return  array  An array of JHtml options.
	*/


	// Upload File

	public function getInput() {


		// Build the script
		$script = array();

		$script[] = '        jQuery( document ).ready(function() {';
		$script[] = '           jQuery(document).on("change", "#'.$this->id.'", function() {';
		$script[] = '               var file =  jQuery("#'.$this->id.'")[0].files[0];';
		$script[] = '               var filename =  file.name;';
		$script[] = '               console.log(file.type);';
		$script[] = '               filename =  filename.replace(/[`ßüäö~!@#$%^&*()|+\=?;:\'", <>\{\}\[\]\\\/]/gi,\'\');';

		$script[] = '               jQuery("#jform_download_url").val(filename);';
		$script[] = '           });';

		$script[] = '        });';


		// Add to document head
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

	return '<div class="filename_labe">'.$this->value.'</div><input name="'.$this->name.'" id="'.$this->id.'" accept="image/*" aria-invalid="false" type="file" value="'.$this->value.'">';
	// code that returns HTML that will be shown as the form field
	}

}

?>