<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

//HTMLHelper::_('bootstrap.framework');

// responsible for moveJ3xImages, dbtransferj3xgalleries, dbtransferj3ximages, dbcopyj3xconfig
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.dbTransferJ3xImages');

function isOKIconHtml($title) {

    $html = <<<EOT
        <div class="btn-group">
            <a class="tbody-icon" href="javascript:void(0);" aria-labelledby="cbpublish1-desc">
                <span class="fas fa-check" aria-hidden="true"/>
            </a>
            <div role="tooltip" id="cbpublish1-desc" style="min-width: 300px max-width: 400% !important;">$title</div>
        </div>
        EOT;

    return $html;
}

function isNotOkIconHtml($title) {

    $html = <<<EOT
        <div class="btn-group">
            <a class="tbody-icon active" href="javascript:void(0);" aria-labelledby="cbunpublish2-desc">
                <span class="fas fa-times" aria-hidden="true"/>
            </a>
            <div role="tooltip" id="cbpublish1-desc" style="min-width: 100% max-width: 100% !important;">$title</div>
        </div>
        EOT;

    return $html;
}

/*--------------------------------------------------------------------------------
	J3x galleries
--------------------------------------------------------------------------------*/
function j3x_galleryListHtml($dbtransferj3ximages) {

//	$html = <<<EOT
    ?>
	<?php if (! empty ($dbtransferj3ximages->j3x_galleriesSorted)): ?>
		<table class="table table-striped" id="j3x_galleryList">

			<caption id="j3x_captionTable" class="sr-only">
                <?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>
				, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
			</caption>
			<thead>
			<tr>
				<td style="width:1%" class="text-center">
                    <?php echo HTMLHelper::_('grid.checkall'); ?>
				</td>

				<th class="text-center">
                    <?php echo Text::_('JSTATUS'); ?>
				</th>
				<th class="text-center">
					`id`
				</th>
				<th width="1%" class="text-center">
					`name/alias/note`
				</th>
				<th class="text-center">
					`images`
				</th>
			</tr>
			</thead>

			<tbody>

            <?php
            $isTransferred = false;
            foreach ($dbtransferj3ximages->j3x_galleriesSorted as $i => $item) {
                // $identHtml = str_repeat('⋮&nbsp;&nbsp;&nbsp;', $item->level);

//				if ($item->isTransferred) {
//					$isMergedHtml =  isOKIconHtml ('Images transferred');
//				} else {
//					$isMergedHtml =  isNotOkIconHtml ('Images not transferred');
//				}
//
                if ($item->j3x_img_count == $item->j4x_img_count) {
                    $isTransferred = true;
                    $isMergedHtml  = isOKIconHtml('Images transferred');
                } else {
                    $isTransferred = false;
                    $isMergedHtml  = isNotOkIconHtml('Images not transferred');
                }

                $attributeGalleryIdHtml = ' gallery_id="' . $item->id . '"';

                if ($isTransferred) {
                    $attributeIsMergedHtml = ' is_merged="true"';
                } else {
                    $attributeIsMergedHtml = ' ';
                }

                ?>
				<tr class="row<?php echo $i % 2; ?>" name="j3x_gal_row" <?php echo $attributeGalleryIdHtml; ?> <?php echo $attributeIsMergedHtml; ?> >

					<td class="text-center">
                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
					</td>

					<td class="text-center">
                        <?php echo $isMergedHtml; ?>
					</td>

					<td class="text-center">
                        <?php echo $item->id; ?>
					</td>

					<td class="text-left">
                        <?php echo $dbtransferj3ximages->escape($item->name); ?>
						<span class="small" title="<?php echo $dbtransferj3ximages->escape($item->path); ?>">
                            <?php if (empty($item->description)) : ?>
                                <?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $dbtransferj3ximages->escape($item->alias)); ?>
                            <?php else : ?>
                                (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $dbtransferj3ximages->escape($item->alias),
                                    $dbtransferj3ximages->escape($item->description)); ?>)
                            <?php endif; ?>
                        </span>
					</td>
					<td class="text-center">
                        <?php
                        // ToDo: Fix SQL where 'Join LEFT' leads to multiplied image count
                        if ($isTransferred) {
                            echo sqrt($item->j3x_img_count);
                        } else {
                            echo $item->j3x_img_count;
                        }
                        ?>
					</td>

				</tr>
                <?php
            }
            ?>
			</tbody>
		</table>

    <?php else : ?>
		<h2><span class="badge badge-pill bg-error"><?php echo Text::_('COM_RSGALLERY2_J3X_GALLERIES_LIST_IS_EMPTY'); ?></span></h2>
    <?php endif; ?>

    <?php

//EOT;
//
//	return $html;
}

