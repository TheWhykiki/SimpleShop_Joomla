<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//require JPATH_ROOT.'/kint.phar';
/**
 * Merkzettel Model
 *
 * @since  0.0.1
 */
class SimpleshopModelProducts extends JModelItem
{
	/**
	 * @var array messages
	 */
	//protected $messages;

	/**
	 * @var object item
	 */
	protected $item;

	protected function populateState()
	{

		// Get values and params from menu

		$jinput = JFactory::getApplication()->input;
		$id     = $jinput->get('Itemid', '', 'text');
		$this->setState('pimmel.id', $id);

		//Kint::dump($id);
		// Load the parameters.
		$this->setState('params', JFactory::getApplication()->getParams());
		parent::populateState();
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Product', $prefix = 'SimpleshopTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getCurrentUser(){
		$user = JFactory::getUser();
		return $user->id;
	}


	// Get all Products from the system

	public function getAllProducts()
	{
		// Get a TableHelloWorld instance
		$table = $this->getTable();

		return $table->loadAll('*');
	}

	// Get current user cart

	/*
	public function getUserCart()
	{
		$user = JFactory::getUser();
		$userId = $user->id;

		$this->userCart = array();

		if (!$this->userDownloads){
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__simpleshop_usercart')
				->where('user_id=' . (int)$userId);
			$db->setQuery($query);
			$produktResults = $db->loadObjectList();

			foreach ($produktResults as $produkt){
				$this->userCart[] = [
					'id'  => $produkt->id,
					'user_id'  => $produkt->user_id,
					'produkt_id'  => $produkt->produkt_id
				];

			}
			//Kint::dump($this->userDownloads);
			//$this->userCart = $this->unique_multidim_array($this->userCart,'download_id');
			//Kint::dump($this->userDownloads);
			return $this->userCart;
		}

		else
		{
			//Kint::dump($this->item);
			throw new Exception('Keine Downloads geladen', 500);
		}
	}

	*/


	// Function to clean the userDownlaods Array
	// clean all duplicates -> normally no duplicates should appear
	// just for safety reason

	function unique_multidim_array($array, $key) {
		$temp_array = array();
		$i = 0;
		$key_array = array();

		foreach($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$i] = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}
		return $temp_array;
	}

	public function getParameters(){
		$params = $this->getState('params');
		return $params;
	}
}