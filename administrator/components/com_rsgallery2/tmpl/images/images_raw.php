<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Tmpl\Images;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.multiselect');

$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

$listOrder = '';
$ListDirn  = '';

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images&layout=images_raw'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="d-flex flex-row">
        <?php if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="">
                <?php echo $this->sidebar; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex flex-row">
        <div class="flex-fill">
            <div id="j-main-container" class="j-main-container">
                <div>
                    <?php if (empty($this->items)) : ?>
                        <div class="alert alert-info">
                            <span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
                            <?php echo Text::_('COM_RSGALLERY2_NO_IMAGE_UPLOADED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
                        </div>
                    <?php else : ?>
                        <?php // echo 'images: ' . count($this->items); ?>

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
                                <th width="1%" class="text-center">
                                    `use_j3x_location`
                                </th>

                            </tr>
                            </thead>

                            <tbody>
                            
                            <?php foreach ($this->items as $i => $item) { ?>
                                <tr class="row<?php echo $i % 2; ?>">

                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                    </td>

                                    <td class="text-center">
                                        <?php echo $item->id; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php echo $this->escape($item->name); ?>
                                        <span class="small" title="<?php // echo $this->escape($item->path); ?>">
                                            <?php if (!isset($item->note)) : ?>
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
                                        if (!!isset($item->title)) {
                                            echo '"' . $item->title . '"';
                                        } else {
                                            echo '???';
                                        }
                                        ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        if (!!isset($item->params)) {
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
                                        <?php if (!!isset($item->publish_up)) {
                                            echo '"' . $item->publish_up . '"';
                                        } else {
                                            echo '???';
                                        }
                                        ?>
                                    </td>
                                    <td width="1%" class="text-center">
                                        <?php if (!!isset($item->publish_down)) {
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
                                        if (!!isset($item->asset_id)) {
                                            echo $item->checked_out;
                                        } else {
                                            echo '???';
                                        }
                                        ?>
                                    </td>
                                    <td width="1%" class="text-center">
                                        <?php
                                        if (!!isset($item->asset_id)) {
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
                                        if (!!isset($item->asset_id)) {
                                            echo $item->created_by;
                                        } else {
                                            echo '???';
                                        }
                                        ?>
                                    </td>
                                    <td width="1%" class="text-center">
                                        "<?php
                                        if (!!isset($item->asset_id)) {
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
                                        if (!!isset($item->approved)) {
                                            echo $item->approved;
                                        } else {
                                            echo '???';
                                        }
                                        ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php
                                        if (!!isset($item->asset_id)) {
                                            echo $item->asset_id;
                                        } else {
                                            echo '???';
                                        }
                                        ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->access; ?>
                                    </td>

                                    <td width="1%" class="text-center">
                                        <?php echo $item->use_j3x_location; ?>
                                    </td>

                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="task" value=""/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
