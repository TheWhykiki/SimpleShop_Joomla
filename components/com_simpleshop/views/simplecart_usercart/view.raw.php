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
		$this->allDownloads = $this->get('AllUserDownloads');
		$this->getZip = $this->get('CreateZip');

		$displayValues = [
			'filepath' => $this->getZip,
			'downloads' => $this->allDownloads
		];

	}

}