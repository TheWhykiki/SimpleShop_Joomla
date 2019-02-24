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
 * HelloWorlds View
 *
 * @since  0.0.1
 */
class SimpleshopViewOrders extends JViewLegacy
{
	/**
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{

		// Get application
		$app = JFactory::getApplication();


		$context = "simpleshop.list.admin.orders";


		$this->items		= $this->get('Items');

		$this->state			= $this->get('State');
		$this->filter_order 	= $app->getUserStateFromRequest($context.'filter_order', 'filter_order', 'id', 'cmd');
		$this->filter_order_Dir = $app->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
		$this->filterForm    	= $this->get('FilterForm');

		$this->activeFilters 	= $this->get('ActiveFilters');


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		// Set the submenu
		SimpleshopHelper::addSubmenu('simpleshop');

		// Set the toolbar and number of found items
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}


	protected function addToolBar()
	{
		$title = JText::_('Simpleshop Bestellhistorie');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'orders');
		JToolBarHelper::deleteList('', 'order.delete');
		JToolBarHelper::custom('order.deleteAllCarts', 'warning', '', 'Alle Carts leeren', false);

	}

	protected function setDocument()
	{
		$document = JFactory::getDocument();
		//$document->setTitle(JText::_('COM_HELLOWORLD_ADMINISTRATION'));
	}

}