/*--------------------------------------------------------------------------------
	J3x images
--------------------------------------------------------------------------------*/
function j3x_imageInfoListHtml($dbtransferj3ximages) {
    $toBeMovedCount = 0;

    ?>
	<?php if (! empty ($dbtransferj3ximages->j3x_images)): ?>

		<table class="table table-striped" id="galleryList">

			<caption id="captionTable" class="sr-only">
                <?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>
				, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
			</caption>
			<thead>
			<tr>
				<td style="width:1%" class="text-center">
                    <?php echo HTMLHelper::_('grid.checkall'); ?>
				</td>

				<th width="1%" class="text-center">
                    <?php echo Text::_('JSTATUS'); ?>
				</th>
				<th width="1%" class="center">
					`id`
				</th>
				<th width="1%" class="center">
					`name`
				</th>
				<th width="1%" class="center">
					`alias`
				</th>
				<th width="1%" class="center">
					`descr`
				</th>
				<th width="1%" class="center">
					`gallery_id`
				</th>
				<th width="1%" class="center">
					`title`
				</th>

			</tr>
			</thead>

			<tbody>

            <?php
            foreach ($dbtransferj3ximages->j3x_images as $i => $item) {
                $isMerged = in_array($item->id, $dbtransferj3ximages->j3x_imageIdsMerged);
                if ($isMerged) {
                    $mergedStatusHtml = isOKIconHtml('Image is merged');
                } else {
                    $mergedStatusHtml = isNotOkIconHtml('Image is not merged');

                    $toBeMovedCount += 1;
                }

                ?>
				<tr class="row<?php echo $i % 2; ?>">

					<td class="text-center">
                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
					</td>

					<td class="text-center">
                        <?php echo $mergedStatusHtml; ?>
					</td>

					<td width="1%" class="center">
                        <?php
                        $link = Route::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);
                        echo '<a href="' . $link . '"">' . $item->id . '</a>';
                        ?>
					</td>
					<td width="1%" class="center">
                        <?php
                        $link = Route::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);
                        echo '<a href="' . $link . '"">' . $item->name . '</a>';
                        ?>
					</td>
					<td width="1%" class="center">
                        <?php echo $item->alias; ?>
					</td>
					<td width="1%" class="center">
                        <?php // echo $item->descr; ?>
					</td>
					<td width="1%" class="center">
                        <?php echo $item->gallery_id; ?>
					</td>
					<td width="1%" class="center">
                        <?php echo $item->title; ?>
					</td>
				</tr>

                <?php
            }
            ?>
			</tbody>
		</table>

    <?php else : ?>
		<h2><span class="badge badge-pill bg-error"><?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_LIST_IS_EMPTY'); ?></span></h2>
    <?php endif; ?>

    <?php

    return $toBeMovedCount;
}

/*--------------------------------------------------------------------------------
	Move buttons
--------------------------------------------------------------------------------*/
function j3xdTransferButtonsHtml($movej3ximages) {

//	$html = <<<EOT

    ?>
	<?php if (! empty ($movej3ximages->j3x_galleriesSorted)): ?>
        <?php
        /*
               <button id="transferByGallery" type="button" class="btn btn-success btn-rsg2"
                       title="<?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_MOVE_BY_GALLERY_DESC'); ?>"

               >
                   <span class="icon-checkbox" aria-hidden="false"></span>
                   <?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_MOVE_BY_GALLERY'); ?>
               </button>

               <!-- button id="transferByCheckedGalleries" type="button" class="btn btn-success btn-rsg2"
                       title="<?php echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_BY_GALLERIES_CHECK_DESC'); ?>"
                       disabled
               >
                   <span class="icon-out-2" aria-hidden="false"></span>
                   <span class="icon-image" aria-hidden="false"></span>
                   <?php echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_BY_GALLERIES_CHECK'); ?>
               </button -->
               <!--button id="deSelectGallery" type="button" class="btn btn-success btn-rsg2"
                       title="<?php echo "???" . Text::_('COM_RSGALLERY2_J3X_IMAGES_DESELECT_BY_GALLERY_DESC'); ?>"
               >
                   <span class="icon-checkbox-unchecked" aria-hidden="false"></span>
                   <?php echo "???" . Text::_('COM_RSGALLERY2_J3X_IMAGES_DESELECT_BY_GALLERY'); ?>
               </button-->

               <button id="transferAllJ3xImjages" type="button" class="btn btn-success btn-rsg2"
                       title="<?php echo Text::_('COM_RSGALLERY2_MOVE_SELECTED_J3X_IMAGES_DESC'); ?>"
               >
                   <span class="icon-out-2" aria-hidden="false"></span>
                   <span class="icon-images" aria-hidden="false"></span>
                   <?php echo Text::_('COM_RSGALLERY2_MOVE_ALL_J3X_IMAGES'); ?>
               </button>

               <hr>
               /**/
        ?>
		<button id="selectNextGallery" type="button" class="btn btn-info btn-rsg2"
		        title="<?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_GALLERY_DESC'); ?>"

		>
			<span class="icon-checkbox" aria-hidden="false"></span>
            <?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_GALLERY'); ?>
		</button>
		<button id="selectNextGalleries10" type="button" class="btn btn-info btn-rsg2"
		        title="<?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_100_GALLERY_DESC'); ?>"

		>
			<span class="icon-checkbox" aria-hidden="false"></span>
            <?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_10_GALLERIES'); ?>
		</button>
		<button id="selectNextGalleries100" type="button" class="btn btn-info btn-rsg2 "
		        title="<?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_100_GALLERY_DESC'); ?>"
		>
			<span class="icon-checkbox" aria-hidden="false"></span>
            <?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_100_GALLERIES'); ?>
		</button>

    <?php else : ?>
		<h2><span class="badge badge-pill bg-success"><?php echo Text::_('COM_RSGALLERY2_J4X_GALLERIES_LIST_IS_EMPTY'); ?></span></h2>
    <?php endif; ?>

    <?php
}

