<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2023 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('bootstrap.framework');

//HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/imagesProperties.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/backend/imagesProperties.js', ['version' => 'auto', 'relative' => true]);
//$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

//--- load additional language file --------------------------------------
//$lang = JFactory::getLanguage();
//$extension = 'com_helloworld';
//$base_dir = JPATH_SITE;
//$language_tag = 'en-GB';
//$reload = true;
//$lang->load($extension, $base_dir, $language_tag, $reload);


// file paths are expected to be sub media rsgallery2 or media root (check on read) ? url ?

$name='path file name';
$value='';

$exifDataOfFiles = $this->exifDataOfFiles;


?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=maintenance&layout=checkimageexif'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="d-flex flex-row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>

        <!--div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
        <div class="flex-fill">
			<div id="j-main-container" class="j-main-container">


				<div class="d-flex flex-row">
					<div class="flex-fill">
						<div id="j-main-container" class="j-main-container">
							<div>

<!--								<table class="table table-striped w-auto" id="imageFileList">-->
								<table class="table w-auto" id="imageFileList">

									<caption id="captionTable" class="sr-only">
										<?php echo Text::_('COM_RSGALLERY2_CHECK_IMAGE_EXIF_LIST'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
									</caption>
									<thead>
										<tr>
											<th scope="col" >
												<?php echo HTMLHelper::_('grid.checkall'); ?>
											</th>

											<th scope="col" class="text-center">
												Idx
											</th>

											<th scope="col" class="text-center">
												Gallery Id
											</th>

											<th scope="col" class="w-100 text-left">
												Filename
											</th>
										</tr>
									</thead>

									<tbody>
										<?php

										foreach ($this->exifImageFiles as $i => $exifImageFile)
										{
											$galleryId = $exifImageFile [0];
											$imageName = $exifImageFile [1];

											$selected = false;
											//if (in_array($idx, $cids)) {
											if (true) {
												$selected = true;
											}

											?>
											<tr class="table-secondary"></tr>
												<th scope="row">
													<?php echo HTMLHelper::_('grid.id', $i, $i, false, ); ?>
												</th>

											<td class="text-center mb-3 ">
												<div>
													<?php echo $i ?>
												</div>
											</td>

											<td class="text-center">
													<div class="input-group mb-3 ">
														<input id="jform_gal_<?php echo $galleryId ?>" class="form-control w-25 " type="text"
														       value="<?php echo $galleryId ?>" size="25" name="jform[galIds][] aria-invalid=" false">
													</div>
												</td>

												<td class="text-center">

													<div class="input-group mb-3 w-100">
														<input id="jform_file_<?php echo $imageName ?>" class="form-control" type="text"
															       value="<?php echo $imageName ?>" size="75" name="jform[imgNames][] aria-invalid=" false">
													</div>

												</td>


											</tr>
											<?php
		                                }
		                            ?>
									</tbody>
								</table>


							</div>
						</div>
					</div>
				</div>

				<?php if (empty($exifDataOfFiles)) : ?>
					* Exif data missing: do start with button above or

				<?php else: ?>


					<?php

					if ( ! empty ($exifDataOfFiles)) {
						foreach ($exifDataOfFiles as $idx => $exifDataOfFile) {

							// not solved empty one item on not existing file
//							$test1 = count ($exifDataOfFile);
//							$test2 = $exifDataOfFile[0];
//							$test3 = $exifDataOfFile[1];
//							$test4 = $exifDataOfFile;

							if ( ! empty ($exifDataOfFile[0])) {
								$fileName = $exifDataOfFile[0];
							} else {
								$fileName = '%unknown error for filename%';
							}

							echo '<hr>';
							echo '$idx: ' . $idx . '  ';
							echo 'Filename: ' . $fileName . '<br>';

							if ( ! empty ($exifDataOfFile[1])) {
								$exifData = $exifDataOfFile[1];

								echo '<pre>' . json_encode($exifData, JSON_PRETTY_PRINT) . '</pre><br>';
							}
						}
					}

//					$json_beautified = str_replace(array("{", "}", '","'), array("{<br />&nbsp;&nbsp;&nbsp;&nbsp;", "<br />}", '",<br />&nbsp;&nbsp;&nbsp;&nbsp;"'), $exifDataOfFiles);
//					echo $json_beautified;
//					echo $exifDataOfFiles;
//					echo json_encode($exifDataOfFiles [0][0], JSON_PRETTY_PRINT) . '<br>';
//					echo '<hr>';
//					echo json_encode($exifDataOfFiles[0][1], JSON_PRETTY_PRINT) . '<br>';
//					echo '<hr>';

					//--- $this->exifIsNotSupported --------------------------------------------------------------------------------

					echo '<hr>';
					echo '<h2>' . '*Tags not supported' .  '</h2>';

					if ( ! empty ($this->exifIsNotSupported)) {

						foreach ($this->exifIsNotSupported as $notSupported) {

							echo $notSupported . '<br>';
						}
					}


					//--- tags by user --------------------------------------------------------------------------------

					echo '<hr>';
					echo '<h2>' . '*Tags selected by user' .  '</h2>';

					if ( ! empty ($this->exifUserSelected)) {

						foreach ($this->exifUserSelected as $exifUserSelected) {

							echo $exifUserSelected . '<br>';
						}
					}


//					//--- this->exifIsNotUserSelected --------------------------------------------------------------------------------
//
//					echo '<hr>';
//					echo '<h2>' . '*Tags not supported' .  '</h2>';


					//--- collected tags --------------------------------------------------------------------------------

					echo '<hr>';
					echo '<h2>' . '*Tags collected by selected files' .  '</h2>';

					if ( ! empty ($this->exifAllTagsCollected)) {

						foreach ($this->exifAllTagsCollected as $exifItem) {

							echo $exifItem . '<br>';
						}
					}


					//--- supported tags --------------------------------------------------------------------------------

					echo '<hr>';
					echo '<h2>' . '*Tags supported' .  '</h2>';

					if ( ! empty ($this->exifTagsSupported)) {

						foreach ($this->exifTagsSupported as $exifItem) {

							echo $exifItem . '<br>';
						}
					}


					//--- supported tags translation tags ----------------------------------------------------------------------

					echo '<hr>';
					echo '<h2>' . '*Tags translation ID of supported ' .  '</h2>';

					if ( ! empty ($this->exifTagsTranslationIds)) {

						foreach ($this->exifTagsTranslationIds as $exifItem) {

							echo Text::_( $exifItem) . '<br>';
						}
					}


					?>

				<?php endif; ?>


<!--				--><?php //echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'PreparedButNotReady')); ?>
<!---->
<!--				--><?php //echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'PreparedButNotReady', Text::_('COM_RSGALLERY2_MAINT_PREPARED_NOT_READY', true)); ?>
<!--<!--                <p></p>-->-->
<!--<!--                <legend><strong>-->--><?php ////echo Text::_('COM_RSGALLERY2_MAINT_PREPARED_NOT_READY_DESC'); ?><!--<!--</strong></legend>-->-->
<!--<!--                <p><h3>-->--><?php ////echo Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW'); ?><!--<!--</h3></p>-->-->
<!---->
<!---->
<!--				<div class="control-group">-->
<!--					<div class="control-label">-->
<!--						<label id="jform_--><?php //echo $name ?><!---lbl" class="jform_control-label"-->
<!--						       for="jform_--><?php //echo $name ?><!--">--><?php //echo $name ?><!--:</label>-->
<!--					</div>-->
<!--					<div class="controls">-->
<!--						<input id="jform_--><?php //echo $name ?><!--" class="input-xxlarge input_box" type="text"-->
<!--						       value="--><?php //echo $value ?><!--" size="70" name="jform[--><?php //echo $name ?><!--] aria-invalid=" false">-->
<!--					</div>-->
<!--				</div>-->
<!---->
<!---->
<!---->
<!---->
<!---->
<!--				--><?php
//
//					try
//					{
//
//
//
//
//					}
//					catch (\RuntimeException $e)
//					{
//						$OutTxt = '';
//						$OutTxt .= 'Error rawEdit view: "' . 'PreparedButNotReady' . '"<br>';
//						$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//						$app = Factory::getApplication();
//						$app->enqueueMessage($OutTxt, 'error');
//					}
//
//				?>
<!---->
<!--				--><?php //echo HTMLHelper::_('bootstrap.endTab'); ?>
<!---->
<!--				--><?php //echo HTMLHelper::_('bootstrap.endTabSet'); ?>
<!---->
<!--				<!--input type="hidden" name="option" value="com_rsgallery2" />-->
<!--				<input type="hidden" name="rsgOption" value="maintenance" /-->-->

				<input type="hidden" name="task" value="" />
				<?php echo HTMLHelper::_('form.token'); ?>

			</div>
		</div>
	</div>

	<?php echo HTMLHelper::_('form.token'); ?>
</form>


