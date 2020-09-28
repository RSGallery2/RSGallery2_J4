<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

//HTMLHelper::_('bootstrap.framework');
//HTMLHelper::_('behavior.multiselect');


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

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DBTransferJ3xGalleries'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="row">
        <?php if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="">
                <?php echo $this->sidebar; ?>
            </div>
        <?php endif; ?>
        <div class="<?php if (!empty($this->sidebar)) {
            echo 'col-md-10';
        } else {
            echo 'col-md-12';
        } ?>">
            <div id="j-main-container" class="j-main-container">

                <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'DBTransferJ3xGalleries')); ?>

                <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DBTransferJ3xGalleries', Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES', true)); ?>

                <legend><strong><?php echo Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES'); ?></strong></legend>

                <h3><?php echo Text::_('COM_RSGALLERY2_J3X_GALLERY_LIST'); ?></h3>

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
                        <th width="1%" class="text-center">
                            `id`
                        </th>
                        <th width="1%" class="text-center">
                            `parent`
                        </th>
                        <th width="10%" class="text-center">
                            `alias`
                        </th>
                        <th width="1%" class="text-center">
                            `description`
                        </th>

                        <th width="1%" class="text-center">
                            `thumb_id`
                        </th>
                        <th width="1%" class="text-center">
                            `params`
                        </th>
                        <th width="1%" class="text-center">
                            `published`
                        </th>
                        <th width="1%" class="text-center">
                            `hits`
                        </th>

                        <th width="1%" class="text-center">
                            `checked_out`
                        </th>
                        <th width="1%" class="text-center">
                            `checked_out_time`
                        </th>
                        <th width="1%" class="text-center">
                            `ordering`
                        </th>
                        <th width="1%" class="text-center">
                            `date`
                        </th>
                        <th width="1%" class="center">
                            `user`
                        </th>
                        <th width="1%" class="text-center">
                            `uid`
                        </th>
                        <th width="1%" class="text-center">
                            `allowed`
                        </th>
                        <th width="1%" class="text-center">
                            `asset_id`
                        </th>
                        <th width="1%" class="text-center">
                            `access`
                        </th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php
                    foreach ($this->j3x_galleriesSorted as $i => $item) {
                        $identHtml = str_repeat('⋮&nbsp;&nbsp;&nbsp;', $item->level);

                        if (in_array ($item->id, $this->j3x_galleryIdsMerged)){
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

                            <td width="1%" class="text-center">
                                <?php
                                // ToDo: Name of parent gallery as title
                                echo $item->parent; ?>
                            </td>

                            <td class="text-left">
                                <!--
                                <?php //echo $identHtml . $this->escape($item->name);
                                ?>
                                <span class="small">
                                    (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>)
    					        </span>
    					        -->
                                <?php echo $identHtml . $this->escape($item->alias); ?>

                            </td>
                            <td width="1%" class="center">
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

                            <td width="1%" class="text-center">
                                <?php echo $item->published; ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php echo $item->hits; ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php echo $item->checked_out; ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php echo $item->checked_out_time; ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php echo $item->ordering; ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php echo $item->date; ?>
                            </td>
                            <td width="1%" class="text-center">
                                "<?php echo $item->user; ?>"
                            </td>
                            <td width="1%" class="text-center">
                                <?php echo $item->uid; ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php echo $item->allowed; ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php echo $item->asset_id; ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php echo $item->access; ?>
                            </td>

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>

                </table>


                <?php

                try {
                    echo '<hr>';
                    echo '<h3>J3x ' . Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE') . '</h3>';
                    echo $this->j3x_galleriesHtml;

                    echo '<hr>';
                    echo '<h3>J3x ' . Text::_('COM_RSGALLERY2_RAW_GALLERIES_TXT') . '</h3>';
                    echo jsonArray2Lines($this->j3x_galleriesSorted);

//                        echo '<hr>';
//                        echo jsonArray2Lines ($this->j3x_galleries);


                    /*--------------------------------------------------------------------------------
                        J4x galleries
                    --------------------------------------------------------------------------------*/

//
                    echo '<hr style="height:1px;border:none;color:#333;background-color:#333;" />';
                    echo '<hr style="height:1px;border:none;color:#333;background-color:#333;" />';

                    echo '<h3>' . 'J4x ' . Text::_('COM_RSGALLERY2_GALLERIES_LIST') . '</h3>';

//                    if (true) {
                    if (count ($this->j4x_galleries) > 1) {
                    ?>

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
                                    `id`
                                </th>
                                <th width="1%" class="text-center">
                                    `parent_id`
                                </th>
                                <th width="1%" class="text-center">
                                    `name/alias/note`
                                </th>
                                <th width="1%" class="text-center">
                                    `description`
                                </th>

                                <th width="1%" class="text-center">
                                    `thumb_id`
                                </th>
                                <th width="1%" class="text-center">
                                    `params`
                                </th>
                                <th width="1%" class="text-center">
                                    `published`
                                </th>
                                <th width="1%" class="text-center">
                                    `hits`
                                </th>

                                <th width="1%" class="text-center">
                                    `checked_out`
                                </th>
                                <th width="1%" class="text-center">
                                    `checked_out_time`
                                </th>
                                <th width="1%" class="text-center">
                                    `created`
                                </th>
                                <th width="1%" class="text-center">
                                    `created_by`
                                </th>
                                <th width="1%" class="text-center">
                                    `created_by_alias`
                                </th>
                                <th width="1%" class="text-center">
                                    `modified`
                                </th>
                                <th width="1%" class="text-center">
                                    `modified_by`
                                </th>

                                <th width="1%" class="text-center">
                                    `parent_id`
                                </th>
                                <th width="1%" class="text-center">
                                    `level`
                                </th>
                                <th width="1%" class="text-center">
                                    `path`
                                </th>
                                <th width="1%" class="text-center">
                                    `lft`
                                </th>
                                <th width="1%" class="text-center">
                                    `rgt`
                                </th>

                                <th width="1%" class="text-center">
                                    `asset_id`
                                </th>
                                <th width="1%" class="text-center">
                                    `access`
                                </th>

                            </tr>
                            </thead>

                            <tbody>
                            <?php

                            foreach ($this->j4x_galleries as $i => $item) {
                                ?>
                                <tr class="row<?php echo $i % 2; ?>">

                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                    </td>

                                    <td class="text-center">
                                        <?php echo $item->id; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php
                                        // ToDo: Name of parent gallery as title
                                        echo $item->parent_id; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php echo $this->escape($item->name); ?>
                                        <span class="small" title="<?php echo $this->escape($item->path); ?>">
                                        <?php if (empty($item->note)) : ?>
                                            (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>)
                                        <?php else : ?>
                                            (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>)
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

                                    <td width="1%" class="text-center">
                                        <?php echo $item->published; ?>
                                    </td>
                                    <td width="1%" class="text-center">
                                        <?php echo $item->hits; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->checked_out; ?>
                                    </td>
                                    <td width="1%" class="text-center">
                                        <?php echo $item->checked_out_time; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->created; ?>
                                    </td>
                                    <td width="1%" class="text-center">
                                        <?php echo $item->created_by; ?>
                                    </td>
                                    <td width="1%" class="text-center">
                                        "<?php echo $item->created_by_alias; ?>"
                                    </td>
                                    <td width="1%" class="text-center">
                                        <?php echo $item->modified; ?>
                                    </td>
                                    <td width="1%" class="text-center">
                                        <?php echo $item->modified_by; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->parent_id; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->level; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->path; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->lft; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->rgt; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->asset_id; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->access; ?>
                                    </td>

                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>

                        </table>


                        <?php


                        echo '<hr>';
                        echo $this->j4x_galleriesHtml;

                        echo '<hr>';
                        echo jsonArray2Lines($this->j4x_galleries);
                    } // count (j4x_galleries) > 1
                    else {
                        $keyTranslation = 'J4x ' . Text::_('COM_RSGALLERY2_GALLERIES_LIST_IS_EMPTY');
                        echo '   <h2><span class="badge badge-pill badge-success">' . $keyTranslation . '</span></h2>';
                    }

                    echo '<hr>';
                } catch (\RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error rawEdit view: "' . 'DBTransferJ3xGalleries' . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                ?>

                <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

                <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

                <!--input type="hidden" name="option" value="com_rsgallery2" />
                <input type="hidden" name="rsgOption" value="maintenance" /-->

                <input type="hidden" name="task" value=""/>
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>


