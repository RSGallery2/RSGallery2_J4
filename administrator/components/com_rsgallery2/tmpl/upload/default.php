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

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
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
                <?php
                echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'upload_gallery_must_exist']);
                ?>
                <?php if ($this->is1GalleryExisting) : ?>
	                <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'upload_gallery_must_exist', Text::_('COM_RSGALLERY2_DO_UPLOAD')); ?>

                    <div class="form-actions">
                        <p style="width: 150px; text-align: center">
                        <label for="ToGallery"
                               class="control-label"><?php echo JText::_('COM_RSGALLERY2_ONE_GALLERY_MUST_EXIST'); ?></label>
                        </p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-primary"
                           name="ToGallery"
                           class="input_box"
                           title="<?php echo JText::_('COM_RSGALLERY2_GOTO_RSG2_GALLERY_VIEW'); ?>"
                           href="index.php?option=com_rsgallery2&amp;view=galleries">
							<?php echo JText::_('COM_RSGALLERY2_MENU_GALLERIES'); ?>
                        </a>
                        </p>
                    </div>

					<?php echo HTMLHelper::_('uitab.endTab'); ?>
	                <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

				<?php else : ?>

                    <?php
                    echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'upload_zip_pc']);
                    ?>

                    <?php
                    /*---------------------------------------------------------------------------
                    Drag and drop
                    ---------------------------------------------------------------------------*/

                    /**/

                    ?>
                    <?php
                    /**
                    echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => $tabs[0]['name'] ?? '']);
                    // Show installation tabs
                     foreach ($tabs as $tab) :
                         echo HTMLHelper::_('uitab.addTab', 'myTab', $tab['name'], $tab['label']);
                        <fieldset class="uploadform option-fieldset options-grid-form-full">
                             echo $tab['content'];
                        </fieldset>
                         echo HTMLHelper::_('uitab.endTab');
                     endforeach;
                     if (!$tabs) :
                         $app->enqueueMessage(Text::_('COM_INSTALLER_NO_INSTALLATION_PLUGINS_FOUND'), 'warning');
                     endif;
                    //echo $this->loadTemplate('ftp');

                    /**/
                    ?>
                    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'upload_drag_and_drop', Text::_('COM_RSGALLERY2_UPLOAD_BY_DRAG_AND_DROP')); ?>
                    <fieldset class="uploadform">
                        <legend><?php echo Text::_('COM_RSGALLERY2_UPLOAD_BY_DRAG_AND_DROP_LABEL'); ?></legend>

                        <div id="uploader-wrapper">
                            <div id="dragarea" data-state="pending">
                                <div id="dragarea-content" class="text-center">
                                    <p>
                                        <span id="upload-icon" class="icon-upload" aria-hidden="true"></span>
                                    </p>
                                    <div id="upload-progress" class="upload-progress">
                                        <div class="progress progress-striped active">
                                            <div class="bar bar-success"
                                                 style="width: 0;"
                                                 role="progressbar"
                                                 aria-valuenow="0"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100"
                                            >
                                            </div>
                                        </div>
                                        <p class="lead">
                                            <span class="uploading-text">
                                                <?php echo Text::_('PLG_INSTALLER_PACKAGEINSTALLER_UPLOADING'); ?>
                                            </span>
                                            <span class="uploading-number">0</span><span class="uploading-symbol">%</span>
                                        </p>
                                    </div>
                                    <div class="install-progress">
                                        <div class="progress progress-striped active">
                                            <div class="bar" style="width: 100%;"></div>
                                        </div>
                                        <p class="lead">
                                            <span class="installing-text">
                                                <?php echo Text::_('PLG_INSTALLER_PACKAGEINSTALLER_INSTALLING'); ?>
                                            </span>
                                        </p>
                                    </div>


                                    <div class="upload-actions">
                                        <p class="lead">
                                            <?php echo Text::_('COM_RSGALLERY2_DRAG_IMAGES_HERE'); ?>
                                        </p>
                                        <p>
                                            <button id="select-file-button" type="button" class="btn btn-success">
                                                <span class="icon-copy" aria-hidden="true"></span>
                                                <?php echo Text::_('COM_RSGALLERY2_SELECT_FILES_ZIP_DESC'); ?>
                                            </button>
                                        </p>
                                        <p>
                                            <?php echo Text::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $this->PostMaxSize); ?>
                                        </p>
                                    </div>

                                    <?php



                        // Specify gallery,  all in one
    //					echo $this->form->renderFieldset('upload_drag_and_drop');
                        LimitsAndMaxInfo ($this->UploadLimit, $this->PostMaxSize, $this->MemoryLimit)
                        ?>

                    <?php echo HTMLHelper::_('uitab.endTab'); ?>

                    <?php
                    /*---------------------------------------------------------------------------
                    Zip upload
                    ---------------------------------------------------------------------------*/
                    ?>
                    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'upload_zip_pc', Text::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP')); ?>
                        <legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP_FROM_LOCAL_PC'); ?></legend>
	                    <?php
	                    // Specify gallery,  all in one
	                    //					echo $this->form->renderFieldset('upload_drag_and_drop');
	                    LimitsAndMaxInfo ($this->UploadLimit, $this->PostMaxSize, $this->MemoryLimit)
	                    ?>

                    <?php echo HTMLHelper::_('uitab.endTab'); ?>

                    <?php
                    /*---------------------------------------------------------------------------
                    Server folder upload
                    ---------------------------------------------------------------------------*/
                    ?>
                    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'upload_folder_server', Text::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_SERVER')); ?>
                        <legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_PATH_ON_SERVER'); ?></legend>
                    <?php echo HTMLHelper::_('uitab.endTab'); ?>

                    <?php
                    // Specify gallery,  all in one
                    //					echo $this->form->renderFieldset('upload_drag_and_drop');
                    //LimitsAndMaxInfo ($this->UploadLimit, $this->PostMaxSize, $this->MemoryLimit)
                    ?>

                    <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
                <?php endif; ?>
            </div>
		</div>
	</div>

    <input type="hidden" name="installtype" value="">
    <input type="hidden" name="task" value="install.install">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php

