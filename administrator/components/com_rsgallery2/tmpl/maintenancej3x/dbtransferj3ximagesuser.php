<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

/*--------------------------------------------------------------------------------
	db transfer j3x images (all)
--------------------------------------------------------------------------------*/

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximagesuser'); ?>"
      method="post" name="adminForm" id="adminForm">
	<div class="d-flex flex-row">
        <?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
                <?php echo $this->sidebar; ?>
			</div>
        <?php endif; ?>

		<!--div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
		<div class="flex-fill">
			<div id="j-main-container" class="j-main-container">

				<div class="card text-center">
					<div class="card-body">
						<h3 class="card-title"><?php echo Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES', true); ?></h3>

						<p class="card-text"><?php echo Text::_('COM_RSGALLERY2_USE_BELOW_BUTTON'); ?></p>

						<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('MaintenanceJ3x.copyDbJ3xImages2J4xUser');return false;">
                            <?php echo Text::_('COM_RSGALLERY2_DB_COPY_ALL_J3X_IMAGES'); ?>
						</button>

					</div>
				</div>

				<input type="hidden" name="boxchecked" value="0"/>
				<input type="hidden" name="task" value=""/>
			</div>
		</div>
	</div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>