/*--------------------------------------------------------------------------------
	db transfer j3x images (by galleries selected)
--------------------------------------------------------------------------------*/

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximages'); ?>"
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

                <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'tabDbtransferj3ximages']); ?>

                <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'tabDbtransferj3ximages',
                    Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES', true)); ?>

                    <?php //--- all at once ------------------------------------------------------------------------ ?>

				<div class="card text-center">
					<div class="card-body">
						<h3 class="card-title"><?php echo Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_ALL', true); ?></h3>

						<p class="card-text"><?php echo Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_ALL_DESC'); ?></p>
						<p class="card-text"><?php echo Text::_('COM_RSGALLERY2_USE_BELOW_BUTTON'); ?></p>

                            <button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('MaintenanceJ3x.copyDbJ3xImages2J4xUser');return false;">
                            <?php echo Text::_('COM_RSGALLERY2_DB_COPY_ALL_J3X_IMAGES'); ?>
						</button>

                            <button class="btn btn-warning" type="submit" onclick="Joomla.submitbutton('MaintenanceJ3x.revertCopyDbJ3xImages2J4xUser');return false;">
                            <?php echo Text::_('COM_RSGALLERY2_DB_REVERT_COPY_ALL_J3X_IMAGES'); ?>
						</button>

					</div>
				</div>

				<br>

                    <?php //--- copy instruction ------------------------------------------------------------------------ ?>

				<div class="card text-dark bg-light j3x-info-card">
					<div class="card-body">
						<h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J3X_COPY_INSTRUCTION'); ?><?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES'); ?></h5>
                        <?php echo Text::_('COM_RSGALLERY2_J3X_DB_IMAGES_COPY_INSTRUCTION_DESC'); ?>
					</div>
				</div>

                    <?php //--- Select gallery and buttons ---------------------------------------------------------- ?>

                    <?php /*
                                   //                <div class="card text-dark bg-light j3x-gallery-card">
                                       <div class="card-body">
                                           <?php
                                           // specify gallery
                                           // toDO: change name as used for all
                                           echo $this->form->renderFieldset('j3x_gallery');
                                           ?>
                                       </div>
                                   </div>
                                   */
                ?>
				<div class="card text-dark bg-light j3x--card">
					<div class="card-body">
						<h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_USE'); ?></h5>

                            <?php j3xdTransferButtonsHtml ($this); ?>
					</div>
				</div>

				<hr>

                    <?php //--- J3x gallery image status list --------------------------------------------------------------------- ?>

				<div class="card text-dark bg-light j3x-galleries-card">
					<div class="card-body">
						<h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J3X_GALLERY_LIST'); ?></h5>

                            <?php j3x_galleryListHtml ($this); ?>
					</div>
				</div>

				<hr>


                    <?php //--- J3x image info list --------------------------------------------------------------------- ?>

				<div class="card text-dark bg-light j3x-galleries-card">
					<div class="card-body">
						<h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_LIST'); ?></h5>

                            <?php // j3x_imageInfoListHtml ($this); ?>
					</div>
				</div>

				<hr>

                    <?php //--- J4x info about must have been transferred ----------------------------------------------- ?>

				<div class="card text-dark bg-light j4x-info-card" style="max-width: 36rem;">
					<div class="card-header">
                        <?php echo Text::_('COM_RSGALLERY2_J3X_J4_GALLERIES_AS_TREE'); ?>
					</div>
					<div class="card-body">
						<h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J4X_GALLERIES_INFO'); ?></h5>
                        <?php echo Text::_('COM_RSGALLERY2_J4X_GALLERIES_MUST_BE_TRANSFERRED'); ?>
					</div>
				</div>


                <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

                <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>


				<input type="hidden" name="boxchecked" value="0"/>
				<input type="hidden" name="task" value=""/>
			</div>
		</div>
	</div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>












