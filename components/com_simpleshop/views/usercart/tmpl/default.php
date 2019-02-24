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

$userCart = $this->userCart;
$userData = $this->userData;

?>
<form action="index.php?option=com_simpleshop

&view=usercart&task=saveOrder" method="post">
<div class="row">
    <div class="col-md-12">
        <h2>Ihre Bestellung auf einen Blick</h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col"><?php echo JText::_('COM_SIMPLESHOP_PRODUCTNAME_CART'); ?></th>
                <th scope="col"><?php echo JText::_('COM_SIMPLESHOP_QUANTITY'); ?></th>
                <th scope="col"><?php echo JText::_('COM_SIMPLESHOP_UNIT_COST'); ?></th>
                <th scope="col"><?php echo JText::_('COM_SIMPLESHOP_TOTAL'); ?></th>
            </tr>
            </thead>
            <tbody class="tableBody">
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h2><?php echo JText::_('COM_SIMPLESHOP_SHIPPING_HEADLINE'); ?></h2>
        <p><?php echo JText::_('COM_SIMPLESHOP_SHIPPING_TO'); ?></p>
        <ul class="cartAdress">
            <li><?php echo JText::_('COM_SIMPLESHOP_NAME_LABEL'); ?> <?php echo $userData['name']; ?></li>
            <li><?php echo JText::_('COM_SIMPLESHOP_MAIL_LABEL'); ?> <?php echo $userData['email']; ?></li>
            <li><?php echo JText::_('COM_SIMPLESHOP_STREET_LABEL'); ?> <?php echo $userData['strasse']; ?></li>
            <li><?php echo JText::_('COM_SIMPLESHOP_ZIPLOCATION_LABEL'); ?> <?php echo $userData['ort']; ?></li>

            <input type="hidden" name="name" value="<?php echo $userData['name']; ?>">
            <input type="hidden" name="strasse" value="<?php echo $userData['strasse']; ?>">
            <input type="hidden" name="email" value="<?php echo $userData['email']; ?>">
            <input type="hidden" name="ort" value="<?php echo $userData['ort']; ?>">

        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <a href="/shop" class="btn btn-danger">Zurück&nbsp;<i class="fa fa-times"></i></a>
        <button type="submit" class="btn btn-success">Bestellung absenden&nbsp;<i class="fa fa-check"></i></button>
    </div>
</div>
</form>





<?php echo '<input id="token" type="hidden" name="' . JSession::getFormToken() . '" value="1" />'; ?>
