<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/*--------------------------------------------------------------------------------
	reset upgrade flags
--------------------------------------------------------------------------------*/
?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=resetupgradeflags'); ?>"
      method="post" name="adminForm" id="adminForm"
>
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
                        <h3 class="card-title">
							<?php echo Text::_('COM_RSGALLERY2_RESET_UPDATE_FLAGS', true); ?>
                        </h3>

                        <p class="card-text">
							<?php echo Text::_('COM_RSGALLERY2_RESET_UPDATE_FLAGS_DESC'); ?>
                            <br>
							<?php echo Text::_('COM_RSGALLERY2_USE_BELOW_BUTTON'); ?>
                        </p>
                    </div>
                </div>

				<?php renderFlags($this->form); ?>


                <div class="card text-center">
                    <div class="card-body">
                        <button class="btn btn-success" type="submit"
                                onclick="Joomla.submitbutton('MaintenanceJ3x.resetUpgradeFlags');return false;">
							<?php echo Text::_('COM_RSGALLERY2_DO_UPDATE_CHANGED_FLAGS'); ?>
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


<?php
function renderFlags($form)
{
//--- Select gallery and buttons ----------------------------------------------------------
	?>
<br>
    <div class="col-lg-9">
        <div class="control-group">
            <div class="controls">
                <div class="input-group">
					<?php
					//     $CopyDbConfig     = Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG');
					//    $CopyDbConfigDesc = Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG_DESC');
					//			echo '(1) ' . $form->renderField('dbcopyj3xconfiguser') . '<br>';
					//			echo '(2) ' . $form->renderField('dbtransferj3xgalleries') . '<br>';
					//			echo '(3) ' . $form->renderField('dbtransferj3ximages') . '<br>';
					//			echo '(4) ' . $form->renderField('changeJ3xMenuLinks') . '<br>';
					//			echo '(5) ' . $form->renderField('movej3ximagesuser') . '<br>';
					//			echo '(6) ' . $form->renderField('changeGidMenuLinks') . '<br>';
					echo $form->renderField('dbcopyj3xconfiguser');
					echo $form->renderField('dbtransferj3xgalleries');
					echo $form->renderField('dbtransferj3ximages');
					echo $form->renderField('changeJ3xMenuLinks');
					echo $form->renderField('movej3ximagesuser');
					echo $form->renderField('changeGidMenuLinks');


					?>
                </div>
            </div>
        </div>
    </div>

	<?php

}
?>
