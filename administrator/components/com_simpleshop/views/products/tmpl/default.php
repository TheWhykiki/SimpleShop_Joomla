<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('formbehavior.chosen', 'select');

$listOrder     = $this->escape($this->filter_order);
$listDirn      = $this->escape($this->filter_order_Dir);
?>
<form action="index.php?option=com_simpleshop&view=products" method="post" id="adminForm" name="adminForm">
    <div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
    </div>
    <div id="j-main-container" class="span10">
    <div class="row-fluid">
        <div class="span6">
			<?php
			echo JLayoutHelper::render(
				'joomla.searchtools.default',
				array('view' => $this)
			);
			?>
        </div>
    </div>
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th width="2%">
				<?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th width="90%">
				<?php echo JHtml::_('grid.sort', 'Produkt Titel', 'produkt_titel', $listDirn, $listOrder); ?>
			</th>
            <th width="90%">
				<?php echo JHtml::_('grid.sort', 'Autor', 'author', $listDirn, $listOrder); ?>
            </th>
			<th width="2%">
				<?php echo JHtml::_('grid.sort', 'ID', 'id', $listDirn, $listOrder); ?>
			</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="5">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php if (!empty($this->items)) : ?>
			<?php foreach ($this->items as $i => $row) :
				$link = JRoute::_('index.php?option=com_simpleshop

&task=product.edit&id=' . $row->id);
				?>

				<tr>
					<td>
						<?php echo JHtml::_('grid.id', $i, $row->id); ?>
					</td>
					<td>
                        <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_HELLOWORLD_EDIT_HELLOWORLD'); ?>">
							<?php echo $row->produkt_titel; ?>
                        </a>
                        <div class="small">
							<?php echo JText::_('JCATEGORY') . ': ' . $this->escape($row->category_title); ?>
                        </div>
                    </td>

                    <td>
                        <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_HELLOWORLD_EDIT_HELLOWORLD'); ?>">
							<?php echo $row->author; ?>
                        </a>
                    </td>
					<td align="center">
						<?php echo $row->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
    </div>
</form>