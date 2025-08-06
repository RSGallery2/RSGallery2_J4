<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2023-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

//HTMLHelper::_('bootstrap.framework');

// on more use preset ....
$this->document->getWebAssetManager()->useStyle('com_rsgallery2.backend.dbCopyJ3xConfig');

/*--------------------------------------------------------------------------------
	db transfer j3x configuration
--------------------------------------------------------------------------------*/
?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbcopyj3xconfig'); ?>"
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
						<h3 class="card-title"><?php echo Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG', true); ?></h3>

						<p class="card-text"><?php echo Text::_('COM_RSGALLERY2_USE_BELOW_BUTTON'); ?></p>

                        <button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('MaintenanceJ3x.copyJ3xConfig2J4xOptionsUser');return false;">
                            <?php echo Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG'); ?>
						</button>

					</div>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="task" value=""/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


