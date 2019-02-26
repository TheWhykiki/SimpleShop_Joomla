<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_merkzettel
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * Merkzettel Component Controller
 *
 * @since  0.0.1
 */
class SimpleshopController extends JControllerLegacy
{

	/******************************************************************************/
	// Add product to cart
	/*****************************************************************************/


	public function ajaxAddProduct()
	{

		$modelUserCart = $this->getModel('usercart');

		$userId = $this->_getUserId();


		$jinput = JFactory::getApplication()->input;
		$produktID = $jinput->get('produktID');
		$quantity = $jinput->get('quantity');
		$eigenschaft = $jinput->get('eigenschaft');


		if (!JSession::checkToken('get'))
		{

			echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
		}
		else
		{
			$modelUserCart->storeValuesFromAjax($produktID,$userId ,$quantity, $eigenschaft);
			$modelUserCart->getUserCart($userId);

			parent::display();
		}
	}


	/******************************************************************************/
	// Refresh cart ajax
	/*****************************************************************************/

	public function ajaxRefreshCart()
	{

		$modelUserCart = $this->getModel('usercart');
		$userId = $this->_getUserId();

		$jinput = JFactory::getApplication()->input;
		$produktID = $jinput->get('produktID');
		$quantity = $jinput->get('quantity');
		$produktEigenschaft = $jinput->get('produktEigenschaft');

		if (!JSession::checkToken('get'))
		{

			echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
		}
		else
		{
			$modelUserCart->refreshValuesFromAjax($userId, $quantity, $produktID,$produktEigenschaft);
			parent::display();
		}
	}

	/******************************************************************************/
	// Remove product cart ajax
	/*****************************************************************************/

	public function ajaxRemoveProduct()
	{

		$modelUserCart = $this->getModel('usercart');
		$userId = $this->_getUserId();

		$jinput = JFactory::getApplication()->input;
		$produktID = $jinput->get('produktID');
		$quantity = $jinput->get('quantity');
		$produktEigenschaft = $jinput->get('produktEigenschaft');

		if (!JSession::checkToken('get'))
		{

			echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
		}
		else
		{
			$modelUserCart->removeProductFromCard($userId, $quantity, $produktID,$produktEigenschaft);
			parent::display();
		}
	}

	/******************************************************************************/
	// Show user cart ajax
	/*****************************************************************************/


	public function showUserCart()
	{

		$modelUserCart = $this->getModel('usercart');
		$userId = $this->_getUserId();

		if (!JSession::checkToken('get'))
		{

			echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
		}
		else
		{
			$modelUserCart->getUserCart($userId);
			echo new JResponseJson($modelUserCart->getUserCart($userId),true, false);

		}
	}

	/******************************************************************************/
	// Get product properties
	/*****************************************************************************/


	public function getProductProperties()
	{

		$modelProduct = $this->getModel('product');

		$jinput = JFactory::getApplication()->input;
		$produktID = $jinput->get('produktID');

		if (!JSession::checkToken('get'))
		{

			echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
		}
		else
		{
			$modelProduct->getProductProperties($produktID);
			$produkt = $modelProduct->getProductProperties($produktID);
			//var_dump($produkt);die;
			$produktEigenschaften = explode(",", $produkt[0]->produkt_eigenschaften);
			$produktEigenschaftenPreise = explode(",", $produkt[0]->produkt_eigenschaften_preis);
			$productProperties = array();
			$productProperties = array_combine($produktEigenschaften, $produktEigenschaftenPreise);

			$newProperties = array(
				'produkt_preis' => $produkt[0]->produkt_preis,
				'produkt_steuer' => $produkt[0]->produkt_steuer,
				'produkt_eigenschaften' => $productProperties
			);

			//var_dump($newProperties);die;

			echo new JResponseJson($newProperties,true, false);

		}
	}

	/******************************************************************************/
	// Save order data from ajax
	/*****************************************************************************/

	public function saveOrder()
	{
		$app    = JFactory::getApplication();
		$modelUserCart = $this->getModel('usercart');

		$modelUserCart->storeUserOrder();
		$modelUserCart->sendOrderMail();
		$modelUserCart->emptyCart();
		$params = $app->getParams('com_simpleshop');
		$return_url = $params->get('return_url');


		$app->redirect($return_url);

	}

	public function showGuestData(){

		$modelUserCart = $this->getModel('usercart');

		$userId = $this->_getUserId();

		$modelUserCart->getUserCart($userId);

		parent::display();

	}

	protected function _getUserId(){
		$user = JFactory::getUser();

		if($user->id == 0){

			// Get input cookie object
			$inputCookie  = JFactory::getApplication()->input->cookie;

			// Get cookie data
			$value        = $inputCookie->get($name = 'userid', $defaultValue = null);

			if(empty($value)){

				$idGenerated = mt_rand ( 9999 , 999999 );

				// Check that cookie exists

				$cookieExists = ($value !== null);

				// Set cookie data
				$inputCookie->set($name = 'userid', $value = $idGenerated , $expire = time()+60*60*24*30);
				$userId = $value;
				return $userId;
			}
			else{
				$value        = $inputCookie->get($name = 'userid', $defaultValue = null);
				$userId = $value;
				return $userId;
			}
		}
		else{
			$userId = $user->id;
			return $userId;
		}
	}

}