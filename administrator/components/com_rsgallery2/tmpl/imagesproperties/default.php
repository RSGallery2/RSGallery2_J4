<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
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


HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/imagesProperties.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_rsgallery2/backend/imagesProperties.js', ['version' => 'auto', 'relative' => true]);

Text::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST', true);




$extension = $this->escape($this->state->get('filter.extension'));

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images'); ?>"
       method="post" name="adminForm" id="adminForm">

		<div class="d-flex flex-row">
			<?php if (!empty($this->sidebar)) : ?>
				<div id="j-sidebar-container" class="">
					<?php echo $this->sidebar; ?>
				</div>
			<?php endif; ?>
            <!--div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
            <div class=" p2">
				<div id="j-main-container" class="j-main-container">

					<legend><?php echo Text::_('COM_RSGALLERY2_PROPERTIES_UPLOADED_IMAGES'); ?></legend>

					<?php if (empty($this->items)) : ?>
						<div class="alert alert-no-items">
							<?php echo Text::_('COM_RSGALLERY2_NO_IMAGES_SELECTED_FOR_VIEW'); ?>
						</div>
					<?php else : ?>

                        <span class="">
                            <?php
                            echo  HTMLHelper::_('grid.checkall');
                            echo  " ";
                            echo  Text::_("COM_RSGALLERY2_SELECT_ALL");
                            ?>
                        </span>
                        <br><br>

                        <ul class="thumbnails">
							<?php
							$Idx = 0;

							foreach ($this->items as $Idx => $item)
							{
							    //-- path to display image ------------------------------------

                                //$src   = $this->HtmlPathDisplay . $this->escape($item->name) . '.jpg';

                                // galleryJ4x path is depending on gallery id
                                $this->ImagePath->setPathsURIs_byGalleryId($item->gallery_id);
                                $src = $this->ImagePath->getDisplayUrl ($item->name);

								?>
								<li class="imageAreaItem" >
									<div class="thumbnail imgProperty">
										<div class='imgContainer'>
											<img src="<?php echo $src; ?>" class="img-rounded modalActive" alt="<?php echo $this->escape($item->name);?>">
										</div>

										<div class="caption" >
											<?php echo HTMLHelper::_('grid.id', $Idx, $item->id, false, 'sid'); ?>
											<small>&nbsp;<?php echo $this->escape($item->name);?>&nbsp;(ID: <?php echo $this->escape($item->id);?>)</small><br>
										</div>

										<div class="control-group">
											<label class="control-label" for="title[]"><?php echo Text::_('COM_RSGALLERY2_TITLE'); ?></label>
											<div class="controls">
												<input name="title[]" type="text" size="15" aria-invalid="false"
												       value="<?php echo $this->escape($item->title);?>"
												       style="width:95%;>
                                        </div>
                                    </div>

                                    <!-- Gallery can't be changed. Disable input -->
                                    <div class="control-group">
											</div>
											<label class="control-label" for="galleryID[]" ><?php echo Text::_('COM_RSGALLERY2_GALLERY'); ?></label>
											<div class="controls">
												<input type="text" name="galleryID[]" placeholder="Idx:"
												       value="<?php echo $this->escape($item->gallery_name);?>"
												       disabled style="width:95%;>
                                        </div>
                                    </div>

                                    <div class="control-group">
												<!-- label class="control-label" for="description2[]" ><?php echo Text::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
                                                <div class="controls">
                                                <textarea cols="15" rows="" name="description[]"
                                                  placeholder="Text input"
                                                  style="width:95%;"><?php echo $this->escape($item->descr);?></textarea>
                                                </div-->

												<label class="control-label" for="description[]" ><?php echo Text::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
												<div class="controls">
													<?php
													if ( ! empty($this->editor))
													{
														// ToDo: Leave out some editor buttons : use config ...
														echo $this->editor->display(
														        'description[]',
                                                                $this->escape($item->description), '
                                                                90%', '100', '20', '20',
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

								<?php
							}
							?>
						</ul>

						<div id="popupModal" class="Xmodal">
							<span id="popupClose" class="close">&times;</span>
							<img  id="popupImage" class="modal-content">
							<div id="popupCaption"></div>
						</div>


					<?php endif; ?>

				</div>

				<input type="hidden" name="extension" value="<?php echo $extension; ?>">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="boxchecked" value="0">
				<?php echo HTMLHelper::_('form.token'); ?>

			</div>

		</div>
	</div>
</form>



