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
class MerkzettelTableUsermerkzettel extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__merkzettel_userdownloads', 'id', $db);
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

	function loadAllByUser($userId)
	{

		$db = $this->getDBO();
		$query = $db->getQuery(true);

		$query
			->select(array('a.*', 'b.*'))
			->from($db->quoteName('#__merkzettel_userdownloads', 'a'))
			->join('INNER', $db->quoteName('#__merkzettel_downloads', 'b') . ' ON (' . $db->quoteName('a.download_id') . ' = ' . $db->quoteName('b.id') . ')')
			->where($db->quoteName('user_id') . ' = '. $userId)
			->order($db->quoteName('a.download_id') . ' DESC');

		$db->setQuery($query);

		$results = $db->loadObjectList();


		if ($db->getErrorNum())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Check that we have a result.

		if (empty($results))
		{
			return false;
		}

		//Return the array

		return $results;


	}


	function downloadDelete($downloadId, $userId)
	{

		$db = $this->getDBO();
		$query = $db->getQuery(true);

		$query
			->delete($db->quoteName('#__merkzettel_userdownloads'))
			->where($db->quoteName('user_id') . ' = '. $userId.' AND '.$db->quoteName('download_id') . ' = '.$downloadId);
		$db->setQuery($query);

		$result = $db->execute();

	}
}