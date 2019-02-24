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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * HelloWorld Model
 *
 * @since  0.0.1
 */
class SimpleshopModelProduct extends JModelAdmin
{
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
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm(
			'com_simpleshop.product',
			'product_edit_form',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			'com_simpleshop.edit.product.data',
			array()
		);

		//var_dump($data);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;


	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$input	= JFactory::getApplication()->input;
		var_dump($data['alias']);
		$alias = JFilterOutput::stringURLSafe($data['produkt_titel']);
		$data['alias'] = $alias;
		var_dump($data['alias']);

		$filter	= JFilterInput::getInstance();

		// set the metadata to the Item Data
		if (isset($data['metadata']) && isset($data['metadata']['author']))
		{
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');

			$metadata = new JRegistry;
			$metadata->loadArray($data['metadata']);
			$data['metadata'] = (string) $metadata;
		}

		// Set the Params Items to data
		if (isset($data['params']) && is_array($data['params']))
		{
			$params = new JRegistry;
			$params->loadArray($data['params']);
			$data['params'] = (string) $params;
		}

		//TODO: Safe2copy make alias duplication safe

		// Alter the kuenstlername for save as copy
		if ($input->get('task') === 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));



			if ($data['produkt_titel'] == $origTable->produkt_titel)
			{
				list($produkt_titel, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['produkt_titel']);
				$data['produkt_titel'] = $produkt_titel;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] == $origTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['published'] = 0;

			//var_dump($data['alias']);die;
		}

		// Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (int) $input->get('id') == 0)
		{
			if ($data['alias'] == null || empty($data['alias']))
			{
				if (JFactory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['produkt_titel']);
				}
				else
				{
					$data['alias'] = JFilterOutput::stringURLSafe($data['produkt_titel']);
				}

				$table = JTable::getInstance('Product', 'SimpleshopTable');

				if ($table->load(array('alias' => $data['alias'], 'catid' => $data['catid'])) && ($table->id != $data['id'] || $data['id'] == 0))
				{
					$msg = JText::_('COM_KUENSTLERVERWALTUNG_BAND_SAVE_WARNING');
				}

				list($produkt_titel, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['kuenstlername']);
				$data['alias'] = $alias;

				if (isset($msg))
				{
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}



		if (parent::save($data))
		{
			return true;
		}
		return false;
	}

	/**
	 * Method to check if it's OK to delete a message. Overrides JModelAdmin::canDelete
	 */
	protected function canDelete($record)
	{
		if( !empty( $record->id ) )
		{
			return JFactory::getUser()->authorise( "core.delete", "com_helloworld.helloworld." . $record->id );
		}
	}


}