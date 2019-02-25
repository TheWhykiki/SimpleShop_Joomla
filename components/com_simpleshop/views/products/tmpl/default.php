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

$menuParams = $this->params;

$user = JFactory::getUser();
$app = JFactory::getApplication();

// Get active menu
$currentMenuItem = $app->getMenu()->getActive();
// Get params for active menu
$params = $currentMenuItem->params;

?>

<?php if(empty($user->id)): ?>
<form action="index.php?option=com_simpleshop&view=usercart" method="post" class="submitForm">
<?php endif; ?>
<?php if(!empty($params->get('page_heading'))):?>
<div class="row">
    <div class="col-md-12 shopHeadline">
        <h2><?php echo $params->get('page_heading'); ?></h2>
    </div>
</div>
<?php endif; ?>
<div class="row">
    <div class="col-md-3 sidebar" >
        <div class="cartContainer">

        </div>
	    <?php if(empty($user->id)): ?>
            <button type="submit" class="btn btn-success btnSubmitCartGuest buttonHide submitCart"><?php echo JText::_('COM_SIMPLESHOP_SEND_ORDER'); ?> <i class="fa fa-share-square"></i></button>
            <br>
            <button class="btn btn-primary sendCart buttonHide" id="toAddress"><?php echo JText::_('COM_SIMPLESHOP_ADD_ADDRESS'); ?> <i class="fa fa-address-card"></i></button>

	    <?php else: ?>
            <a href="/index.php?option=com_simpleshop&view=usercart" class="btn btn-success btnSubmitCart buttonHide submitCart"><?php echo JText::_('COM_SIMPLESHOP_SEND_ORDER'); ?> <i class="fa fa-share-square"></i></a>
	    <?php endif; ?>
    </div>
    <div class="col-md-9 webshopContentWrapper">
        <div class="row">
	        <?php foreach($this->allProducts as $produkt): ?>
            <div class="col-md-4 productsSingleProductContainer">
                <?php
                    $slug = JFilterOutput::stringURLSafe($produkt->produkt_titel);
                    $slug = $produkt->id.':'.$slug;
                    $id = $produkt->id;
				    $link = JRoute::_(SimpleshopHelperRoute::getProductRoute($slug));
                ?>
                <a href="<?php echo $link; ?>">
                    <h3 class="productTitle"><?php echo $produkt->produkt_titel; ?></h3>
                </a>

                <img src="<?php echo $produkt->produkt_bild; ?>" class="webshopImage">

                <p class="produktPreis"><?php echo str_replace('.',',',$produkt->produkt_preis); ?> <?php echo JText::_('COM_SIMPLESHOP_CURRENCY'); ?></p>

                <div class="orderContainer">
                    <input type="hidden" id="hiddenID-<?php echo $produkt->id;?>" value="<?php echo $produkt->id;?>">
                    <input type="text" name=menge" class="menge" id="menge-<?php echo $produkt->id;?>" value="">
	                <?php if(isset($produkt->produkt_eigenschaften)): ?>
		                <?php $produktEigenschaften = explode(",", $produkt->produkt_eigenschaften);?>
                        <select name="produkt_eigenschaften" id="produkt_eigenschaften<?php echo $produkt->id;?>">
			                <?php foreach($produktEigenschaften as $eigenschaft): ?>
                                <option value="<?php echo $eigenschaft; ?>"><?php echo $eigenschaft; ?></option>
			                <?php endforeach; ?>
                        </select>
	                <?php endif; ?>
                    <button
                            class="btnAddProduct btn btnAdd btn-primary"
                            id="buttonAdd-<?php echo $produkt->id;?>"
                            data-produktid="<?php echo $produkt->id;?>"
                    >
                        <span><?php echo JText::_('COM_SIMPLESHOP_ADDTOCART');?></span>
                        &nbsp;
                        <i class="fa fa-cart-plus"></i>
                    </button>
                </div>

            </div>
	        <?php endforeach; ?>
        </div>
	    <?php if(empty($user->id)): ?>
        <div class="guestContainer">
            <div class="row guestRow">
                <h2>Als Gast bestellen</h2>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name"><?php echo JText::_('COM_SIMPLESHOP_NAME_LABEL'); ?></label>
                        <input type="text" class="form-control guestInput" name="name" id="name" placeholder="<?php echo JText::_('COM_SIMPLESHOP_NAME_LABEL'); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email"><?php echo JText::_('COM_SIMPLESHOP_MAIL_LABEL'); ?></label>
                        <input type="email" class="form-control guestInput" name="email" id="email" placeholder="<?php echo JText::_('COM_SIMPLESHOP_MAIL_LABEL'); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="strasse"><?php echo JText::_('COM_SIMPLESHOP_STREET_LABEL'); ?></label>
                        <input type="text" class="form-control guestInput" name="strasse" id="strasse" placeholder="<?php echo JText::_('COM_SIMPLESHOP_STREET_LABEL'); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ort"><?php echo JText::_('COM_SIMPLESHOP_ZIPLOCATION_LABEL'); ?></label>
                        <input type="text" class="form-control guestInput" name="ort" id="plz" placeholder="<?php echo JText::_('COM_SIMPLESHOP_ZIPLOCATION_LABEL'); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 warning">
                    <button type="submit" class="btn btn-success btnSubmitCartGuest submitCart"><?php echo JText::_('COM_SIMPLESHOP_SENDORDER'); ?></button>
                </div>
            </div>
        </div>
	    <?php endif; ?>



	    <?php echo '<input id="token" type="hidden" name="' . JSession::getFormToken() . '" value="1" />'; ?>
    </div>
</div>
<?php if(empty($user->id)): ?>
</form>
<?php endif; ?>
