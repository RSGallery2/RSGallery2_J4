<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2023 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

/*--------------------------------------------------------------------------------
	db transfer j3x galleries
--------------------------------------------------------------------------------*/

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximages'); ?>"
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

                <div class="card text-center" >
                    <div class="card-body">
                        <h3 class="card-title"><?php echo Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES', true);?></h3>

                        <p class="card-text"><?php echo Text::_('COM_RSGALLERY2_USE_BELOW_BUTTON'); ?></p>

                        <button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('MaintenanceJ3x.copyDbJ3xImages2J4xUser');return false;">
				            <?php echo JText::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES'); ?>
                        </button>

                    </div>
                </div>

                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="task" value=""/>
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>












