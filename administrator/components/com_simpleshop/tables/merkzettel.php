<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Hello Table class
 *
 * @since  0.0.1
 */
class MerkzettelTableMerkzettel extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__merkzettel_downloads', 'id', $db);
	}

	/**
	 * Overloaded bind function
	 *
	 * @param       array           named array
	 * @return      null|string     null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}
		return parent::bind($array, $ignore);
	}

	function loadAll($key = null)
	{

		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$query->select($key);
		$query->from($this->getTableName());
		$db->setQuery($query);
		$row = $db->loadObjectList();

		if ($db->getErrorNum())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Check that we have a result.

		if (empty($row))
		{
			return false;
		}

		//Return the array
		return $row;


	}
}