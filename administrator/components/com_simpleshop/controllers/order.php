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

/**
 * HelloWorld Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 * @since       0.0.9
 */
class SimpleshopControllerOrder extends JControllerForm
{
	public function delete()
	{
		$input           = JFactory::getApplication()->input;
		$recs            = $input->get('cid', array(), 'array');
		$recs2            = $input->get('id', array(), 'array');
		$numberOfRecords = $input->get('boxchecked', 0, 'int');

		$model = $this->getModel('Order', 'SimpleshopModel');




		//$model->getDownloadsToDelete($recs);
		JLoader::import('joomla.application.component.model'); //Load the Joomla Application Framework

		//JLoader::import( 'usermerkzettel', JPATH_SITE  . '/components/com_simpleshop

/models' ); //Call the frontend model directory


		//$modelUserDownloads = JModelLegacy::getInstance( 'usermerkzettel', 'merkzettelmodel' );
		//$modelUserDownloads->deleteValuesFromBackend($recs);
		$model->delete($recs);


		//var_dump($numberOfRecords);

		if(count($recs) > 1){
			$msg = "Bestellungen gelöscht";
		}
		else{
			$msg = "Bestellung gelöscht";
		}

		JFactory::getApplication()->enqueueMessage($msg);

		$url = 'index.php?option=com_simpleshop

&view=orders';



		$this->setRedirect($url, $msg);

	}

	public function deleteAllCarts()
	{
		$input           = JFactory::getApplication()->input;
		$recs            = $input->get('cid', array(), 'array');
		$recs2            = $input->get('id', array(), 'array');
		$numberOfRecords = $input->get('boxchecked', 0, 'int');

		$model = $this->getModel('Order', 'SimpleshopModel');




		//$model->getDownloadsToDelete($recs);
		JLoader::import('joomla.application.component.model'); //Load the Joomla Application Framework


		$model->emptyUsercartsTable();

		$msg = "Alle Warenörbe geleert";

		JFactory::getApplication()->enqueueMessage($msg);

		$url = 'index.php?option=com_simpleshop

&view=orders';

		$this->setRedirect($url, $msg);

	}
}