<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2020 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.framework');

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=Maintenance&layout=Prepared'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="col-md-2">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>
		<div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
			<div id="j-main-container" class="j-main-container">

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'PreparedButNotReady')); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'PreparedButNotReady', Text::_('Create images', true)); ?>
                <p></p>
                <legend><strong><?php
                        // echo Text::_('COM_RSGALLERY2_MAINT_PREPARED_NOT_READY_DESC');
                        echo 'Create images for testing purposes: Use button above, no further functionality';

                        ?></strong></legend>
                <p><h3><?php
                    // echo Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW');
                    ?></h3></p>

                <?php

					try
					{

                       // ?> <h1>---</h1> <?php

					}
					catch (\RuntimeException $e)
					{
						$OutTxt = '';
						$OutTxt .= 'Error rawEdit view: "' . 'PreparedButNotReady' . '"<br>';
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


