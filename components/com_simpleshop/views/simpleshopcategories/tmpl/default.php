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
$userID = $this->user_id;
$userDownloadsInCart = $this->userDownloadsInCart;

?>

<?php if(empty($user->id)): ?>
<form action="index.php?option=com_simpleshop&view=usercart" method="post">
	<?php endif; ?>
    <div class="row">
        <div class="col-md-12 shopHeadline">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 sidebar" >
            <div class="cartContainer">
            </div>
			<?php if(empty($user->id)): ?>
                <button type="submit" class="btn btn-success btnSubmitCartGuest buttonHide submitCart">Bestellung absenden <i class="fa fa-share-square"></i></button>
                <br>
                <button class="btn btn-primary sendCart buttonHide" id="toAddress">Zur Adresseingabe <i class="fa fa-address-card"></i></button>

			<?php else: ?>
                <a href="/index.php?option=com_simpleshop&view=usercart" class="btn btn-success btnSubmitCart buttonHide submitCart">Bestellung absenden <i class="fa fa-share-square"></i></a>
			<?php endif; ?>

        </div>
        <div class="col-md-9 webshopContentWrapper">
            <div class="row">
                <div class="col-md-12">
					<?php foreach($this->AllProductsByCategory as $produkt): ?>

						<?php
						$slug = JFilterOutput::stringURLSafe($produkt->produkt_titel);
						$slug = $produkt->id.':'.$slug;
						$id = $produkt->id;
						$link = JRoute::_(SimpleshopHelperRoute::getProductRoute($slug));
						//var_dump($link);
						?>
                        <a href="<?php echo $link; ?>">link</a>
						<?php  if(empty($user->id) AND $produkt->catid == 29):?>
                            <div class="row webshopRow">
                                <div class="col-12 col-md-2 webshopImageContainer h-100">
                                    <img src="<?php echo $produkt->produkt_bild; ?>" class="webshopImage">
                                </div>
                                <div class="col-12 col-md-10 webshopInfoContainer h-100"><h2><?php echo $produkt->produkt_titel; ?></h2>
                                    <p class="produktPreis"><?php echo str_replace('.',',',$produkt->produkt_preis); ?> € / Verpackungseinheit</p>
									<?php echo $produkt->produkt_beschreibung; ?>

                                    <div class="orderContainer">
                                        <input type="hidden" id="hiddenID-<?php echo $produkt->id;?>" value="<?php echo $produkt->id;?>">
                                        <input type="text" name=menge" class="menge" id="menge-<?php echo $produkt->id;?>" value="">
                                        <button
                                                class="btnAddProduct btn btnAdd btn-primary"
                                                id="buttonAdd-<?php echo $produkt->id;?>"
                                                data-produktid="<?php echo $produkt->id;?>"
                                                data-messageadd="Zum Merkzettel hinzufügen"
                                                data-iconadd="fa-download"
                                                data-messageremove="Vom Merkzettel entfernen"
                                                data-iconremove="fa-trash"
                                        >
                                            <span>Zum Warenkorb hinzufügen</span>
                                            &nbsp;
                                            <i class="fa fa-cart-plus"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
						<?php else: ?>
                            <div class="row webshopRow">
                                <div class="col-12 col-md-2 webshopImageContainer h-100">
                                    <img src="<?php echo $produkt->produkt_bild; ?>" class="webshopImage">
                                </div>
                                <div class="col-12 col-md-10 webshopInfoContainer h-100"><h2><?php echo $produkt->produkt_titel; ?></h2>
                                    <p class="produktPreis"><?php echo str_replace('.',',',$produkt->produkt_preis); ?> € / Verpackungseinheit</p>
									<?php echo $produkt->produkt_beschreibung; ?>

                                    <div class="orderContainer">
                                        <input type="hidden" id="hiddenID-<?php echo $produkt->id;?>" value="<?php echo $produkt->id;?>">
                                        <input type="text" name=menge" class="menge" id="menge-<?php echo $produkt->id;?>" value="">
                                        <button
                                                class="btnAddProduct btn btnAdd btn-primary"
                                                id="buttonAdd-<?php echo $produkt->id;?>"
                                                data-produktid="<?php echo $produkt->id;?>"
                                                data-messageadd="Zum Merkzettel hinzufügen"
                                                data-iconadd="fa-download"
                                                data-messageremove="Vom Merkzettel entfernen"
                                                data-iconremove="fa-trash"
                                        >
                                            <span>Zum Warenkorb hinzufügen</span>
                                            &nbsp;
                                            <i class="fa fa-cart-plus"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
						<?php endif; ?>




					<?php endforeach; ?>
                </div>
            </div>
			<?php if(empty($user->id)): ?>
                <div class="row guestRow">
                    <h2>Als Gast bestellen</h2>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Vor- und Nachname:</label>
                            <input type="text" class="form-control guestInput" name="name" id="name" placeholder="Vor- und Nachnamen eingeben">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">E-Mailadresse:</label>
                            <input type="email" class="form-control guestInput" name="email" id="email" placeholder="E-Mailadresse eingeben">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="strasse">Strasse und Hausnummer:</label>
                            <input type="text" class="form-control guestInput" name="strasse" id="strasse" placeholder="Strasse und Hausnummer eingeben">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">PLZ, Ort:</label>
                            <input type="text" class="form-control guestInput" name="ort" id="plz" placeholder="PLZ, Ort">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 warning">
                        <button type="submit" class="btn btn-success btnSubmitCartGuest">Bestellung absenden</button>
                    </div>
                </div>
			<?php endif; ?>



			<?php echo '<input id="token" type="hidden" name="' . JSession::getFormToken() . '" value="1" />'; ?>
        </div>
    </div>
	<?php if(empty($user->id)): ?>
</form>
<?php endif; ?>

<?php echo '<input id="token" type="hidden" name="' . JSession::getFormToken() . '" value="1" />'; ?>
