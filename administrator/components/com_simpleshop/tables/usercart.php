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
class SimpleshopTableUsercart extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__simpleshop_usercart', 'id', $db);
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

	/******************************************************************************/
	// Get download cart user
	/*****************************************************************************/

	protected function _loadAllByProduct($userId)
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$query
			->select( array('produkt_id', 'COUNT(*) as counter') )
			->from($db->quoteName('#__simpleshop_usercart'))
			->where($db->quoteName('user_id') . ' = '. $userId)
			->group($db->quoteName('produkt_id'));
		$db->setQuery($query);
		$results = $db->loadAssocList();

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
	public function loadAllByUser($userId)
	{

		$products = $this->_loadAllByProduct($userId);

		$db = $this->getDBO();

		$counter = -1;
		if (is_bool($products)){
			return false;
		}
		else{
			foreach($products as $product){
				$counter++;
				$query = $db->getQuery(true);
				$query
					->select(array('a.*,b.*'))
					->from($db->quoteName('#__simpleshop_usercart', 'a'))
					->join('INNER', $db->quoteName('#__simpleshop', 'b') . ' ON (' . $db->quoteName('a.produkt_id') . ' = ' . $db->quoteName('b.id') . ')')
					->where($db->quoteName('b.id') . ' =  '. $product['produkt_id']);
				$db->setQuery($query);
				//die(str_replace('#_', 'jos', $db->getQuery()));
				$result = $db->loadObject();

				$products[$counter]['produkt_titel'] = $result->produkt_titel;
				$products[$counter]['produkt_preis'] = $result->produkt_preis;
			}
		}



		if ($db->getErrorNum())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Check that we have a result.

		if (empty($products))
		{
			return false;
		}

		//Return the array

		return $products;
	}

	/******************************************************************************/
	// Save products based on quantity
	/*****************************************************************************/

	public function saveProducts($products){

		$cartObject = new stdClass();

		foreach($products as $product){
			$cartObject->user_id = $product['user_id'];
			$cartObject->produkt_id= $product['produkt_id'];
			$result = JFactory::getDbo()->insertObject('#__simpleshop_usercart', $cartObject);
		}

	}

	/******************************************************************************/
	// Get order ID for mail
	/*****************************************************************************/

	public function getOrderID($dateOrdered, $userId){

		$dateOrdered = '"'.$dateOrdered.'"';
		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$query
			->select( 'id')
			->from($db->quoteName('#__simpleshop_orders'))
			->where($db->quoteName('created') . ' = '. $dateOrdered)
			->andWhere($db->quoteName('userid') . ' = '. $userId);
		$db->setQuery($query);
		//die(str_replace('#_', 'jos', $db->getQuery()));
		return  $results = $db->loadResult();
	}

	/******************************************************************************/
	// Save orders
	/*****************************************************************************/

	public function saveOrder($cartData){

		$cartObject = new stdClass();
		$cartObject->userid = $cartData['userid'];
		$cartObject->name = $cartData['name'];
		$cartObject->email = $cartData['email'];
		$cartObject->orders= $cartData['orders'];
		$cartObject->adresse= $cartData['adresse'];
		$cartObject->created= $cartData['created'];

		$result = JFactory::getDbo()->insertObject('#__simpleshop_orders', $cartObject);
	}

	/******************************************************************************/
	// Refresh products in cart
	/*****************************************************************************/

	public function refreshProducts($products, $userId, $produktID){

		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$query
			->delete('#__simpleshop_usercart')
			->where($db->quoteName('user_id') . ' = '. $userId)
			->where($db->quoteName('produkt_id') . ' = '. $produktID);
		$db->setQuery($query);
		$db->execute();

		$cartObject = new stdClass();

		foreach($products as $product){
			$cartObject->user_id = $product['user_id'];
			$cartObject->produkt_id= $product['produkt_id'];
			$result = JFactory::getDbo()->insertObject('#__simpleshop_usercart', $cartObject);
		}

	}

	/******************************************************************************/
	// Delete usercart
	/*****************************************************************************/

	public function deleteCart($userId){

		//var_dump($userId);die;
		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$query
			->delete( '#__simpleshop_usercart')
			->where($db->quoteName('user_id') . ' = '. $userId);
		$db->setQuery($query);
		//die(str_replace('#_', 'jos', $db->getQuery()));
		return  $results = $db->loadResult();
	}

}