function LimitsAndMaxInfo ($UploadLimit, $PostMaxSize, $MemoryLimit)
{
    /**
    ?>
    <div class="control-group">
        <div class="controls">
            <div>
                <!--small class="help-block" style="color:darkred;"-->
                <small style="color:darkred;">
					<?php echo Text::sprintf('COM_RSGALLERY2_UPLOAD_LIMIT_IS', $UploadLimit) . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                </small>
            </div>
            <div>
                <small class="help-block" style="color:darkred;">
					<?php echo Text::sprintf('COM_RSGALLERY2_POST_MAX_SIZE_IS', $PostMaxSize) . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                </small>
            </div>
            <div>
                <small class="help-block" style="color:darkred;">
					<?php echo Text::sprintf('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS', $MemoryLimit) . ' ' . JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                </small>
            </div>
        </div>
    </div>

	<?php
    /**/
    ?>
    <div style="display-box">
        <!--small class="help-block" style="color:darkred;"-->
        <small style="color:darkred;">
            <?php echo Text::sprintf('COM_RSGALLERY2_UPLOAD_LIMIT_IS', $UploadLimit); ?>
        </small>
        <div>
            <small class="help-block" style="color:darkred;">
                <?php echo Text::sprintf('COM_RSGALLERY2_POST_MAX_SIZE_IS', $PostMaxSize); ?>
            </small>
        </div>
        <div>
            <small class="help-block" style="color:darkred;">
                <?php echo Text::sprintf('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS', $MemoryLimit); ?>
            </small>
        </div>
        <div>
            <small class="help-block" style="color:darkred;">
                <?php echo JText::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
            </small>
        </div>
    </div>
	<?php
}

