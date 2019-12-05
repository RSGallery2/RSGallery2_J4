<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.framework');






?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbCopyOldConfig'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="col-md-2">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>
		<div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
			<div id="j-main-container" class="j-main-container">

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'DbCopyOldConfig')); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DbCopyOldConfig', JText::_('COM_RSGALLERY2_COPY_OLD_CONFIG', true)); ?>

                <legend><strong><?php echo JText::_('COM_RSGALLERY2_COPY_OLD_CONFIG'); ?></strong></legend>

                <p><h3>DbCopyOldConfig</h3></p>
                <?php

					try
					{




					}
					catch (RuntimeException $e)
					{
						$OutTxt = '';
						$OutTxt .= 'Error rawEdit view: "' . 'DbCopyOldConfig' . '"<br>';
						$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
					
						$app = Factory::getApplication();
						$app->enqueueMessage($OutTxt, 'error');
					}

				?>

				<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

				<?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_rsgallery2" />
				<input type="hidden" name="rsgOption" value="maintenance" /-->

				<input type="hidden" name="task" value="" />
				<?php echo HTMLHelper::_('form.token'); ?>
            </div>
		</div>
	</div>

	<?php echo HTMLHelper::_('form.token'); ?>
</form>


