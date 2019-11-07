<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('stylesheet', 'com_rsgallery2/upload.css', array('version' => 'auto', 'relative' => true));

HTMLHelper::_('behavior.core');

Text::script('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL');

HTMLHelper::_('behavior.tabstate');

$app = Factory::getApplication();

$tabs = []

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=upload'); ?>"
      method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"
      class="form-validate form-horizontal">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="col-md-2">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>

		<div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
			<div id="j-main-container" class="j-main-container">

				<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => $tabs[0]['name'] ?? '']); ?>
				<?php // Show installation tabs ?>
				<?php foreach ($tabs as $tab) : ?>
					<?php echo HTMLHelper::_('uitab.addTab', 'myTab', $tab['name'], $tab['label']); ?>
                    <fieldset class="uploadform option-fieldset options-grid-form-full">
						<?php echo $tab['content']; ?>
                    </fieldset>
					<?php echo HTMLHelper::_('uitab.endTab'); ?>
				<?php endforeach; ?>
				<?php if (!$tabs) : ?>
					<?php $app->enqueueMessage(Text::_('COM_INSTALLER_NO_INSTALLATION_PLUGINS_FOUND'), 'warning'); ?>
				<?php endif; ?>


				<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'ftp', Text::_('COM_INSTALLER_MSG_DESCFTPTITLE')); ?>
				<?php echo $this->loadTemplate('ftp'); ?>
				<?php echo HTMLHelper::_('uitab.endTab'); ?>

				<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

            </div>
		</div>
	</div>

    <input type="hidden" name="installtype" value="">
    <input type="hidden" name="task" value="install.install">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>



