<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2023 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

//HTMLHelper::_('bootstrap.framework');

//HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/imagesProperties.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/backend/imagesProperties.js', ['version' => 'auto', 'relative' => true]);
// responsible for moveJ3xImages, dbtransferj3xgalleries, dbtransferj3ximages, dbcopyj3xconfig
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.dbTransferJ3xGalleries');

function jsonArray2Lines($lines)
{
    $html = [];

    foreach ($lines as $line) {
        $identHtml = ' * ';
        if (isset ($line->level)) {
            $identHtml = str_repeat('⋮&nbsp;&nbsp;&nbsp;', $line->level);
        }
        $html[] = $identHtml . json_encode($line, JSON_PRETTY_PRINT) . '<br>';
    }


    return implode($html);
}


function isOKIconHtml ($title) {

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

function isNotOkIconHtml ($title) {

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
function j3x_galleryListHtml ($dbtransferj3xgalleries) {

//	$html = <<<EOT
?>
	<?php if (! empty ($dbtransferj3xgalleries->j3x_galleriesSorted)): ?>
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
                <th class="text-center">
                    `parent`
                </th>
                <th class="text-center">
                    `name/alias/note`
                </th>
                <th class="text-center">
                    `description`
                </th>

                <th class="text-center">
                    `thumb_id`
                </th>
                <th class="text-center">
                    `params`
                </th>
                <th class="text-center">
                    `published`
                </th>
                <th class="text-center">
                    `hits`
                </th>

                <th class="text-center">
                    `checked_out`
                </th>
                <th class="text-center">
                    `checked_out_time`
                </th>
                <th class="text-center">
                    `ordering`
                </th>
                <th class="text-center">
                    `date`
                </th>
                <th class="center">
                    `user`
                </th>
                <th class="text-center">
                    `uid`
                </th>
                <th class="text-center">
                    `allowed`
                </th>
                <th class="text-center">
                    `asset_id`
                </th>
                <th class="text-center">
                    `access`
                </th>
            </tr>
            </thead>

            <tbody>

            <?php
            foreach ($dbtransferj3xgalleries->j3x_galleriesSorted as $i => $item) {
                $identHtml = str_repeat('⋮&nbsp;&nbsp;&nbsp;', $item->level);

                if (in_array ($item->id, $dbtransferj3xgalleries->j3x_galleryIdsMerged)){
                    $isMergedHtml =  isOKIconHtml ('Gallery is merged');
                } else {
                    $isMergedHtml =  isNotOkIconHtml ('Gallery is not merged');
                }

                ?>
                <tr class="row<?php echo $i % 2; ?>">

                    <td class="text-center">
                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                    </td>

                    <td class="text-center">
                        <?php echo $isMergedHtml; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->id; ?>
                    </td>

                    <td class="text-center">
                        <?php
                        // ToDo: Name of parent gallery as title
                        echo $item->parent; ?>
                    </td>

                    <td class="text-left">
	                    <?php echo $dbtransferj3xgalleries->escape($item->name); ?>
                        <span class="small" title="<?php echo $dbtransferj3xgalleries->escape($item->path); ?>">
                            <?php if (empty($item->description)) : ?>
	                            <?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $dbtransferj3xgalleries->escape($item->alias)); ?>
                            <?php else : ?>
                                (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $dbtransferj3xgalleries->escape($item->alias),
		                            $dbtransferj3xgalleries->escape($item->description)); ?>)
                            <?php endif; ?>
                        </span>
                        <!--span class="small" title="<?php echo $dbtransferj3xgalleries->escape($item->path); ?>">
                            <?php if (empty($item->note)) : ?>
                                <?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $dbtransferj3xgalleries->escape($item->alias)); ?>
                            <?php else : ?>
                                (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $dbtransferj3xgalleries->escape($item->alias), $dbtransferj3xgalleries->escape($item->note)); ?>)
                            <?php endif; ?>
                        </span-->
                    </td>
                    <td class="center">
                        <span class="small">
                            <?php echo $item->description; ?>
                        </span>
                    </td>

                    <td class="text-center">
                        <?php echo $item->thumb_id; ?>
                    </td>

                    <td class="text-center">
                        <span class="small">
                            "<?php echo $item->params; ?>"
                        </span>
                    </td>

                    <td class="text-center">
                        <?php echo $item->published; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $item->hits; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->checked_out; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $item->checked_out_time; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->ordering; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $item->date; ?>
                    </td>
                    <td class="text-center">
                        "<?php echo $item->user; ?>"
                    </td>
                    <td class="text-center">
                        <?php echo $item->uid; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $item->allowed; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->asset_id; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->access; ?>
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
	J4x galleries
