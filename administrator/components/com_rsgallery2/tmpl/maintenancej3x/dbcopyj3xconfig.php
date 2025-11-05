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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

//HTMLHelper::_('bootstrap.framework');

// on more use preset ....
$this->document->getWebAssetManager()->useStyle('com_rsgallery2.backend.dbCopyJ3xConfig');

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbcopyj3xconfig'); ?>"
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

				<?php //echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'DbCopyJ3xConfig')); ?>

				<?php //echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DbCopyJ3xConfig', Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG', true)); ?>

				<legend><strong><?php echo Text::_('COM_RSGALLERY2_COMPARE_AND_COPY_J3X_CONFIG'); ?></strong></legend>

				<?php if (! count ($this->j3xConfigItems)) : ?>
					<div class="alert alert-info">
						<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_('COM_RSGALLERY2_J3X_RSG2_TABLES_NOT_EXISTING'); // JGLOBAL_NO_MATCHING_RESULTS ?>
					</div>
                <?php else : ?>
					<table class="table" id="galleryList">
						<caption id="captionTable" class="sr-only">
                            <?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
						</caption>
						<thead>
						<tr>
							<td style="width:3%" class="text-center  configCheck">
                                <?php echo HTMLHelper::_('grid.checkall'); ?>
							</td>
							<th scope="col" class="text-center d-none d-md-table-cell configIndexHeader">
                                <?php
                                // echo HTMLHelper::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2');
                                echo 'Index';
                                ?>
							</th>
							<th scope="col" class="text-center configNameHeader">
                                <?php
                                //echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder);
                                echo 'Name';
                                ?>
							</th>
							<th scope="col" class="text-center configValueOldHeader">
                                <?php
                                //echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder);
                                echo 'Old Value';
                                ?>
							</th>

							<th scope="col" class="text-center configValueNewHeader">
                                <?php
                                //echo HTMLHelper::_('searchtools.sort', 'COM_RSGALLERY2_NAME', 'a.name', $listDirn, $listOrder);
                                echo 'New value';
                                ?>
							</th>

						</tr>
						</thead>
						<tbody>

                        <?php

                        try {
                            echo '<tr>';
                            echo '    <th colspan="4">';
                            echo '       ' . Text::_('COM_RSGALLERY2_CFG_J3X_ASSISTED');
                            echo '    </th>';
                            echo '</tr>';

                            // ToDo: Section ? List of special transfer ....
                            // for all ...

                            echo '<tr>';
                            echo '    <th colspan="4">';
                            echo '       ' . Text::_('COM_RSGALLERY2_CFG_J3X_MERGE_1TO1');
                            echo '    </th>';
                            echo '</tr>';

                            //foreach ($this->j3xConfigItems as $oldName => $oldValue)
                            $idx        = 0;
                            $NotDefined = '{Not defined}';

                            // ToDo: only name in $this->j4xConfigItemsMerged
                            // ToDo: list for special transfer values

                            // section new config elements (matching to old)
                            foreach ($this->mergedItems as $mergedName => $mergedValue) {
//	                        if (! isset ($this->j3xConfigItems[$mergedName]))
//	                        {
//		                        continue;
//	                        }

                                /**
	                        $valOld = $this->j3xConfigItems [$mergedName] ?? $NotDefined;
	                        $valNew = $this->j4xConfigItems[$mergedName]  ?? $NotDefined;
                            /**/
                                $valOld = $this->j3xConfigItems [$mergedName];
                                $valNew = $this->j4xConfigItems[$mergedName];
                                /**/
                                // Make empty string visible
                                $valOld = strlen($valOld) > 0 ? trim($valOld) : '""';
                                $valNew = strlen($valNew) > 0 ? $valNew : '""';
                                /**/

                                ?>
								<!-- tr class="row<?php echo $idx % 2; ?>" > -->
								<tr>
									<td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $idx, $mergedName); ?>
									</td>
									<td class="order text-center d-none d-md-table-cell">
                                        <?php echo $idx; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell configMatching">
                                        <?php echo $mergedName; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell configValueOld">
                                        <?php echo $valOld; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell">
                                        <?php echo $valNew; ?>
									</td>
								</tr>
                                <?php

                                $idx++;
                            }

                            echo '<tr>';
                            echo '    <th colspan="4">';
                            echo '       ' . Text::_('COM_RSGALLERY2_CFG_J3X_UNTOUCHED');
                            echo '    </th>';
                            echo '</tr>';

                            // Section old config elements not having new partner
                            foreach ($this->untouchedJ3xItems as $mergedName => $mergedValue) {
                                /**
                                 * $valOld     = $this->j3xConfigItems [$mergedName] ?? $NotDefined;
                                 * $valNew     = $this->j4xConfigItems[$mergedName]  ?? $NotDefined;
                                 * /**/
                                $valOld = $valOld = $this->j3xConfigItems [$mergedName];
                                $valNew = '%';

                                // Make empty string visible
                                $valOld = strlen($valOld) > 0 ? $valOld : '""';
                                $valNew = strlen($valNew) > 0 ? $valNew : '""';

                                ?>
								<!-- tr class="row<?php echo $idx % 2; ?>" > -->
								<tr>
									<td class="text-center">
									<?php // echo HTMLHelper::_('grid.id', $idx, $mergedName); ?>
									</td>
									<td class="order text-center d-none d-md-table-cell">
                                        <?php echo '(' . $idx . ')'; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell configNotMatching">
                                        <?php echo $mergedName; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell configValueOld">
                                        <?php echo $valOld; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell">
                                        <?php echo $valNew; ?>
									</td>
								</tr>
                                <?php

                                $idx++;
                            }

                            echo '<tr>';
                            echo '    <th colspan="4">';
                            echo '       ' . Text::_('COM_RSGALLERY2_CFG_J4X_UNTOUCHED');
                            echo '    </th>';
                            echo '</tr>';

                            // Section old config elements not having new partner
                            foreach ($this->untouchedJ4xItems as $mergedName => $mergedValue) {
                                /**
                                 * $valOld     = $this->j3xConfigItems [$mergedName] ?? $NotDefined;
                                 * $valNew     = $this->j4xConfigItems[$mergedName] ?? $NotDefined;
                                 * /**/
                                $valOld = '%';
                                $valNew = $this->j4xConfigItems[$mergedName];

                                // Make empty string visible
                                $valOld = strlen($valOld) > 0 ? $valOld : '""';
                                $valNew = strlen($valNew) > 0 ? $valNew : '""';

                                ?>
								<!-- tr class="row<?php echo $idx % 2; ?>" > -->
								<tr>
									<td class="text-center">
									<?php // echo HTMLHelper::_('grid.id', $idx, $mergedName); ?>
									</td>
									<td class="order text-center d-none d-md-table-cell">
                                        <?php echo '(' . $idx . ')'; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell configOnlyNew">
                                        <?php echo $mergedName; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell configValueOld">
                                        <?php echo $valOld; ?>
									</td>
									<td class="order text-center d-none d-md-table-cell">
                                        <?php echo $valNew; ?>
									</td>
								</tr>
                                <?php

                                $idx++;
                            }
                        } catch (\RuntimeException $e) {
                            $OutTxt = '';
                            $OutTxt .= 'Error rawEdit view: "' . 'DbCopyJ3xConfig' . '"<br>';
                            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                            $app = Factory::getApplication();
                            $app->enqueueMessage($OutTxt, 'error');
                        }

                        ?>
						</tbody>
					</table>

                <?php endif; ?>

				<?php //echo HTMLHelper::_('bootstrap.endTab'); ?>

				<?php //echo HTMLHelper::_('bootstrap.endTabSet'); ?>

			</div>
		</div>
	</div>

	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="task" value=""/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


