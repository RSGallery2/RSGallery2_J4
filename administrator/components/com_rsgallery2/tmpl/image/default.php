<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

// $this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

echo 'default.php: ' . realpath(dirname(__FILE__)) . '<br>';
?>


<form action="<?php echo Route::_('index.php?option=com_rsgallery2'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="d-flex flex-row">
        <?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
                <?php echo $this->sidebar; ?>
			</div>
        <?php endif; ?>
		<!--div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
		<div class="flex-fill">
			<div id="j-main-container" class="j-main-container">


			</div>
		</div>
	</div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>



