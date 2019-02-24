<?php
/**
 * View file for responding to Ajax request for performing Search Here on the map
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.view');


class MerkzettelViewUsermerkzettel extends JViewLegacy
{
	/**
	 * This display function returns in json format the Helloworld greetings
	 *   found within the latitude and longitude boundaries of the map.
	 * These bounds are provided in the parameters
	 *   minlat, minlng, maxlat, maxlng
	 */

	function display($tpl = null)
	{

		//$this->allDownloads = $this->get('AllUserDownloads');
		$this->userCart = $this->get('UserCart');
		//$this->getZip = $this->get('CreateZip');

		$displayValues = [
			'produkte' => $this->userCart
		];

		echo new JResponseJson($displayValues);

	}

}