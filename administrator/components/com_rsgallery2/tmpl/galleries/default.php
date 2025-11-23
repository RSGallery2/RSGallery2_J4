<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\String\Inflector;

HTMLHelper::_('behavior.multiselect');

$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.galleries');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');
$extension = $this->escape($this->state->get('filter.extension'));
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'a.lft' && strtolower($listDirn) == 'asc');
$parts     = explode('.', $extension, 2);
$component = $parts[0];
$section   = null;

/**/
if (count($parts) > 1) {
    $section = $parts[1];

    $inflector = Inflector::getInstance();

    if (!$inflector->isPlural($section)) {
        $section = $inflector->toPlural($section);
    }
}
/**/

if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = 'index.php?option=com_rsgallery2&task=galleries.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?php
echo Route::_('index.php?option=com_rsgallery2&view=galleries'); ?>"
      method="post" name="adminForm" id="adminForm">
    <div class="d-flex flex-row">
        <?php
        if (!empty($this->sidebar)) : ?>
            <!--div id="j-sidebar-container" class="col-md-2"-->
            <div id="j-sidebar-container" class=" p-2">
                <?php
                echo $this->sidebar; ?>
            </div>
            <?php
        endif; ?>
        <!--div class="<?php
        echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
        <div class="flex-fill">
            <div id="j-main-container" class="j-main-container">
                <?php
                // Search tools bar
                echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]);
                ?>
                <?php
                if (empty($this->items)) : ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-info">
                                <span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php
                                    echo Text::_('INFO'); ?></span>
                                <?php
                                echo Text::_('COM_RSGALLERY2_NO_GALLERY_CREATED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
                            </div>
                        </div>
                    </div>

                    <?php
                else : ?>
                    <table class="table" id="galleryList">
                        <caption id="captionTable" class="sr-only">
                            <?php
                            echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>, <?php
                            echo Text::_('JGLOBAL_SORTED_BY'); ?>
                        </caption>
                        <thead>
                        <tr>
                            <td style="width:1%" class="text-center">
                                <?php
                                echo HTMLHelper::_('grid.checkall'); ?>
                            </td>
                            <th scope="col" style="width:1%" class="text-center d-none d-md-table-cell">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                            </th>
                            <th scope="col" style="width:1%" class="text-center">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="min-width:100px">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_IMAGES', 'image_count', $listDirn, $listOrder); ?>
                            </th>

                            <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                            </th>


                            <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', 'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
                            </th>

                            <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_DATE_CREATED', 'a.created', $listDirn, $listOrder); ?>
                            </th>


                            <th scope="col" style="width:3%" class="d-none d-lg-table-cell text-center">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
                            </th>

                            <th scope="col" style="width:5%" class="d-none d-md-table-cell">
                                <?php
                                echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                            </th>

                            <?php if ($this->isDevelop) : ?>
                                <th scope="col" style="width:5%" class="d-none d-md-table-cell">
                                    lft
                                </th>
                            <?php endif; ?>


                            <?php
                            /**
                             *
                             *
                             * <?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_published')) : ?>
                             * <th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
                             * <span class="icon-publish hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_PUBLISHED_ITEMS'); ?>"></span>
                             * <span class="sr-only"><?php echo Text::_('COM_RSGALLERY2_COUNT_PUBLISHED_ITEMS'); ?></span>
                             * </th>
                             * <?php endif; ?>
                             * <?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_unpublished')) : ?>
                             * <th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
                             * <span class="icon-unpublish hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_UNPUBLISHED_ITEMS'); ?>"></span>
                             * <span class="sr-only"><?php echo Text::_('COM_RSGALLERY2_COUNT_UNPUBLISHED_ITEMS'); ?></span>
                             * </th>
                             * <?php endif; ?>
                             * <?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_archived')) : ?>
                             * <th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
                             * <span class="icon-archive hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_ARCHIVED_ITEMS'); ?>"></span>
                             * <span class="sr-only"><?php echo Text::_('COM_RSGALLERY2_COUNT_ARCHIVED_ITEMS'); ?></span>
                             * </th>
                             * <?php endif; ?>
                             * <?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_trashed')) : ?>
                             * <th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
                             * <span class="icon-trash hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_TRASHED_ITEMS'); ?>"></span>
                             * <span class="sr-only"><?php echo Text::_('COM_RSGALLERY2_COUNT_TRASHED_ITEMS'); ?></span>
                             * </th>
                             * <?php endif; ?>
                             * <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                             * <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
                             * </th>
                             * <?php if ($this->assoc) : ?>
                             * <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                             * <?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
                             * </th>
                             * <?php endif; ?>
                             * <?php if (Multilanguage::isEnabled()) : ?>
                             * <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                             * <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
                             * </th>
                             * <?php endif; ?>
                             */
                            ?>


                        </tr>
                        </thead>
                        <tbody
                        <?php
                        if ($saveOrder) :
                            ?> class="js-draggable"
                                             data-url="<?php
                                                echo $saveOrderingUrl; ?>"
                                             data-direction="<?php
                                                echo strtolower($listDirn); ?>"
                                             data-nested="true"<?php
                        endif; ?> // ToDo: check for false/true
                        >

                        <?php
                        foreach ($this->items as $i => $item) : ?>
                            <?php

                            // ignore the root element of the nested table
                            if ($item->id == 1) {
                                continue;
                            }

                            // access rights of this gallery
                            $canEdit    = $user->authorise('core.edit', $extension . '.gallery.' . $item->id);
                            $canCheckin = $user->authorise('core.admin', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                            $canEditOwn = $user->authorise('core.edit.own', $extension . '.gallery.' . $item->id) && $item->created_by == $userId;
                            $canChange  = $user->authorise('core.edit.state', $extension . '.gallery.' . $item->id) && $canCheckin;

                            // Get the parents of item for sorting
                            if ($item->level > 1) {
                                $parentsStr       = '';
                                $_currentParentId = $item->parent_id;
                                $parentsStr       = ' ' . $_currentParentId;
                                for ($i2 = 0; $i2 < $item->level; $i2++) {
                                    foreach ($this->ordering as $k => $v) {
                                        $v = implode('-', $v);
                                        $v = '-' . $v . '-';
                                        if (strpos($v, '-' . $_currentParentId . '-') !== false) {
                                            $parentsStr       .= ' ' . $k;
                                            $_currentParentId = $k;
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $parentsStr = '';
                            }

                            $created_by  = Factory::getUser($item->created_by);
                            $modified_by = Factory::getUser($item->modified_by);
                            if (empty($modified_by->name)) {
                                $modified_by = $created_by;
                            }

                            ?>
                            <tr class="row<?php
                            echo $i % 2; ?>"
                                data-draggable-group="<?php
                                echo $item->parent_id; ?>"
                                data-item-id="<?php
                                echo $item->id ?>"
                                data-parents="<?php
                                echo $parentsStr ?>"
                                data-level="<?php
                                echo $item->level ?>"
                            >
                                <td class="text-center">
                                    <?php
                                    echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td class="order text-center d-none d-md-table-cell">
                                    <?php
                                    $iconClass = '';
                                    if (!$canChange) {
                                        $iconClass = ' inactive';
                                    } elseif (!$saveOrder) {
                                        $iconClass = ' inactive tip-top hasTooltip" title="' . HTMLHelper::_('tooltipText', 'JORDERINGDISABLED');
                                    }
                                    ?>
                                    <span class="sortable-handler<?php
                                    echo $iconClass ?>">
                                            <span class="fas fa-ellipsis-v"></span>
                                        </span>
                                    <?php
                                    if ($canChange && $saveOrder) : ?>
                                        <input type="text" style="display:none" name="order[]" size="5" value="<?php
                                        echo $item->lft; ?>">
                                        <?php
                                    endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <?php
                                        echo HTMLHelper::_('jgrid.published', $item->published, $i, 'galleries.', $canChange); ?>
                                    </div>
                                </td>
                                <td scope="row">
                                    <?php
                                    echo LayoutHelper::render('joomla.html.treeprefix', ['level' => $item->level]); ?>
                                    <?php
                                    if ($item->checked_out) : ?>
                                        <?php
                                        echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'galleries.', $canCheckin); ?>
                                        <?php
                                    endif; ?>
                                    <?php
                                    if ($canEdit || $canEditOwn) : ?>
                                        <?php
                                        $editIcon = $item->checked_out ? '' : '<span class="fa fa-pencil-square mr-2" aria-hidden="true"></span>'; ?>
                                        <a class="hasTooltip" href="<?php
                                        echo Route::_('index.php?option=com_rsgallery2&task=gallery.edit&id=' . $item->id . '&extension=' . $extension); ?>" title="<?php
                                        echo Text::_('JACTION_EDIT'); ?> <?php
                                        echo $this->escape(addslashes($item->name)); ?>">
                                            <?php
                                            echo $editIcon; ?>&nbsp;<?php
                                            echo $this->escape($item->name); ?></a>
                                        <?php
                                    else : ?>
                                        <?php
                                        echo $this->escape($item->name); ?>
                                        <?php
                                    endif; ?>

                                    <span class="small" title="<?php
                                    echo $this->escape($item->path); ?>">
                                            <?php
                                            if (empty($item->note)) : ?>
                                                <?php
                                                echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                                                <?php
                                            else : ?>
                                                <?php
                                                echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>
                                                <?php
                                            endif; ?>
                                        </span>
                                </td>
                                <?php
                                /**
                                 * Images published, unpublished, archived, trashed
                                 * <?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_published')) : ?>
                                 * <td class="text-center btns d-none d-md-table-cell">
                                 * <a class="badge <?php echo ($item->count_published > 0) ? 'bg-success' : 'badge-secondary'; ?>" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_PUBLISHED_ITEMS'); ?>" href="<?php echo Route::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[gallery_id]=' . (int) $item->id . '&filter[published]=1' . '&filter[level]=1'); ?>">
                                 * <?php echo $item->count_published; ?></a>
                                 * </td>
                                 * <?php endif; ?>
                                 * <?php if (isset($this->items[0]) && property_exists($this->items[0], '                                        <?php
                                 * /**
                                 * ')) : ?>
                                 * <td class="text-center btns d-none d-md-table-cell">
                                 * <a class="badge <?php echo ($item->count_unpublished > 0) ? 'badge-danger' : 'badge-secondary'; ?>" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_UNPUBLISHED_ITEMS'); ?>" href="<?php echo Route::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[gallery_id]=' . (int) $item->id . '&filter[published]=0' . '&filter[level]=1'); ?>">
                                 * <?php echo $item->count_unpublished; ?></a>
                                 * </td>
                                 * <?php endif; ?>
                                 * <?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_archived')) : ?>
                                 * <td class="text-center btns d-none d-md-table-cell">
                                 * <a class="badge <?php echo ($item->count_archived > 0) ? 'badge-info' : 'badge-secondary'; ?>" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_ARCHIVED_ITEMS'); ?>" href="<?php echo Route::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[gallery_id]=' . (int) $item->id . '&filter[published]=2' . '&filter[level]=1'); ?>">
                                 * <?php echo $item->count_archived; ?></a>
                                 * </td>
                                 * <?php endif; ?>
                                 * <?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_trashed')) : ?>
                                 * <td class="text-center btns d-none d-md-table-cell">
                                 * <a class="badge <?php echo ($item->count_trashed > 0) ? 'badge-inverse' : 'badge-secondary'; ?>" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_TRASHED_ITEMS'); ?>" href="<?php echo Route::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[gallery_id]=' . (int) $item->id . '&filter[published]=-2' . '&filter[level]=1'); ?>">
                                 * <?php echo $item->count_trashed; ?></a>
                                 * </td>
                                 * <?php endif; ?>
                                 **/
                                ?>

                                <td class="text-center btns d-none d-md-table-cell itemnumber">
                                    <?php
                                    $link = Route::_("index.php?option=com_rsgallery2&view=Images&filter_gallery_id=" . $item->id);
                                    //$count = random_int (0, 2) ;
                                    $imageCount = 0;
                                    if (!empty($item->image_count)) {
                                        $imageCount = $item->image_count;
                                    }


                                    ?>
                                    <a class="btn <?php
                                    echo ($imageCount > 0) ? 'btn-success' : 'btn-secondary'; ?>" title="<?php
                                    echo Text::_('COM_RSGALLERY2_IMAGES_IN_GALLERY_COUNT_CLICK_TO_VIEW_THEM'); ?>" href="<?php
                                    echo $link; ?>">
                                        <?php
                                        echo $imageCount; ?></a>
                                </td>

                                <td class="small d-none d-md-table-cell">
                                    <?php
                                    // echo $this->escape($item->access); ?>
                                    <?php
                                    echo $this->escape($item->access_level); ?>
                                </td>

                                <td class="small d-none d-md-table-cell">
                                    <?php
                                    /**
                                     * ?>
                                     * <?php if ((int) $item->created_by != 0) : ?>
                                     * <a href="<?php echo Route::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>">
                                     * <?php echo $this->escape($item->author_name); ?>
                                     * </a>
                                     * <?php else : ?>
                                     * <?php echo Text::_('JNONE'); ?>
                                     * <?php endif; ?>
                                     * <?php if ($item->created_by_alias) : ?>
                                     * <div class="smallsub"><?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></div>
                                     * <?php endif; ?>
                                     * /**/
                                    ?>
                                    <?php
                                    echo $this->escape($created_by->name);

                                    if ($modified_by->name != $created_by->name) {
                                        echo '<br>(' . $modified_by->name . ')';
                                    }
                                    ?>

                                </td>

                                <td class="small d-none d-md-table-cell text-center">
                                    <?php
                                    $date = $item->created;
                                    echo $date > 0 ? HTMLHelper::_('date', $date, Text::_('DATE_FORMAT_LC4')) : '-';

                                    if ($item->modified != $item->created) {
                                        echo '<br>(';
                                        $date = $item->modified;
                                        echo $date > 0 ? HTMLHelper::_('date', $date, Text::_('DATE_FORMAT_LC4')) : '-';
                                        echo ')';
                                    }
                                    ?>
                                </td>

                                <td class="d-none d-lg-table-cell text-center">
                                        <span class="badge badge-info">
                                            <?php
                                            echo (int)$item->hits; ?>
                                        </span>
                                </td>


                                <td class="d-none d-md-table-cell">
                                    <?php
                                    echo (int)$item->id; ?>
                                </td>


                                <?php
                                /**
                                 *
                                 * if ($this->assoc) : ?>
                                 * <td class="d-none d-md-table-cell">
                                 * <?php if ($item->association) : ?>
                                 * <?php echo HTMLHelper::_('galleriesadministrator.association', $item->id, $extension); ?>
                                 * <?php endif; ?>
                                 * </td>
                                 * <?php endif; ?>
                                 * <?php if (Multilanguage::isEnabled()) : ?>
                                 * <td class="small d-none d-md-table-cell">
                                 * <?php echo LayoutHelper::render('joomla.content.language', $item); ?>
                                 * </td>
                                 * <?php endif; ?>
                                 * <td class="d-none d-md-table-cell">
                                 * <?php echo (int) $item->id; ?>
                                 * </td>
                                 */
                                ?>

                                <?php if ($this->isDevelop) : ?>
                                    <th scope="col" style="width:5%" class="d-none d-md-table-cell">
                                        <?php echo $item->lft; ?>
                                    </th>
                                <?php endif; ?>

                            </tr>
                            <?php
                        endforeach; ?>
                        </tbody>
                    </table>

                    <?php
                    // load the pagination. ?>
                    <?php
                    echo $this->pagination->getListFooter(); ?>

                    <?php
                    // Load the batch processing form. ?>
                    <?php
                    if (
                        $user->authorise('core.create', $extension)
                            && $user->authorise('core.edit', $extension)
                            && $user->authorise('core.edit.state', $extension)
                    ) : ?>
                        <?php
                        echo HTMLHelper::_(
                            'bootstrap.renderModal',
                            'collapseModal',
                            [
                                        'title'  => Text::_('COM_RSGALLERY2_GALLERY_BATCH_OPTIONS'),
                                        'footer' => $this->loadTemplate('batch_footer'),
                                ],
                            $this->loadTemplate('batch_body'),
                        ); ?>
                        <?php
                    endif; ?>
                    <?php
                endif; ?>

                <input type="hidden" name="extension" value="<?php
                echo $extension; ?>">
                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <?php
                echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
