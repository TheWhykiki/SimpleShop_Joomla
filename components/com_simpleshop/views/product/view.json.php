<?php
/**
 * View file for responding to Ajax request for performing Search Here on the map
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.view');


class SimpleshopViewProduct extends JViewLegacy
{
	/**
	 * This display function returns in json format the Helloworld greetings
	 *   found within the latitude and longitude boundaries of the map.
	 * These bounds are provided in the parameters
	 *   minlat, minlng, maxlat, maxlng
	 */

	function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;
		$mapbounds = $input->get('mapBounds', array(), 'ARRAY');

		// Get all download function
		$this->allProducts = $this->get('AllProducts');
		$this->userCart = $this->get('UserCart');

	}
}