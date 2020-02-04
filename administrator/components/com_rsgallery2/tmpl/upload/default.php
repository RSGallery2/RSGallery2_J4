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
use Joomla\CMS\Session\Session;

HTMLHelper::_('behavior.core');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

HTMLHelper::_('stylesheet', 'com_rsgallery2/upload.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/libQueueStack.js', ['version' => 'auto', 'relative' => true]);
HTMLHelper::_('script', 'com_rsgallery2/upload.js', ['version' => 'auto', 'relative' => true]);

Text::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST');

// Drag and Drop security id on ajax call.
$script[] = 'var Token = \'' . Session::getFormToken() . '\';';
Factory::getDocument()->addScriptDeclaration(implode("\n", $script));

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
                                   class="control-label"><?php echo JText::_('COM_RSGALLERY2_ONE_GALLERY_MUST_EXIST'); ?></label>
                            <a class="btn btn-primary"
                               id="ToGallery"
                               class="input_box"
                               title="<?php echo JText::_('COM_RSGALLERY2_CREATE_GALLERY_DESC'); ?>"
                               href="index.php?option=com_rsgallery2&amp;task=gallery.add">
                                <i class="icon-images"></i>
		                        <?php echo JText::_('COM_RSGALLERY2_CREATE_GALLERY'); ?>
                            </a>
                        </div>
                    </div>

					<?php echo HTMLHelper::_('uitab.endTab'); ?>
	                <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

				<?php else : ?>

                    <?php
                    // specify gallery
                    // toDO: change name as used for all
                    echo $this->form->renderFieldset('upload_gallery');
	                ?>

                    <?php
                    echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'upload_drag_and_drop'));
                    ?>

                    <?php
                    /*---------------------------------------------------------------------------
                    Drag and drop
                    ---------------------------------------------------------------------------*/
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

                            <?php

                            // 
                            // echo $this->form->renderFieldset('upload_drag_and_drop');

                            /**/
                            ?>
                        <div id="uploader-wrapper">
                            <div id="dragarea" data-state="pending">
                                <div id="dragarea-content" class="text-center">
                                    <div id="imagesArea" class="span12">
                                        <ul id="imagesAreaList" class='thumbnails'>

                                        </ul>
                                    </div>
                                    <div id="imagesArea">
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
                                    <!--div class="uploadProgressArea">
                                        <div class="progress progress-striped active">
                                            <div class="bar" style="width: 100%;"></div>
                                        </div>
                                        <p class="lead">
                                            <span class="installing-text">
                                                <?php echo Text::_('PLG_INSTALLER_PACKAGEINSTALLER_INSTALLING'); ?>
                                            </span>
                                        </p>
                                    </div-->

                                    <div class="upload-actions">
                                        <p class="lead">
                                            <?php echo Text::_('COM_RSGALLERY2_DRAG_IMAGES_HERE'); ?>
                                        </p>
                                        <p>
                                            <button id="select-file-button-drop" type="button" class="btn btn-info btn-file"
                                                    title="<?php echo Text::_('COM_RSGALLERY2_SELECT_FILES_DESC'); ?>">
                                                <span class="icon-copy" aria-hidden="true"></span>
			                                    <?php echo Text::_('COM_RSGALLERY2_SELECT_FILES'); ?>
                                            </button>
                                        </p>
                                        <p>
                                            <button id="select-zip-file-button-drop" type="button" class="btn btn-warning btn-zip"
                                                    title="<?php echo Text::_('COM_RSGALLERY2_SELECT_ZIP_FILE_DESC'); ?>">
                                                <span class="icon-contract-2" aria-hidden="true"></span>
			                                    <?php echo Text::_('COM_RSGALLERY2_SELECT_ZIP_FILE'); ?>
                                            </button>
                                        </p>
                                        <p>
                                            <button id="ftp-upload-folder-button-drop" type="button" class="btn btn-secondary btn-folder"
                                                    title="<?php echo Text::_('COM_RSGALLERY2_FTP_FOLDER_UPLOAD_DESC'); ?>">
                                                <span class="icon-arrow-up-2" aria-hidden="true"></span>
			                                    <?php echo Text::_('COM_RSGALLERY2_FTP_FOLDER_UPLOAD'); ?>
                                            </button>
                                        </p>
                                        <p>
                                            <?php echo Text::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', $this->PostMaxSize); ?>
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <button id="AssignUploadedFiles" type="button"
                                    class="btn btn-primary"
                                    onclick="Joomla.submitAssign2DroppedFiles()"
                                    title="<?php echo JText::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES_DESC'); ?>"
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
                        <div id="legacy-uploader" style="display: none;">
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
                            <div class="form-actions">
                                <button class="btn btn-primary" type="button" id="installbutton_package">
				                    <?php echo Text::_('PLG_INSTALLER_PACKAGEINSTALLER_UPLOAD_AND_INSTALL'); ?>
                                </button>
                            </div>

                            <input id="installer-return" name="return" type="hidden" value="<?php echo $return; ?>">
                        </div>
                    </fieldset>

                    <?php echo HTMLHelper::_('uitab.endTab'); ?>

                    <?php
                    /*---------------------------------------------------------------------------
                    Zip upload
                    ---------------------------------------------------------------------------*/
                    ?>
                    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'upload_zip_pc', Text::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP')); ?>
                        <legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_PC_ZIP_FROM_LOCAL_PC'); ?></legend>
	                    <?php
	                    // 
	                    //echo $this->form->renderFieldset('upload_zip');
	                /**/
	                ?>
                    <hr>
                    <div class="control-group">
                        <label for="zip_upload_directory" class="control-label">
			                <?php echo Text::_('COM_RSGALLERY2_ZIP_MINUS_FILE'); ?>
                        </label>
                        <div class="controls">
                            <button id="select-file-button-zip" type="button" class="btn btn-success">
                                <span class="icon-copy" aria-hidden="true"></span>
		                        <?php echo Text::_('COM_RSGALLERY2_SELECT_FILES_ZIP_DESC'); ?>
                            </button>
                            <input type="text" id="zip_upload_directory" name="zip_upload_directory" class="form-control"
                                   value="<?php
                                   //echo $app->input->get('install_directory', $app->get('tmp_path'));
                                   echo "d:\...";;
                                   ?>"
                            >
                        </div>
                    </div>
                    <hr>
                    <div class="control-group">
                        <div class="controls">
                            <button type="button" class="btn btn-primary" id="uploadbutton_directory-zip" onclick="XXX_Joomla.submitbuttonfolder()">
				                <?php echo Text::_('COM_RSGALLERY2_CHECK_AND_UPLOAD'); ?>
                            </button>
                        </div>
                    </div>
	                <?php
	                /**/
	                    ?>

                    <?php echo HTMLHelper::_('uitab.endTab'); ?>

                    <?php
                    /*---------------------------------------------------------------------------
                    Server folder upload
                    ---------------------------------------------------------------------------*/
                    ?>
                    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'upload_folder_server', Text::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_SERVER')); ?>
                        <legend><?php echo JText::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER_PATH_ON_SERVER'); ?></legend>

                    <?php
                    // 
                    //echo $this->form->renderFieldset('upload_folder');
                    /**/
                    ?>
                    <hr>
                    <div class="control-group">
                        <label for="ftp_upload_directory" class="control-label">
                            <?php echo Text::_('COM_RSGALLERY2_UPLOAD_FROM_FOLDER'); ?>
                        </label>
                        <div class="controls">
                            <input type="text" id="ftp_upload_directory" name="ftp_upload_directory" class="form-control"
                                   value="<?php echo $app->input->get('install_directory', $app->get('tmp_path')); ?>">
                                <div style="color:red">
                                    ToDo:<br>
                                    Path must start with base path<br>
                                    FTP base path is:  D:\xampp\htdocs\joomla3xNextRelease
                                </div>
                        </div>
                    </div>
                    <hr>
                    <div class="control-group">
                        <div class="controls">
                            <button type="button" class="btn btn-primary" id="uploadbutton_directory-server" onclick="XXX_Joomla.submitbuttonfolder()">
                                <?php echo Text::_('COM_RSGALLERY2_CHECK_AND_UPLOAD'); ?>
                            </button>
                        </div>
                    </div>
                    <?php
                    /**/
                    ?>

                    <?php echo HTMLHelper::_('uitab.endTab'); ?>

                    <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

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
    //                               class="control-label"><?php echo JText::_('COM_RSGALLERY2_ONE_GALLERY_MUST_EXIST'); ?></label>
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


