<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.multiselect');

$listOrder = '';
$ListDirn  = '';

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=galleries&layout=galleries_raw'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="row">
        <div class="col-md-12">
		<?php if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="col-md-2">
				<?php echo $this->sidebar; ?>
            </div>
		<?php endif; ?>

            <!--div id="j-main-container" class="j-main-container"-->
                <div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
                    <!--div id="j-main-container" class="j-main-container"-->
                    <div>
                        <?php if (empty($this->items)) : ?>
                            <div class="alert alert-info">
                                <span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
                                <?php echo Text::_('COM_RSGALLERY2_NO_GALLERY_CREATED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
                            </div>
                        <?php else : ?>
                            <?php // echo 'galleries: ' . count($this->items); ?>

                            <table class="table table-striped" id="galleryList">

                                <caption id="captionTable" class="sr-only">
		                            <?php echo Text::_('COM_CATEGORICOM_RSGALLERY2_TABLE_CAPTIONES_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
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

                                    foreach ($this->items as $i => $item)
                                    {
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
                            if (count ($this->items) == 1) {
                                $keyTranslation = Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_IS_EMPTY');
                                echo '   <h2><span class="badge badge-pill badge-success">' . $keyTranslation . '</span></h2>';
                            }

                            ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="task" value="" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
