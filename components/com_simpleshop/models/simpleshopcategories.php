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

class SimpleshopModelSimpleshopcategories extends JModelItem

{

	/**
	 * @var array messages
	 */

	//protected $messages;

	/**
	 * @var object item
	 */

	protected $item;

	public function getCurrentUser(){
		$user = JFactory::getUser();
		return $user->id;

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


	// Get all products by category

	public function getAllProductsByCategory()

	{
		$jinput = JFactory::getApplication()->input;
		$catID     = $jinput->get('category','' , 'INT');


		// Get a TableHelloWorld instance
		$table = $this->getTable();
		return $table->loadAllByCategory($catID);

	}


	public function getParameters(){

		$params = $this->getState('params');
		return $params;
	}

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

}