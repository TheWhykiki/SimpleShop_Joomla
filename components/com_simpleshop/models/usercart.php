<?php

class SimpleshopModelUsercart extends JModelItem
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
	public function getTable($type = 'Usercart', $prefix = 'SimpleshopTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);

	}

	/******************************************************************************/
	// Check if user or guest
	/*****************************************************************************/

	protected function _getUserID(){
		$user = JFactory::getUser();


		if($user->id == 0)
		{
			// Get input cookie object
			$inputCookie = JFactory::getApplication()->input->cookie;

			// Get cookie data
			//var_dump($inputCookie->get($name = 'userid', $defaultValue = null));die;
			return $userID = $inputCookie->get($name = 'userid', $defaultValue = null);
		}
		else{
			return $userID = $user->id;
		}
	}


	/******************************************************************************/
	// Get user data (address)
	/*****************************************************************************/

	public function getUserData()
	{

		$user = JFactory::getUser();

		if($user->id == 0){
			$jinput = JFactory::getApplication()->input;

			$userData= [
				'name' => $jinput->get('name', '', 'string'),
				'email' => $jinput->get('email', '', 'string'),
				'strasse' => $jinput->get('strasse', '', 'string'),
				'ort' => $jinput->get('ort', '', 'string'),
			];

			return $userData;

		}
		else{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__filialfinder_stores'));
			$query->where($db->quoteName('owner_id') . ' = '. $db->quote($user->id));
			$db->setQuery($query);

			$results = $db->loadObject();

			$userData= [
				'name' => $results->name,
				'email' => $results->email,
				'strasse' => $results->strasse,
				'ort' => $results->plz." ".$results->stadt
			];
			return $userData;
		}
	}


	/******************************************************************************/
	// Get current user cart
	/*****************************************************************************/

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

	public function getUserCart()
	{

		$userID = $this->_getUserID();

		// Get a TableHelloWorld instance
		$table = $this->getTable();

		// Load all downloads

		return $table->loadAllByUser($userID);
	}


	/******************************************************************************/
	// Save cart values from ajax call
	/*****************************************************************************/

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

	public function storeValuesFromAjax($produktID,$userId, $quantity, $eigenschaft)
	{
		$table = $this->getTable();
		$produktValues = [];
		for ($i = 0; $i <= $quantity - 1; $i++) {
			$produktValues[$i] = [
				'user_id' => $userId,
				'produkt_id' => $produktID,
				'produkt_eigenschaft' => $eigenschaft
			];
		}
		//var_dump($produktValues);die;
		return $table->saveProducts($produktValues);
	}

	/******************************************************************************/
	// Save order and send mail
	/*****************************************************************************/

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

	// Get the order date

	protected function _getDate(){
		return $date = date("Y-m-d H:i:s");
	}

	public function sendOrderMail(){

		$table = $this->getTable();
		$date = $this->_getDate();

		$userID = $this->_getUserID();
		$orderID = $table->getOrderID($date,$userID);
		$userCart = json_encode($this->getUserCart());
		$userData = $this->getUserData();
		JLoader::import('helpers.ordermailer', JPATH_COMPONENT);
		$mailerHelper = new OrdermailerHelper();
		$mailerHelper->mailOrder($userCart, $orderID, $userData);
	}


	public function storeUserOrder()
	{
		$userID = $this->_getUserID();
		$userCart = json_encode($this->getUserCart());
		$date = $this->_getDate();
		$userData = $this->getUserData();

		//ar_dump($userData['name']);die;
		$adresse = $userData['strasse'].'<br>'.$userData['plz'].' '.$userData['ort'];

		$cartData = [
			'userid' => $userID,
			'orders' => $userCart,
			'name' =>  $userData['name'],
			'email' =>  $userData['email'],
			'adresse' => $adresse,
			'created' => $date
		];

		$table = $this->getTable();
		//var_dump($table);die;

		return $table->saveOrder($cartData);
	}



	/******************************************************************************/
	// Empty cart when ordered
	/*****************************************************************************/

	public function emptyCart(){

		$userID = $this->_getUserID();

		$table = $this->getTable();
		//var_dump($table);die;
		return $table->deleteCart($userID);

	}

	/******************************************************************************/
	// Refresh cart values from ajax
	/*****************************************************************************/


	public function refreshValuesFromAjax($userId, $quantity, $produktID, $produktEigenschaft)
	{

		$table = $this->getTable();
		$produktValues = [];
		for ($i = 0; $i <= $quantity - 1; $i++) {
			$produktValues[$i] = [
				'user_id' => $userId,
				'produkt_id' => $produktID,
				'produkt_eigenschaft' => $produktEigenschaft
			];
		}

		return $table->refreshProducts($produktValues, $userId, $produktID, $produktEigenschaft);
	}

}