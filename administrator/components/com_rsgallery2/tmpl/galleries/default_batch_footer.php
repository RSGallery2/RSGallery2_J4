<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

// ?? wa script see article

?>
<button type="button" class="btn btn-secondary" data-dismiss="modal">
    <?php
    echo Text::_('JCANCEL'); ?>
</button>
<button type="submit" id='batch-submit-button-id' class="btn btn-success" data-submit-task='gallery.batch'>
    <?php
    echo Text::_('JGLOBAL_BATCH_PROCESS'); ?>
</button>
