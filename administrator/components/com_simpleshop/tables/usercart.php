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
			->select( array('*', 'COUNT(*) as counter') )
			->from($db->quoteName('#__simpleshop_usercart'))
			->where($db->quoteName('user_id') . ' = '. $userId)
			->group($db->quoteName('produkt_id'))
			->group($db->quoteName('produkt_eigenschaft'));
		$db->setQuery($query);
		//die(str_replace('#_', 'lj40r', $db->getQuery()));
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
		//var_dump($products);die;
		//var_dump($products);

		$db = $this->getDBO();

		$counter = -1;
		if (is_bool($products)){
			return false;
		}
		else if(empty($products)){
			return false;
		}
		else{
			foreach($products as $product){
				//var_dump($product);
				$counter++;
				$query = $db->getQuery(true);
				$query
					->select(array('a.*,b.*'))
					->from($db->quoteName('#__simpleshop','a'))
					->join('INNER', $db->quoteName('#__simpleshop_usercart', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.produkt_id') . ')')
					->where($db->quoteName('a.id') . ' =  '. $product['produkt_id']);
				$db->setQuery($query);
				//die(str_replace('#_', 'lj40r', $db->getQuery()));
				$result = $db->loadObject();


				// Get Title and Product Properties
				// Count product and set the values in array

				$products[$counter]['produkt_titel'] = $result->produkt_titel;
				$products[$counter]['produkt_eigenschaften'] = $result->produkt_eigenschaften;
				$products[$counter]['produkt_eigenschaften_preis'] = $result->produkt_eigenschaften_preis;

				// Make array from properties and price per property

				if(isset($result->produkt_eigenschaften_preis)){

					$preiseArray = explode(",", $result->produkt_eigenschaften_preis);
					$eigenschaftenArray = explode(",", $result->produkt_eigenschaften);
					$produkteigenschaftenPreise = array_combine($eigenschaftenArray, $preiseArray);

				}

				if(isset($result->produkt_eigenschaften) AND isset($result->produkt_eigenschaften_preis)){

					$pricePerProperty = $produkteigenschaftenPreise[$product['produkt_eigenschaft']];
					$products[$counter]['produkt_preis'] = $result->produkt_preis + $pricePerProperty;
					//var_dump($products[$counter]['produkt_preis']);
				}
				else{
					$products[$counter]['produkt_preis'] = $result->produkt_preis;
				}



				$products[$counter]['produkt_steuer'] = $products[$counter]['produkt_preis']  * ($result->produkt_steuer/100);
				$products[$counter]['produkt_preis_mit_steuer'] = $products[$counter]['produkt_preis']  + $products[$counter]['produkt_steuer'];
				$products[$counter]['produkt_total'] = $products[$counter]['produkt_preis_mit_steuer'] * $products[$counter]['counter'];


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

		//var_dump($products);
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
			$cartObject->produkt_eigenschaft = $product['produkt_eigenschaft'];
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

	public function refreshProducts($products, $userId, $produktID, $produktEigenschaft){

		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$query
			->delete('#__simpleshop_usercart')
			->where($db->quoteName('user_id') . ' = '. $userId)
			->where($db->quoteName('produkt_id') . ' = '. $produktID)
			->where($db->quoteName('produkt_eigenschaft') . ' = "'.$produktEigenschaft.'"');
		$db->setQuery($query);
		//die(str_replace('#_', 'lj40r', $db->getQuery()));
		$db->execute();

		$cartObject = new stdClass();

		foreach($products as $product){
			$cartObject->user_id = $product['user_id'];
			$cartObject->produkt_id= $product['produkt_id'];
			$cartObject->produkt_eigenschaft = $produktEigenschaft;
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