--------------------------------------------------------------------------------*/
function j4x_galleryListHtml ($dbtransferj3xgalleries) {

?>
	<!-- more than root of tree exists -->
	<?php if (count ($dbtransferj3xgalleries->j4x_galleries) >1): ?>

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

                <th class="text-center">
                    `id`
                </th>
                <th class="text-center">
                    `parent_id`
                </th>
                <th class="text-center">
                    `name/alias/note`
                </th>
                <th class="text-center">
                    `description`
                </th>

                <th class="text-center">
                    `thumb_id`
                </th>
                <th class="text-center">
                    `params`
                </th>
                <th class="text-center">
                    `published`
                </th>
                <th class="text-center">
                    `hits`
                </th>

                <th class="text-center">
                    `checked_out`
                </th>
                <th class="text-center">
                    `checked_out_time`
                </th>
                <th class="text-center">
                    `created`
                </th>
                <th class="text-center">
                    `created_by`
                </th>
                <th class="text-center">
                    `created_by_alias`
                </th>
                <th class="text-center">
                    `modified`
                </th>
                <th class="text-center">
                    `modified_by`
                </th>

                <th class="text-center">
                    `parent_id`
                </th>
                <th class="text-center">
                    `level`
                </th>
                <th class="text-center">
                    `path`
                </th>
                <th class="text-center">
                    `lft`
                </th>
                <th class="text-center">
                    `rgt`
                </th>

                <th class="text-center">
                    `asset_id`
                </th>
                <th class="text-center">
                    `access`
                </th>

            </tr>
            </thead>

            <tbody>
            <?php

            foreach ($dbtransferj3xgalleries->j4x_galleries as $i => $item) {
                ?>
                <tr class="row<?php echo $i % 2; ?>">

                    <td class="text-center">
                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->id; ?>
                    </td>

                    <td class="text-center">
                        <?php
                        // ToDo: Name of parent gallery as title
                        echo $item->parent_id; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $dbtransferj3xgalleries->escape($item->name); ?>
                        <span class="small" title="<?php echo $dbtransferj3xgalleries->escape($item->path); ?>">
                            <?php if (empty($item->note)) : ?>
                                <?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $dbtransferj3xgalleries->escape($item->alias)); ?>
                            <?php else : ?>
                                (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $dbtransferj3xgalleries->escape($item->alias), $dbtransferj3xgalleries->escape($item->note)); ?>)
                            <?php endif; ?>
                        </span>
                    </td>

                    <td class="text-center">
                        <?php echo $item->description; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->thumb_id; ?>
                    </td>

                    <td class="text-center">
                        "<?php echo $item->params; ?>"
                    </td>

                    <td class="text-center">
                        <?php echo $item->published; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $item->hits; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->checked_out; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $item->checked_out_time; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->created; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $item->created_by; ?>
                    </td>
                    <td class="text-center">
                        "<?php echo $item->created_by_alias; ?>"
                    </td>
                    <td class="text-center">
                        <?php echo $item->modified; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $item->modified_by; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->parent_id; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->level; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->path; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->lft; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->rgt; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->asset_id; ?>
                    </td>

                    <td class="text-center">
                        <?php echo $item->access; ?>
                    </td>

                </tr>
                <?php
            }
            ?>
            </tbody>

        </table>
	<?php else : ?>
        <h2><span class="badge badge-pill bg-success"><?php echo Text::_('COM_RSGALLERY2_J4X_GALLERIES_LIST_IS_EMPTY'); ?></span></h2>
	<?php endif; ?>

	<?php

//EOT;
//
//	return $html;
}






/*--------------------------------------------------------------------------------
	form
--------------------------------------------------------------------------------*/
?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3xgalleries'); ?>"
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

                <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'dbtransferj3xgalleries')); ?>

                <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'dbtransferj3xgalleries', Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES', true)); ?>

	            <?php //--- J3x main --------------------------------------------------------------- ?>

                <div class="card text-dark bg-light j3x-info-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J3X_COPY_INSTRUCTION'); ?> <?php echo Text::_('COM_RSGALLERY2_J3X_GALLERIES'); ?></h5>
                        <?php echo Text::_('COM_RSGALLERY2_J3X_DB_GALLERY_COPY_INSTRUCTION_DESC'); ?>
                    </div>
                </div>

                <div class="card text-dark bg-light j3x-galleries-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J3X_GALLERY_LIST'); ?></h5>

                        <?php j3x_galleryListHtml ($this); ?>
                    </div>
                </div>

                <hr>

	            <?php //--- J4x main --------------------------------------------------------------- ?>

                <div class="card text-dark bg-light j4x-info-card" style="max-width: 36rem;">
                    <div class="card-header">
			            <?php echo Text::_('COM_RSGALLERY2_J3X_J4_GALLERIES_AS_TREE'); ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J4X_GALLERIES_INFO'); ?></h5>
			            <?php echo Text::_('COM_RSGALLERY2_J4X_GALLERIES_INFO_DESC'); ?>
                    </div>
                </div>

                <div class="card text-dark bg-light j4x-galleries-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J4X_GALLERY_LIST'); ?></h5>

                        <?php j4x_galleryListHtml ($this); ?>
                    </div>
                </div>

                <hr>

	            <?php //--- J3x tree --------------------------------------------------------------- ?>

                <div class="card text-dark bg-light j3x-galleries-as-tree-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo Text::_('COM_RSGALLERY2_J3X_J3_GALLERIES_AS_TREE'); ?></h5>

	                    <?php echo $this->j3x_galleriesHtml; ?>
                    </div>
                </div>

                <hr>

	            <?php //--- J3x raw --------------------------------------------------------------- ?>

                <div class="card text-dark bg-light j3x-galleries-as-raw-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo jsonArray2Lines($this->j3x_galleriesSorted); ?></h5>

			            <?php echo $this->j3x_galleriesHtml; ?>
                    </div>
                </div>

                <hr>

                <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

                <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

                <!--input type="hidden" name="option" value="com_rsgallery2" />
                <input type="hidden" name="rsgOption" value="maintenance" /-->

                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="task" value="" />
                <?php echo HTMLHelper::_('form.token'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>


