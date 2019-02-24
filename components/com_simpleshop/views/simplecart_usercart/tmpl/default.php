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

$allDownloadsByUser = $this->allDownloads;
//phpinfo();

?>


<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">Download Titel</th>
        <th scope="col">Download Url</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody class="tableBody">
    </tbody>
</table>

<a href="index.php?option=com_merkzettel&task=makeZipFromFiles" target="_blank" class="downloadAll btn btn-primary">
    Alle herunterladen
</a>





<?php echo '<input id="token" type="hidden" name="' . JSession::getFormToken() . '" value="1" />'; ?>
