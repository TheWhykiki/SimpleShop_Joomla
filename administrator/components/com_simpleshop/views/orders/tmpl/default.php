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
aa
<form action="index.php?option=com_simpleshop

&view=orders" method="post" id="adminForm" name="adminForm">
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
			<th>
				<?php echo JHtml::_('grid.sort', 'ID', 'id', $listDirn, $listOrder); ?>
			</th>
            <th>
				<?php echo JHtml::_('grid.sort', 'Bestelldatum', 'created', $listDirn, $listOrder); ?>
            </th>
            <th>
				<?php echo JHtml::_('grid.sort', 'Kundendaten', 'adresse', $listDirn, $listOrder); ?>
            </th>
            <th>
				<?php echo JHtml::_('grid.sort', 'E-Mail', 'email', $listDirn, $listOrder); ?>
            </th>
            <th>
				<?php echo JHtml::_('grid.sort', 'Order', 'email', $listDirn, $listOrder); ?>
            </th>
            <th>
				<?php echo JHtml::_('grid.sort', 'Gesamtsumme', 'email', $listDirn, $listOrder); ?>
            </th>
			<th width="2%">
				<?php echo JHtml::_('grid.sort', 'ID', 'id', $listDirn, $listOrder); ?>
			</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="5">

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
                        <?php echo $row->id; ?>
                    </td>

                    <td>
                        <?php echo $row->created; ?>
                    </td>
                    <td>
                        <?php echo $row->name; ?><br>
                        <?php echo $row->adresse; ?>
                    </td>

                    <td>
	                    <?php echo $row->email; ?>
                    </td>

                    <td>
                        <table>
                            <thead>
                                <tr>
                                    <th>Produkttitel</th>
                                    <th>Menge</th>
                                    <th>Einzelpreis</th>
                                    <th>Gesamtpreis</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                            $orders = json_decode($row->orders);
                            $gesamtsumme = array();
                            foreach($orders as $order){
                                $gesamtsumme[] = $order->produkt_preis * $order->counter;
                                echo '<tr>';
                                echo '<td>'.$order->produkt_titel.'</td>';
	                            echo '<td>'.$order->counter.'</td>';
	                            echo '<td>'.$order->produkt_preis.' €</td>';
	                            echo '<td>'.$order->produkt_preis * $order->counter.' €</td>';
	                            echo '</tr>';

                            }

                            ?>
                            </tbody>

                        </table>
                    </td>
                    <td>
                        <?php echo array_sum($gesamtsumme).' €'; ?>
                    </td>

					<td align="center">
						<?php echo $row->userid; ?>
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