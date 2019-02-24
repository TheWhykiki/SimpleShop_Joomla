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
class SimpleshopModelProduct extends JModelItem
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
		$id     = $jinput->get('id', '', 'INT');
		$this->setState('product.id', $id);

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
		// add table path for when model gets used from other component
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_simpleshop/tables');
		// get instance of the table
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.6
	 *
	 *
	 */
	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('product.id');
		$table = $this->getTable();
		$jinput = JFactory::getApplication()->input;
		$id     = $jinput->get('id', '', 'INT');

		$item = $table->loadSingle($id);

		return $item;
	}

	public function getMsg()
	{
		if (!isset($this->message))
		{
			$this->message = 'Hello World!';
		}

		return $this->message;
	}
}