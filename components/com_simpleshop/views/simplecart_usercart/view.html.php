<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_merkzettel
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the HelloWorld Component
 *
 * @since  0.0.1
 */
class MerkzettelViewUsermerkzettel extends JViewLegacy
{
	/**
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{

		// Get all download function
		$this->allDownloads = $this->get('AllUserDownloads');

		// Get Joomla Parameters -> Menu
		$this->params = $this->get('Parameters');

		// Get Item for Test Params
		$this->item = $this->get('Item');




		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');

			return false;
		}

		$this->addScriptAndStyle();

		// Display the view

		parent::display($tpl);
	}

	function addScriptAndStyle()
	{
		$document = JFactory::getDocument();

		// everything's dependent upon JQuery
		JHtml::_('jquery.framework');



		// ... and our own JS and CSS
		$document->addScript(JURI::root() . "media/com_merkzettel/js/usermerkzettel.js");
		$document->addStyleSheet(JURI::root() . "media/com_merkzettel/css/merkzettel.css");

		// get the data to pass to our JS code
		$jsParams = $this->get("BasePath");

		//Kint::dump($jsParams);
		$document->addScriptOptions('params', $jsParams);
	}
}