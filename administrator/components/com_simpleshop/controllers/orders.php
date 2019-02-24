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
 * HelloWorlds Controller
 *
 * @since  0.0.1
 */
class SimpleshopControllerOrders extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6

/*
	public function getModel($name = 'Download', $prefix = 'MerkzettelModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);


		var_dump($model);
		return $model;
	}
*/
/*
	public function delete()
	{
		$input           = JFactory::getApplication()->input;
		$recs            = $input->get('cid', array(), 'array');
		$recs2            = $input->get('id', array(), 'array');
		$numberOfRecords = $input->get('boxchecked', 0, 'int');

		$model = $this->getModel('order', 'simpleshopmodel');


		//die(var_dump($recs2));

		//var_dump($model->getDownloadsToDelete());

		// Call Method in download-Model


		//$model->getDownloadsToDelete($recs);
		JLoader::import('joomla.application.component.model'); //Load the Joomla Application Framework

		//JLoader::import( 'usermerkzettel', JPATH_SITE  . '/components/com_simpleshop

/models' ); //Call the frontend model directory


		//$modelUserDownloads = JModelLegacy::getInstance( 'usermerkzettel', 'merkzettelmodel' );
		//$modelUserDownloads->deleteValuesFromBackend($recs);
		//$model->delete($recs);


		//var_dump($numberOfRecords);

		$msg = "Gelöscht";
		JFactory::getApplication()->enqueueMessage($msg);

		var_dump($msg);die;
		$this->setRedirect(JRoute::_('index.php?option=com_simpleshop

&view=orders', $msg));

	}


*/
}