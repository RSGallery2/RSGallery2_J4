<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\String\Inflector;

$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

Text::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST', true);

// 2025.01.10 ToDo: followin is not working do we need  'extension' ?
// $extension = $this->escape($this->state->get('filter.extension'));

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=imagesproperties'); ?>"
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

				<legend></legend>
				<h2>
                        <span class="mb-2">
					<?php echo Text::_('COM_RSGALLERY2_PROPERTIES_UPLOADED_IMAGES'); ?>
                        </span>
				</h2>

                <?php if (empty($this->items)) : ?>
					<div class="alert alert-no-items">
                        <?php echo Text::_('COM_RSGALLERY2_NO_IMAGES_SELECTED_FOR_VIEW'); ?>
					</div>
                <?php else : ?>

					<span class="">
                            <?php echo HTMLHelper::_('grid.checkall') .  " " .  Text::_("COM_RSGALLERY2_SELECT_ALL"); ?>
                        </span>
					<br><br>

					<ul class="imagesPropArea">
                        <?php
                        $Idx = 0;

                        foreach ($this->items as $Idx => $item) {
                            //-- path to display image ------------------------------------

                            // toDo: Move to "htmlview-> create list in model
                            // galleryJ4x path is depending on gallery id

                            if (!$item->use_j3x_location) {
                                $this->ImagePath->setPaths_URIs_byGalleryId($item->gallery_id);
                                $src = $this->ImagePath->getDisplayUrl($item->name);
                            } else {
                                $src = $this->ImagePathJ3x->getDisplayUrl($item->name);
                            }

                            ?>
							<li class="imagePropItem">
								<div class=" imgProperty">
									<div class='imgContainer'>
										<img src="<?php echo $src; ?>" class="img-rounded modalActive" alt="<?php echo $this->escape($item->name); ?>">
									</div>

									<div class="caption">
                                        <?php echo HTMLHelper::_('grid.id', $Idx, $item->id, false, 'sid'); ?>
										<small>&nbsp;<?php echo $this->escape($item->name); ?>&nbsp;(ID: <?php echo $this->escape($item->id); ?>)</small><br>
									</div>

									<div class="control-group">
										<label class="control-label" for="title[]"><?php echo Text::_('COM_RSGALLERY2_TITLE'); ?></label>
										<div class="controls">
											<input name="title[]" type="text" size="15" aria-invalid="false"
											       value="<?php echo $this->escape($item->title); ?>"
												       style="width:95%;>
                                        </div>
                                    </div>

                                    <!-- Gallery can not be changed. Disable input -->
									<div class="control-group">
										<label class="control-label" for="galleryID[]"><?php echo Text::_('COM_RSGALLERY2_GALLERY'); ?></label>
										<div class="controls">
											<input type="text" name="galleryID[]" placeholder="Idx:"
											       value="<?php echo $this->escape($item->gallery_name); ?>"
											       disabled style="width:95%;"
											/>
                                        </div>
                                    </div>

                                    <div class=" control-group">
												<!-- label class="control-label" for="description2[]" ><?php echo Text::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
                                        <div class="controls">
                                            <textarea cols="15" rows="" name="description[]"
	                                              placeholder="Text input"
                                                  style="width:95%;"><?php echo $this->escape($item->descr);?></textarea>
                                         </div-->

										<label class="control-label" for="description[]"><?php echo Text::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
										<div class="controls">
                                            <?php
                                            if (!empty($this->editor)) {
                                                // ToDo: Leave out some editor buttons : use config ...
                                                echo $this->editor->display(
                                                    'description[]',
                                                                $this->escape($item->description), 
                                                                '90%', '100', '20', '20',
                                                    false,
                                                                'description_' . $Idx, null, null,
                                                                $this->editorParams);
                                            }
                                            ?>
										</div>
									</div>

											<input type="hidden" name="cid[]" value="<?php echo $item->id;?>">
								</div>
							</li>

						<?php } ?>
					</ul>

					<div id="popupModal" class="Xmodal">
						<span id="popupClose" class="close">&times;</span>
						<img id="popupImage" class="modal-content">
						<div id="popupCaption"></div>
					</div>


                <?php endif; ?>

			</div>

			<!--
	        // 2025.01.10 ToDo: following is not working do we need  'extension' ?
			input type="hidden" name="extension" value="<?php // echo $extension; ?>" /
			-->
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
            <?php echo HTMLHelper::_('form.token'); ?>

		</div>

	</div>
</form>



