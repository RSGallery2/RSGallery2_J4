<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;

//HTMLHelper::_('behavior.core');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive'); // On long waiting ...  or ToDo: on post forms like edit otherwise ...

HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/upload.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_rsgallery2/upload.js', ['version' => 'auto', 'relative' => true]);

Text::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST', true);

// Drag and Drop security id on ajax call.
$script[] = 'var Token = \'' . Session::getFormToken() . '\';';

// Factory::getDocument()->addScriptDeclaration(implode("\n", $script));
$app = Factory::getApplication();
$app->getDocument()->addScriptDeclaration(implode("\n", $script));

$tabs = [];

//$maxSize = min($this->UploadLimit, $this->PostMaxSize);
$maxSize = $this->UploadLimit;

?>
<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=upload'); ?>"
      method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"
      class="form-validate form-horizontal">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>

		<div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
			<fieldset id="j-main-container" class="j-main-container">
                <?php
                echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'upload_gallery_must_exist']);
                ?>
                <?php if (!$this->is1GalleryExisting) : ?>
	                <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'upload_gallery_must_exist', Text::_('COM_RSGALLERY2_DO_UPLOAD')); ?>

                    <div class="form-actions">
                        <div style="width: 150px; background-color: lightgrey; text-align: center; padding: 20px">
                            <label for="ToGallery"
                                   style="padding-bottom: 20px"
                                   class="control-label"><?php echo Text::_('COM_RSGALLERY2_ONE_GALLERY_MUST_EXIST'); ?></label>
                            <a class="btn btn-primary"
                               id="ToGallery"
                               class="input_box"
                               title="<?php echo Text::_('COM_RSGALLERY2_CREATE_GALLERY_DESC'); ?>"
                               href="<?php echo Route::_('index.php?option=com_rsgallery2&amp;task=gallery.add');?>">
                                <i class="icon-images"></i>
		                        <?php echo Text::_('COM_RSGALLERY2_CREATE_GALLERY'); ?>
                            </a>
                        </div>
                    </div>

					<?php echo HTMLHelper::_('uitab.endTab'); ?>
	                <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

				<?php else : ?>

                    <!--legend><?php echo Text::_('COM_RSGALLERY2_UPLOAD_BY_DRAG_AND_DROP_LABEL'); ?></legend-->
                    <h2>
                        <span class="mb-2">
                            <?php echo Text::_('COM_RSGALLERY2_UPLOAD_BY_DRAG_AND_DROP_LABEL'); ?>
                        </span>
                    </h2>

                    <?php
                    // specify gallery
                    // toDO: change name as used for all
                    echo $this->form->renderFieldset('upload_gallery');
	                ?>

                    <?php
                    /*---------------------------------------------------------------------------
                    Drag and drop
                    ---------------------------------------------------------------------------*/
                    ?>
                    <fieldset class="uploadform">

                        <div id="uploader-wrapper">
                            <div id="dragarea" data-state="pending">
                                <div id="dragarea-content" class="text-center">
                                    <div id="imagesArea" class="imagesArea">
                                        <ul id="imagesAreaList" class='thumbnails'>

                                        </ul>
                                    </div>
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

                                    <div class="upload-actions">
                                        <p class="lead">
                                            <?php echo Text::_('COM_RSGALLERY2_DRAG_IMAGES_HERE'); ?>
                                        </p>
                                        <p>
                                            <button id="select-file-button-drop" type="button" class="btn btn-info btn-rsg2 btn-file w-25"
                                                    title="<?php echo Text::_('COM_RSGALLERY2_SELECT_FILES_DESC'); ?>"
                                                    disabled
                                            >
                                                <span class="icon-copy" aria-hidden="true"></span>
			                                    <?php echo Text::_('COM_RSGALLERY2_SELECT_FILES'); ?>
                                            </button>
                                        </p>
                                        <p>
                                            <button id="select-zip-file-button-drop" type="button" class="btn btn-warning btn-rsg2 btn-zip w-25"
                                                    title="<?php echo Text::_('COM_RSGALLERY2_SELECT_ZIP_FILE_DESC'); ?>"
                                                    disabled
                                            >
                                                <span class="icon-contract-2" aria-hidden="true"></span>
			                                    <?php echo Text::_('COM_RSGALLERY2_SELECT_ZIP_FILE'); ?>
                                            </button>
                                        </p>
                                        <hr>
                                        <p>
                                            <button id="ftp-upload-folder-button-drop" type="button" class="btn btn-secondary btn-rsg2 btn-folder w-25"
                                                    title="<?php echo Text::_('COM_RSGALLERY2_FTP_FOLDER_UPLOAD_DESC'); ?>"
                                                    disabled
                                            >
                                                <span class="icon-arrow-up-2" aria-hidden="true"></span>
                                                <?php echo Text::_('COM_RSGALLERY2_FTP_FOLDER_UPLOAD'); ?>
                                            </button>
                                            <div class="form-group">
                                                <label for="ftp_upload_directory"><?php echo Text::_('COM_RSGALLERY2_PATH'); ?>: </label>
                                                <input type="text" id="ftp_upload_directory" name="ftp_upload_directory" class="w-50 h-100 mx-auto"
                                                    value="<?php echo $this->FtpUploadPath;?>"
                                                >
                                            </div>
                                        </p>
                                        <hr>
                                        <p>
                                            <?php echo Text::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $this->PostMaxSize); ?> MB
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <button id="AssignUploadedFiles" type="button"
                                    class="btn btn-primary mx-auto mt-2"
                                    onclick="Joomla.submitAssign2DroppedFiles()"
                                    title="<?php echo Text::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES_DESC'); ?>"
                                    disabled
                                >
                                <span class="icon-copy" aria-hidden="true"></span>
		                        <?php echo Text::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES'); ?>
                            </button>

                            <p>
                                <div id="uploadProgressArea"></div>
                            </p>
                            <p>
                                <div id="uploadErrorArea"></div>
                            </p>
                        </div>
                        <div id="hidden-input-buttons" style="display: none;">
                            <div class="control-group">
                                <label for="input_files" class="control-label"><?php echo Text::_('PLG_INSTALLER_PACKAGEINSTALLER_EXTENSION_PACKAGE_FILE'); ?></label>
                                <div class="controls">
                                    <input class="form-control-file" id="input_files" name="input_files" type="file" multiple="multiple" >
                                    <small class="form-text text-muted"><?php echo Text::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $maxSize); ?></small>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="input_zip" class="control-label"><?php echo Text::_('PLG_INSTALLER_PACKAGEINSTALLER_EXTENSION_PACKAGE_FILE'); ?></label>
                                <div class="controls">
                                    <input class="form-control-file" id="input_zip" name="input_zip" type="file" multiple="multiple" >
                                    <small class="form-text text-muted"><?php echo Text::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $maxSize); ?></small>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <?php
                    LimitsAndMaxInfo ($this->UploadLimit, $this->PostMaxSize, $this->MemoryLimit)
                    ?>
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
					<?php echo Text::sprintf('COM_RSGALLERY2_UPLOAD_LIMIT_IS', $UploadLimit) . ' ' . Text::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                </small>
            </div>
            <div>
                <small class="help-block" style="color:darkred;">
					<?php echo Text::sprintf('COM_RSGALLERY2_POST_MAX_SIZE_IS', $PostMaxSize) . ' ' . Text::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                </small>
            </div>
            <div>
                <small class="help-block" style="color:darkred;">
					<?php echo Text::sprintf('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS', $MemoryLimit) . ' ' . Text::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
                </small>
            </div>
        </div>
    </div>

	<?php
    /**/
    ?>
    <hr>
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
                <?php echo Text::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI'); ?>
            </small>
        </div>
    </div>
	<?php

    /**
    // use footnote ? -> or display none and on hover display: block
    //                        <label for="ToGallery"
    //                               style="padding-bottom: 20px"
    //                               class="control-label"><?php echo Text::_('COM_RSGALLERY2_ONE_GALLERY_MUST_EXIST'); ?></label>
    /**/
	$uploadMaxsTitle = ""
        . Text::sprintf('COM_RSGALLERY2_UPLOAD_LIMIT_IS', $UploadLimit)
        . Text::sprintf('COM_RSGALLERY2_POST_MAX_SIZE_IS', $PostMaxSize)
        . Text::sprintf('COM_RSGALLERY2_POST_MEMORY_LIMIT_IS', $MemoryLimit)
        . Text::_('COM_RSGALLERY2_MEGABYTES_SET_IN_PHPINI')
    ;
	/**/
	echo '<div title="' . $uploadMaxsTitle . '" >ToDo: Make /Maximum/ Element with title in hover</div>';
	/**/
}


