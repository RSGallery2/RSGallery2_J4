<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (c)  2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

//$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

?>

<form action="<?php
echo Route::_('index.php?option=com_rsgallery2&view=develop&layout=InstallMessage'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

	<!--    <div id="installer-install" class="clearfix">-->
	<div class="d-flex flex-row">
        <?php
        if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
                <?php
                echo $this->sidebar; ?>
			</div>
        <?php
        endif; ?>

		<!--div class="<?php
        echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
		<div class="flex-fill">
			<div id="j-main-container" class="j-main-container">

                <?php
                echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'InstallMessage']); ?>

                <?php
                echo HTMLHelper::_(
                    'bootstrap.addTab',
                    'myTab',
                    'InstallMessage',
                    Text::_('COM_RSGALLERY2_DEVELOP_INSTALL_MSG_TEST', true),
                ); ?>

                <?php

                echo '<br />';
                echo $this->form->renderFieldset('install_message_var');

                ?>

				<!--                    <button id="AssignUploadedFiles" type="button"-->
				<!--                            class="btn btn-primary mx-auto mt-2"-->
				<!--                            onclick="Joomla.submitAssign2DroppedFiles()"-->
				<!--                            title="--><?php
                //echo Text::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES_DESC'); ?><!--"-->
				<!--                            disabled-->
				<!--                        >-->
				<!--                        <span class="icon-copy" aria-hidden="true"></span>-->
				<!--                        --><?php
                //echo Text::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES'); ?>
				<!--                    </button>-->

                <?php

                echo '<br />';

                echo '====================================================================<br />';
                //echo '<br />';
                echo 'Show changelog since ' . $this->lowerVersion . ' and up to ' . $this->Rsg2Version . '<br />';
                echo $this->installMessage2;

                echo '====================================================================<br />';
                //echo '<br />';
                echo 'Show all changelog messages<br />';
                echo $this->installMessage;

                echo '====================================================================<br />';
                ?>

                <?php
                echo HTMLHelper::_('bootstrap.endTab'); ?>

                <?php
                echo HTMLHelper::_('bootstrap.endTabSet'); ?>


				<input type="hidden" value="" name="task">
                <?php
                echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>
