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


?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=TransferJ3xImages'); ?>"
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

                <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'TransferJ3xImages')); ?>

                <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'TransferJ3xImages', Text::_('COM_RSGALLERY2_TRANSFER_J3X_IMAGES', true)); ?>

                <legend><strong><?php echo Text::_('COM_RSGALLERY2_TRANSFER_J3X_IMAGES'); ?></strong></legend>

                <h3><?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_LIST'); ?></h3>

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
                        </th>
                        <th width="1%" class="text-center">
                            `use_j3x_location`
                        </th>

					</tr>
                    </thead>

                    <tbody>

                    <?php
                    foreach ($this->j3x_images as $i => $item) {

                        ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                            </td>

                            <td width="1%" class="center">
                                <?php
                                $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);
                                echo '<a href="' . $link . '"">' . $item->id . '</a>';
                                ?>
                            </td>
                            <td width="1%" class="center">
                                <?php
                                $link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);
                                echo '<a href="' . $link . '"">' . $item->name . '</a>';
                                ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->alias; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->descr; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->gallery_id; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->title; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->hits; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->date; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->rating; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->votes; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->comments; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->published; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->checked_out; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->checked_out_time; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->ordering; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->approved; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->userid; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->params; ?>
                            </td>
                            <td width="1%" class="center">
                                <?php echo $item->asset_id; ?>
                            </td>
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

                <table class="table table-striped" id="imageList">

                    <caption id="captionTable" class="sr-only">
                        <?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
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
                            `name/alias/note`
                        </th>
                        <th width="1%" class="text-center">
                            `description`
                        </th>

                        <th width="1%" class="text-center">
                            `gallery_id`
                        </th>
                        <th width="1%" class="text-center">
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
                        </th>

                        <th width="1%" class="text-center">
                            `ordering`
                        </th>
                        <th width="1%" class="text-center">
                            `approved`
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

                    foreach ($this->j4x_images as $i => $item)
                    {
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                            </td>

                            <td class="text-center">
                                <?php echo $item->id; ?>
                            </td>

                            <td class="text-center">
                                <?php echo $this->escape($item->name); ?>
                                <span class="small" title="<?php // echo $this->escape($item->path);
                                ?>">
                                    <?php if ( ! isset($item->note)) : ?>
                                        (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>)
                                    <?php else : ?>
                                        (<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>)
                                    <?php endif; ?>
                                    </span>
                            </td>

                            <td class="text-center">
                                <?php echo '"' . $item->description . '"'; ?>
                            </td>

                            <td class="text-center">
                                <?php echo $item->gallery_id; ?>
                            </td>

                            <td class="text-center">
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
                            </td>


                            <td width="1%" class="text-center">
                                <?php echo $item->ordering; ?>
                            </td>

                            <td width="1%" class="text-center">
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
                    $OutTxt .= 'Error rawEdit view: "' . 'TransferJ3xImages' . '"<br>';
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










































