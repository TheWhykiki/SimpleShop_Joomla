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
 * HelloWorld Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 * @since       0.0.9
 */
class SimpleshopControllerProduct extends JControllerForm
{

	public function save($data = array(), $key = 'id')

	{

		$jinput = JFactory::getApplication()->input;
		$data = JRequest::getVar( 'jform', null, 'post', 'array' );
		JRequest::setVar('jform', $data );

		parent::save();

	}

}