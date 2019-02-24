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

<h2><?php echo $menuParams['page_title']; ?></h2>
<ul class="allDownloadsList">
    <?php foreach($this->allDownloads as $download): ?>
        <li class="allDownloadsSingleDownload">
            <h3 class="allDownloadsTitle"><?php echo $download->download_titel; ?></h3>
	        <p class="allDownloadsText"><?php echo $download->download_text; ?></p>
            <input type="hidden" id="hiddenID-<?php echo $download->id;?>" value="<?php echo $download->id;?>">
            <a class="allDownloadsLink" href="../user_downloads/<?php echo $download->download_url; ?>" target="_blank"><?php echo $download->download_url; ?></a>

            <button
                    class="btnDownload btn btnAdd btn-primary"
                    id="buttonAdd-<?php echo $download->id;?>"
                    data-downloadid="<?php echo $download->id;?>"
                    data-messageadd="Zum Merkzettel hinzufügen"
                    data-iconadd="fa-download"
                    data-messageremove="Vom Merkzettel entfernen"
                    data-iconremove="fa-trash"
            >
                <span>Zum Merkzettel hinzufügen</span>
                &nbsp;
                <i class="fa fa-download"></i>
            </button>
        </li>

    <?php endforeach; ?>

</ul>

<?php echo '<input id="token" type="hidden" name="' . JSession::getFormToken() . '" value="1" />'; ?>
