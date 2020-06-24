<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\String\Inflector;

HTMLHelper::_('behavior.multiselect');

$user      = Factory::getUser();
$userId    = $user->get('id');
$extension = $this->escape($this->state->get('filter.extension'));
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'a.lft' && strtolower($listDirn) == 'asc');
$parts     = explode('.', $extension, 2);
$component = $parts[0];
$section   = null;

/**/
if (count($parts) > 1)
{
	$section = $parts[1];

	$inflector = Inflector::getInstance();

	if (!$inflector->isPlural($section))
	{
		$section = $inflector->toPlural($section);
	}
}
/**/

if ($saveOrder && !empty($this->items))
{
	$saveOrderingUrl = 'index.php?option=com_rsgallery2&task=galleries.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images'); ?>"
       method="post" name="adminForm" id="adminForm">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="col-md-2">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>
		<div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>">
			<div id="j-main-container" class="j-main-container">
				<?php
				// Search tools bar
//				echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				?>
				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('COM_RSGALLERY2_NO_IMAGE_UPLOADED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
                    </div>
				<?php else : ?>
					<table class="table" id="galleryList">
						<caption id="captionTable" class="sr-only">
							<?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
						</caption>
						<thead>
							<tr>
                                <td style="width:1%" class="text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
								<th scope="col" style="width:1%" class="text-center d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', '', 's.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
								</th>
								<th scope="col" style="width:1%" class="text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
								</th>


                                <!--th scope="col" style="min-width:100px">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_TITLE', 'a.name', $listDirn, $listOrder); ?>
                                </th-->
                                <th scope="col" style="min-width:100px">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_GALLERY', 'gallery_name', $listDirn, $listOrder); ?>
                                </th>

                                <th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_ORDER', 'a.ordering', $listDirn, $listOrder); ?>
                                </th>

                                <!--th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                                </th-->

                                <th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
                                </th>


                                <th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_DATE_CREATED', 'a.created', $listDirn, $listOrder); ?>
                                </th>

                                <th scope="col" style="width:3%" class="d-none d-lg-table-cell text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
                                </th>



                                <th scope="col" style="width:5%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_VOTES', 'a.votes', $listDirn, $listOrder); ?>
                                </th>

                                <th scope="col" style="width:5%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_RATING', 'a.rating', $listDirn, $listOrder); ?>
                                </th>

                                <th scope="col" style="width:5%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_COMMENTS', 'a.comments', $listDirn, $listOrder); ?>
                                </th>


                                <th scope="col" style="width:5%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                                <?php
                                /**


                                <?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_published')) : ?>
									<th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
										<span class="icon-publish hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_PUBLISHED_ITEMS'); ?>"></span>
										<span class="sr-only"><?php echo Text::_('COM_RSGALLERY2_COUNT_PUBLISHED_ITEMS'); ?></span>
									</th>
								<?php endif; ?>
								<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_unpublished')) : ?>
									<th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
										<span class="icon-unpublish hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_UNPUBLISHED_ITEMS'); ?>"></span>
										<span class="sr-only"><?php echo Text::_('COM_RSGALLERY2_COUNT_UNPUBLISHED_ITEMS'); ?></span>
									</th>
								<?php endif; ?>
								<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_archived')) : ?>
									<th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
										<span class="icon-archive hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_ARCHIVED_ITEMS'); ?>"></span>
										<span class="sr-only"><?php echo Text::_('COM_RSGALLERY2_COUNT_ARCHIVED_ITEMS'); ?></span>
									</th>
								<?php endif; ?>
								<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_trashed')) : ?>
									<th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
										<span class="icon-trash hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_RSGALLERY2_COUNT_TRASHED_ITEMS'); ?>"></span>
										<span class="sr-only"><?php echo Text::_('COM_RSGALLERY2_COUNT_TRASHED_ITEMS'); ?></span>
									</th>
								<?php endif; ?>
								<th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
								</th>
								<?php if ($this->assoc) : ?>
									<th scope="col" style="width:10%" class="d-none d-md-table-cell">
										<?php echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
									</th>
								<?php endif; ?>
								<?php if (Multilanguage::isEnabled()) : ?>
									<th scope="col" style="width:10%" class="d-none d-md-table-cell">
										<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
									</th>
								<?php endif; ?>
                                 */
                                ?>


                            </tr>
						</thead>



						<tbody <?php if ($saveOrder) :?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false"<?php endif; ?>>
							<?php 
							foreach ($this->items as $i => $item) : ?>
								<?php
								// Get permissions
								$canEdit    = $user->authorise('core.edit',       $extension . '.gallery.' . $item->id);
								$canCheckin = $user->authorise('core.admin',      'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
								$canEditOwn = $user->authorise('core.edit.own',   $extension . '.gallery.' . $item->id) && $item->created_by == $userId;
								$canChange  = $user->authorise('core.edit.state', $extension . '.gallery.' . $item->id) && $canCheckin;

								?>


								<tr class="row<?php echo $i % 2; ?>" >
                                    <td class="text-center d-none d-md-table-cell">
										<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                    </td>
									<td class="order text-center d-none d-md-table-cell">
										<?php
										$iconClass = '';
										if (!$canChange)
										{
											$iconClass = ' inactive';
										}
										elseif (!$saveOrder)
										{
											$iconClass = ' inactive tip-top hasTooltip" title="' . HTMLHelper::_('tooltipText', 'JORDERINGDISABLED');
										}
										?>
										<span class="sortable-handler<?php echo $iconClass ?>">
											<span class="fa fa-ellipsis-v" aria-hidden="true"></span> 
										</span>
										<?php if ($canChange && $saveOrder) : ?>
											<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order">
										<?php endif; ?>
									</td>
									<td class="text-center">
										<div class="btn-group">
											<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'images.', $canChange); ?>
										</div>
									</td>
									<th scope="row">
										<?php if ($item->checked_out) : ?>
											<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'imgages.', $canCheckin); ?>
										<?php endif; ?>
										<?php if ($canEdit || $canEditOwn) : ?>
											<?php $editIcon = $item->checked_out ? '' : '<span class="fa fa-pencil-square mr-2" aria-hidden="true"></span>'; ?>
											<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_rsgallery2&task=image.edit&id=' . $item->id . '&extension=' . $extension); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape(addslashes($item->name)); ?>">
												<?php echo $editIcon; ?><?php echo $this->escape($item->name); ?></a>
										<?php else : ?>
											<?php echo $this->escape($item->name); ?>
										<?php endif; ?>
                                    </th>
                                    <td class="text-center btns d-none d-md-table-cell itemnumber">
									<div class="pull-left break-word">
                                       <?php
										$link = JRoute::_("index.php?option=com_rsgallery2&view=image&task=image.edit&id=" . $item->id);
										//$link = JRoute::_("index.php?option=com_rsgallery2&amp;rsgOption=images&amp;task=editA&amp;hidemainmenu=1&amp;id=" . $item->id);
										if ($canEdit)
										{
											echo '<a href="' . $link . '"">' . $this->escape($item->name) . '</a>';
										}
										else
										{
											echo $this->escape($item->name);
										}
										?>
									</div>
                                    </td>

                                    <td class="small d-none d-md-table-cell">
									<?php
									$link = JRoute::_("index.php?option=com_rsgallery2&view=gallery&task=gallery.edit&id=" . $item->gallery_id);
									//$link = JRoute::_("index.php?option=com_rsgallery2&rsgOption=galleries&task=editA&hidemainmenu=1&id=". $item->gallery_id);
									//echo '<a href="' . $link . '"">' . $item->gallery_id . '</a>';
									echo '<a href="' . $link . '"">' . $this->escape($item->gallery_name) . '</a>';
									?>
                                    </td>

                                    <td class="small d-none d-md-table-cell">
										<?php echo $item->ordering; ?>
                                    </td>

                                    <!--td class="small d-none d-md-table-cell">
										<?php echo $item->access; ?>
                                    </td-->




                                    <td class="small d-none d-md-table-cell">
                                        <?php
                                        /**
                                        ?>
										<?php if ((int) $item->created_by != 0) : ?>
                                            <a href="<?php echo Route::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>">
												<?php echo $this->escape($item->author_name); ?>
                                            </a>
										<?php else : ?>
											<?php echo Text::_('JNONE'); ?>
										<?php endif; ?>
										<?php if ($item->created_by_alias) : ?>
                                            <div class="smallsub"><?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></div>
										<?php endif; ?>
                                        /**/
                                        ?>
                                        <?php echo $this->escape($item->created_by); ?>
                                    </td>

                                    <td class="small d-none d-md-table-cell text-center">
										<?php
										//$date = $item->{$orderingColumn};
										$date = $item->created;
										echo $date > 0 ? HTMLHelper::_('date', $date, Text::_('DATE_FORMAT_LC4')) : '-';
										?>
                                    </td>

                                    <td class="d-none d-lg-table-cell text-center">
									    <span class="badge badge-info">
										    <?php echo (int) $item->hits; ?>
									    </span>
                                    </td>



                                    <td class="d-none d-md-table-cell">
										<?php echo (int) $item->votes; ?>
                                    </td>

                                    <td class="d-none d-md-table-cell">
										<?php echo (int) $item->rating; ?>
                                    </td>

                                    <td class="d-none d-md-table-cell">
										<?php echo (int) $item->comments; ?>
                                    </td>

                                    <td class="d-none d-md-table-cell">
										<?php echo (int) $item->id; ?>
                                    </td>


                                    <?php
                                    /**

                                    if ($this->assoc) : ?>
										<td class="d-none d-md-table-cell">
											<?php if ($item->association) : ?>
												<?php echo HTMLHelper::_('galleriesadministrator.association', $item->id, $extension); ?>
											<?php endif; ?>
										</td>
									<?php endif; ?>
									<?php if (Multilanguage::isEnabled()) : ?>
										<td class="small d-none d-md-table-cell">
											<?php echo LayoutHelper::render('joomla.content.language', $item); ?>
										</td>
									<?php endif; ?>
									<td class="d-none d-md-table-cell">
										<?php echo (int) $item->id; ?>
									</td>
                                     */
                                    ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php // load the pagination. ?>
					<?php echo $this->pagination->getListFooter(); ?>

					<?php /* // Load the batch processing form. ?>
					<?php if ($user->authorise('core.create', $extension)
						&& $user->authorise('core.edit', $extension)
						&& $user->authorise('core.edit.state', $extension)) : ?>
						<?php echo HTMLHelper::_(
							'bootstrap.renderModal',
							'collapseModal',
							array(
								'title'  => Text::_('COM_RSGALLERY2_BATCH_OPTIONS'),
								'footer' => $this->loadTemplate('batch_footer'),
							),
							$this->loadTemplate('batch_body')
						); ?>
					<?php endif; */?>
				<?php endif; ?>

				<input type="hidden" name="extension" value="<?php echo $extension; ?>">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="boxchecked" value="0">
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>

