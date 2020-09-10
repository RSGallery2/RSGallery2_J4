<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

HTMLHelper::_('stylesheet', 'com_rsgallery2/moveJ3xImages.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_rsgallery2/moveJ3xImages.js', ['version' => 'auto', 'relative' => true]);

Text::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST', true);

// Drag and Drop security id on ajax call.
$script[] = 'var Token = \'' . Session::getFormToken() . '\';';
Factory::getDocument()->addScriptDeclaration(implode("\n", $script));

// $app = Factory::getApplication();

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=MoveJ3xImages'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="row">
        <?php if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="col-md-2">
                <?php echo $this->sidebar; ?>
            </div>
        <?php endif; ?>
        <div class="<?php if (!empty($this->sidebar)) {
            echo 'col-md-10';
        } else {
            echo 'col-md-12';
        } ?>">
            <div id="j-main-container" class="j-main-container">

                <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'MoveJ3xImages')); ?>

                <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'MoveJ3xImages', Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES', true)); ?>

                <!--legend><strong><?php echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES'); ?></strong></legend-->

                <p>
                    <?php echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_USE'); ?>

                </p>
                <?php
                // specify gallery
                // toDO: change name as used for all
                echo $this->form->renderFieldset('j3x_gallery');
                ?>
                <button id="selectGallery" type="button" class="btn btn-success btn-rsg2"
                        title="<?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_BY_GALLERY_DEC'); ?>"

                >
                    <span class="icon-checkbox" aria-hidden="false"></span>
                    <?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_BY_GALLERY'); ?>
                </button>
                <button id="deSelectGallery" type="button" class="btn btn-success btn-rsg2"
                        title="<?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_DESELECT_BY_GALLERY_DEC'); ?>"

                >
                    <span class="icon-checkbox-unchecked" aria-hidden="false"></span>
                    <?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_DESELECT_BY_GALLERY'); ?>
                </button>
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
                <hr>

                    <button id="ftp-upload-folder-button-drop" type="button" class="btn btn-secondary btn-rsg2"
                            title="<?php echo Text::_('COM_RSGALLERY2_MOVE_SELECTED_J3X_IMAGES_DESC'); ?>"
                            disabled
                    >
                        <span class="icon-out-2" aria-hidden="false"></span>
                        <span class="icon-image" aria-hidden="false"></span>
                        <?php echo Text::_('COM_RSGALLERY2_MOVE_SELECTED_J3X_IMAGES'); ?>
                    </button>
                    <button id="select-zip-file-button-drop" type="button" class="btn btn-warning btn-rsg2"
                            title="<?php echo Text::_('COM_RSGALLERY2_MOVE_SELECTED_J3X_IMAGES_DESC'); ?>"
                    >
                        <span class="icon-out-2" aria-hidden="false"></span>
                        <span class="icon-images" aria-hidden="false"></span>
                        <?php echo Text::_('COM_RSGALLERY2_MOVE_ALL_J3X_IMAGES'); ?>
                    </button>
                <p>
                </p>

                <hr>

                <h3><?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_LIST'); ?></h3>

                <table class="table table-striped" id="imageList_j3x">

                    <caption id="captionTable" class="sr-only">
                        <?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>
                        , <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
                    </caption>
                    <thead>
                    <tr>
                        <td style="width:1%" class="text-center">
                            <?php echo HTMLHelper::_('grid.checkall'); ?>
                        </td>

                        <!--th width="1%" class="text-center">
                            <?php echo Text::_('JSTATUS'); ?>
                        </th-->
                        <th width="1%" class="center">
                            `gallery_id`
                        </th>
                        <th width="1%" class="center">
                            `name`
                        </th>
                        <!--th width="1%" class="center">
                            `alias`
                        </th>
                        <th width="1%" class="center">
                            `descr`
                        </th-->
                        <!--th width="1%" class="center">
                            `title`
                        </th>
                        <th width="1%" class="center">
                            `hits`
                        </th>
                        <th width="1%" class="center">
                            `date`
                        </th>
                        <th width="1%" class="center">
                            `rating`
                        </th>
                        <th width="1%" class="center">
                            `votes`
                        </th>
                        <th width="1%" class="center">
                            `comments`
                        </th>
                        <th width="1%" class="center">
                            `published`
                        </th>
                        <th width="1%" class="center">
                            `checked_out`
                        </th>
                        <th width="1%" class="center">
                            `checked_out_time`
                        </th>
                        <th width="1%" class="center">
                            `ordering`
                        </th>
                        <th width="1%" class="center">
                            `approved`
                        </th>
                        <th width="1%" class="center">
                            `userid`
                        </th>
                        <th width="1%" class="center">
                            `params`
                        </th>
                        <th width="1%" class="center">
                            `asset_id`
                        </th-->

					</tr>
                    </thead>

                    <tbody>

                    <?php
                    foreach ($this->j4x_galleries as $i => $item) {

//                        // a) Must be transferred b) check
//
//                        $isMerged =in_array ($item->id, $this->j3x_imageIdsMerged);
//                        if ($isMerged){
//                            $mergedStatusHtml =  isOKIconHtml ('Image is merged');
//                        } else {
//                            $mergedStatusHtml =  isNotOkIconHtml ('Image is not merged');
//                        }

                        $isToBeMoved = in_array ($item->id, $this->j3x_galleries4ImageMove);
                        if ( ! $isToBeMoved){
                            continue;

                        ?>
                        <tr>

                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                            </td>

                            <!--td class="text-center">
                                <?php echo $mergedStatusHtml; ?>
                            </td-->

                            <td width="1%" class="center">
                                <?php
                                $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=gallery.edit&id=" . $item->id);
                                echo '<a href="' . $link . '"">' . $item->id . '</a>';
                                ?>
                            </td>
                            <td width="1%" class="center">
                                <?php
                                $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=gallery.edit&id=" . $item->id);
                                echo '<a href="' . $link . '"">' . $item->name . '</a>';
                                ?>
                            </td>
                            <!--td width="1%" class="center">
                                <?php //echo $item->alias; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->descr; ?>
                            </td-->
                            <td width="1%" class="center">
                                <?php echo $item->gallery_id; ?>
                            </td>
                            <!--td width="1%" class="center">
                                <?php //echo $item->title; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->hits; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->date; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->rating; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->votes; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->comments; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->published; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->checked_out; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->checked_out_time; ?>
                            </td-->
                            <td width="1%" class="center">
                                <?php echo $item->ordering; ?>
                            </td>
                            <!--td width="1%" class="center">
                                <?php //echo $item->approved; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->userid; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->params; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php //echo $item->asset_id; ?>
                            </td-->
                        </tr>

                        <?php

                    }
                    ?>
                    </tbody>

                </table>


                <?php

//                echo '<hr>';
// ToDo:           sorted by gallery
//                echo '<h3>J3x ' . Text::_('COM_RSGALLERY2_RAW_IMAGES_TXT') . '</h3>';


                /*--------------------------------------------------------------------------------
                    J4x images
                --------------------------------------------------------------------------------*/

                //
                echo '<hr style="height:1px;border:none;color:#333;background-color:#333;" />';
                echo '<hr style="height:1px;border:none;color:#333;background-color:#333;" />';

                echo '<h3>' . 'J4x ' . Text::_('COM_RSGALLERY2_IMAGES_LIST') . '</h3>';

                //if (true) {
                //if (false) {
                if (count ($this->j4x_images) > 0) {
                ?>

                <table class="table table-striped" id="imageList_j4x">

                    <caption id="captionTable" class="sr-only">
                        <?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
                    </caption>
                    <thead>
                    <tr>
                        <!--td style="width:1%" class="text-center">
                            <?php echo HTMLHelper::_('grid.checkall'); ?>
                        </td-->

                        <th width="1%" class="text-center">
                            `id`
                        </th>
                        <th width="1%" class="text-center">
                            `name/alias/note`
                        </th>
                        <!--th width="1%" class="text-center">
                            `description`
                        </th-->

                        <th width="1%" class="text-center">
                            `gallery_id`
                        </th>
                        <!--th width="1%" class="text-center">
                            `title`
                        </th>

                        <th width="1%" class="text-center">
                            `params`
                        </th>
                        <th width="1%" class="text-center">
                            `published`
                        </th>
                        <th width="1%" class="text-center">
                            `publish_up`
                        </th>
                        <th width="1%" class="text-center">
                            `publish_down`
                        </th>

                        <th width="1%" class="text-center">
                            `hits`
                        </th>
                        <th width="1%" class="text-center">
                            `rating`
                        </th>
                        <th width="1%" class="text-center">
                            `votes`
                        </th>
                        <th width="1%" class="text-center">
                            `comments`
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
                        </th-->

                        <th width="1%" class="text-center">
                            `ordering`
                        </th>
                        <!--th width="1%" class="text-center">
                            `approved`
                        </th>

                        <th width="1%" class="text-center">
                            `asset_id`
                        </th>
                        <th width="1%" class="text-center">
                            `access`
                        </th-->
                        <th width="1%" class="text-center">
                            `use_j3x_location`
                        </th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    foreach ($this->j4x_images as $i => $item) {

                        if (in_array ($item->id, $this->j3x_imageIdsMerged)){
                            $isMergedHtml =  isOKIconHtml ('Image is merged');
                        } else {
                            $isMergedHtml =  isNotOkIconHtml ('Image is not merged');
                        }

                        ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <!--td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                            </td-->

                            <td class="text-center">
                                <?php echo $item->id; ?>
                            </td>

                            <td class="text-center">
                                <?php echo $this->escape($item->name); ?>
                                <span class="small" title="<?php // echo $this->escape($item->path);
                                ?>">
                                    <?php if ( ! isset($item->note)) : ?>
                                        (<?php //echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>)
                                    <?php else : ?>
                                        (<?php //echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>)
                                    <?php endif; ?>
                                    </span>
                            </td>

                            <!--td class="text-center">
                                <?php echo '"' . $item->description . '"'; ?>
                            </td-->

                            <td class="text-center">
                                <?php echo $item->gallery_id; ?>
                            </td>

                            <!--td class="text-center">
                                <?php
                                if (! ! isset($item->title))
                                {
                                    echo '"' . $item->title . '"';
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>

                            <td class="text-center">
                                <?php
                                if (! ! isset($item->params))
                                {
                                    echo '"' . $item->params . '"';
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php echo $item->published; ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php
                                if (! ! isset($item->publish_up))
                                {
                                    echo '"' . $item->publish_up . '"';
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php
                                if (! ! isset($item->publish_down))
                                {
                                    echo '"' . $item->publish_down . '"';
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php echo $item->hits; ?>
                            </td>
                            <td class="text-center">
                                <?php echo $item->rating; ?>
                            </td>

                            <td class="text-center">
                                <?php echo $item->votes; ?>
                            </td>

                            <td class="text-center">
                                "<?php echo $item->comments; ?>"
                            </td>


                            <td width="1%" class="text-center">
                                <?php
                                if(! ! isset($item->checked_out))
                                {
                                    echo $item->checked_out;
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php
                                if(! ! isset($item->checked_out_time))
                                {
                                    echo $item->checked_out_time;
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php echo $item->created; ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php
                                if(! ! isset($item->created_by))
                                {
                                    echo $item->created_by;
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>
                            <td width="1%" class="text-center">
                                "<?php
                                if(! ! isset($item->created_by_alias))
                                {
                                    echo $item->created_by_alias;
                                } else {
                                    echo '???';
                                }
                                ?>"
                            </td>
                            <td width="1%" class="text-center">
                                <?php echo $item->modified; ?>
                            </td>
                            <td width="1%" class="text-center">
                                <?php echo $item->modified_by; ?>
                            </td-->


                            <td width="1%" class="text-center">
                                <?php echo $item->ordering; ?>
                            </td>

                            <!--td width="1%" class="text-center">
                                <?php
                                if(! ! isset($item->approved))
                                {
                                    echo $item->approved;
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php
                                if(! ! isset($item->asset_id))
                                {
                                    echo $item->asset_id;
                                } else {
                                    echo '???';
                                }
                                ?>
                            </td>

                            <td width="1%" class="text-center">
                                <?php echo $item->access; ?>
                            </td-->
                            <td width="1%" class="text-center">
                                <?php echo $item->use_j3x_location; ?>
                            </td>

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>


                <?php

                } // count (j4x_images) > 1
                else {
                    $keyTranslation = 'J4x ' . Text::_('COM_RSGALLERY2_IMAGES_LIST_IS_EMPTY');
                    echo '   <h2><span class="badge badge-pill badge-success">' . $keyTranslation . '</span></h2>';
                }


                try {


                    echo '<hr>';
                } catch (\RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error rawEdit view: "' . 'MoveJ3xImages' . '"<br>';
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


<?php

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












































