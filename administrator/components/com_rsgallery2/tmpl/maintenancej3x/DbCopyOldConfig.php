<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.framework');






?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbCopyOldConfig'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="col-md-2">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>
		<div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
			<div id="j-main-container" class="j-main-container">

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'DbCopyOldConfig')); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DbCopyOldConfig', JText::_('COM_RSGALLERY2_COPY_OLD_CONFIG', true)); ?>

                <legend><strong><?php echo JText::_('COM_RSGALLERY2_COPY_OLD_CONFIG'); ?></strong></legend>

				<?php if (! count ($this->configVarsOld)) : ?>
					<div class="alert alert-info">
						<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('COM_RSGALLERY2_NO_GALLERY_CREATED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
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
                            <th scope="col" style="width:100px" class="text-center d-none d-md-table-cell">
                                <?php
                                // echo HTMLHelper::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2');
                                echo 'Old Name';
                                ?>
                            </th>
                            <th scope="col" style="width:5%" class="text-center">
                                <?php
                                //echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder);
                                echo 'Old Value';
                                ?>
                            </th>
                            <th scope="col" style="min-width:100px">
                                <?php
                                //echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder);
                                echo 'New name';
                                ?>
                            </th>

                            <th scope="col" style="min-width:5%">
                                <?php
                                //echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder);
                                echo 'New value';
                                ?>
                            </th>

                        </tr>
					</thead>
					<tbody>

        			<?php

					try
					{

                        //foreach ($this->configVarsOld as $oldName => $oldValue)
                        foreach ($this->configVarsMerged as $idx => $mergedName)
                        {
                            ?>

							<!-- tr class="row<?php echo $idx % 2; ?>" > -->
							<tr>
                                <td class="text-center">
	    							<?php echo HTMLHelper::_('grid.id', $idx, $mergedName); ?>
                                </td>
                                <td class="order text-center d-none d-md-table-cell">
                                    <?php echo "{" . $mergedName . "}"; ?>
                                </td>
                                <td class="order text-center d-none d-md-table-cell">
                                    <?php echo $idx; ?>
                                </td>
                                <td class="order text-center d-none d-md-table-cell">
                                    <?php echo "{" . $mergedName . "}"; ?>
                                </td>
                                <td class="order text-center d-none d-md-table-cell">
	                                <?php echo $idx; ?>
                                </td>
                            </tr>
                            <?php
                        }


					}
					catch (RuntimeException $e)
					{
						$OutTxt = '';
						$OutTxt .= 'Error rawEdit view: "' . 'DbCopyOldConfig' . '"<br>';
						$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
					
						$app = Factory::getApplication();
						$app->enqueueMessage($OutTxt, 'error');
					}

				    ?>
					</tbody>
				</table>

				<?php endif; ?>

				<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

				<?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_rsgallery2" />
				<input type="hidden" name="rsgOption" value="maintenance" /-->

				<input type="hidden" name="task" value="" />
				<?php echo HTMLHelper::_('form.token'); ?>
            </div>
		</div>
	</div>

	<?php echo HTMLHelper::_('form.token'); ?>
</form>


