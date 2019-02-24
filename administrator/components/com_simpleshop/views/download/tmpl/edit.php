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

?>
<form action="<?php echo JRoute::_('index.php?option=com_merkzettel&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details',
			empty($this->item->id) ? JText::_('Details') : JText::_('Details')); ?>
            <fieldset class="adminform">
                <legend><?php echo JText::_('Details') ?></legend>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo $this->form->renderFieldset('details');  ?>
                    </div>
                </div>
            </fieldset>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'params',
			empty($this->item->id) ? JText::_('Params') : JText::_('Params')); ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('Params') ?></legend>
            <div class="row-fluid">
                <div class="span6">
					<?php echo $this->form->renderFieldset('params');  ?>
                </div>
            </div>
        </fieldset>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="download.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>