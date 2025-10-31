<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

//HTMLHelper::_('bootstrap.framework');

//$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=develop&layout=defaultparams'); ?>"
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

                <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'PreparedButNotReady']); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'PreparedButNotReady', Text::_('RSG2 Parameter', true)); ?>
				<p></p>
				<legend><strong><?php
                        // echo Text::_('COM_RSGALLERY2_MAINT_PREPARED_NOT_READY_DESC');
                        echo 'Check extension parameter: use before update ==> exchange config.xml and then check again';

                        ?></strong></legend>
				<p>
				<h3><?php
                    // echo Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW');
                    ?></h3></p>

                <?php

                try {
                    ?>
					<h2>RSG2 Parameter</h2>

					<p>Default: from XML, Actual: RSG2, Merged: Add default to actual</p>

					<table class="table table-striped">
						<thead>
						<tr>
							<th scope="col">Name</th>
							<th>Default</th>
							<th>&lt;=&gt;</th>
							<th>Actual</th>
							<th>Merged</th>
						</tr>
						</thead>

						<tbody>

                        <?php
                        foreach ($this->mergedParams as $mergeName => $mergedValue) {
                            $defaultValue = "%";
                            if (!empty ($this->defaultParams[$mergeName])) {
                                $defaultValue = $this->defaultParams[$mergeName];
                            }

                            $actualValue = "%";
                            if (!empty ($this->actualParams[$mergeName])) {
                                $actualValue = $this->actualParams[$mergeName];
                            }

                            if (empty ($mergedValue)) {
                                $mergedValue = '%';
                            }

                            $delta = '';
                            if ($defaultValue != $actualValue) {
                                $delta = '&lt;=&gt;';
                            }

                            ?>

							<tr>
								<td>
                                    <?php echo $mergeName ?>
								</td>
								<td>
                                    <?php echo $defaultValue ?>
								</td>
								<td>
                                    <?php echo $delta ?>
								</td>
								<td>
                                    <?php echo $actualValue ?>
								</td>
								<td>
                                    <?php echo $mergedValue ?>
								</td>
							</tr>

                            <?php
                        }
                        ?>

						</tbody>
					</table>

                    <?php
                } catch (\RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error rawEdit view: "' . 'PreparedButNotReady' . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                ?>

                <?php

                try {
                    ?>
					<h2>RSG2 surplus (old) parameter</h2>

					<p>Additinal parameter which will be removed</p>

					<table class="table table-striped">
						<thead>
						<tr>
							<th scope="col">Name</th>
							<th>Actual</th>
						</tr>
						</thead>

						<tbody>

                        <?php
                        foreach ($this->actualParams as $oldName => $oldValue) {
                            if (empty ($this->defaultParams[$mergeName])) {
                                ?>

								<tr>
									<td>
                                        <?php echo $oldName ?>
									</td>
									<td>
                                        <?php echo $oldValue ?>
									</td>
								</tr>

                                <?php
                            }
                        }
                        ?>

						</tbody>
					</table>

                    <?php
                } catch (\RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error rawEdit view: "' . 'PreparedButNotReady' . '"<br>';
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


