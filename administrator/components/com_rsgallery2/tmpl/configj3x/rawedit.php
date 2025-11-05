<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2023-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('behavior.formvalidator');

//$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

/* Sort config variables */
$configVars = [];
foreach ($this->configVars as $name => $value) {
    $configVars [$name] = $value;
}
ksort($configVars);

/**
 * Echos an input field for config variables
 *
 * @param   string  $name   name of config variable
 * @param   string  $value  of config variable
 *
     * @since 5.1.0
 */
function configInputField($name = 'unknown', $value = '')
{
    try {
        if (!is_string($name)) {
            $name = 'configInputField: Name is not a string';
        }

        if (!is_string($value)) {
            if (gettype($value) == 'array') {
                $value = implode(',', $value);
            } else {
                if (gettype($value) == 'integer' || gettype($value) == 'boolean') {
                    $value = (string)$value;
                } else {
                    $value = 'Value type is ' . gettype($value) . ' and not a string';
                }
            }
        }

        ?>

		<div class="control-group">
			<div class="control-label">
				<label id="jform_<?php echo $name ?>-lbl" class="jform_control-label"
				       for="jform_<?php echo $name ?>"><?php echo $name ?>:</label>
			</div>
			<div class="controls">
				<input id="jform_<?php echo $name ?>" class="input-xxlarge input_box" type="text"
				       value="<?php echo $value ?>" size="70" name="jform[<?php echo $name ?>] aria-invalid=" false">
			</div>
		</div>

        <?php
        /*
        <div class="control-group">
            <label class="control-label" for="<?php echo $name?>"><?php echo $name?>:</label>
            <div class="controls">
                <input id="<?php echo $name?>" class="input-xxlarge input_box" type="text"
                    value="<?php echo $value?>" size="70" name="<?php echo $name?>">
            </div>
        </div>

        <td>version</td>
        <td>
            <input type="text" value="4.1.0" name="version">
        </td>
        */
    } catch (\RuntimeException $e) {
        $OutTxt = '';
        $OutTxt .= 'Error rawEdit view: "' . 'configInputField' . '"<br>';
        $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

        $app = Factory::getApplication();
        $app->enqueueMessage($OutTxt, 'error');
    }
}

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEdit'); ?>"
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

                <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'ConfigRawView']); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ConfigRawView', Text::_('COM_RSGALLERY2_CONFIG_J3X_RAW_EDIT', true)); ?>

				<div class="card">
					<div class="card-body">
						<legend><?php echo Text::_('COM_RSGALLERY2_CONFIG_J3X_RAW_EDIT_DESC'); ?></legend>

                        <?php

                        try {
                            /**/
                            foreach ($configVars as $name => $value) {
                                configInputField($name, $value);
                            }
                        } catch (\RuntimeException $e) {
                            $OutTxt = '';
                            $OutTxt .= 'Error rawEdit view: "' . 'configInputField' . '"<br>';
                            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                            $app = Factory::getApplication();
                            $app->enqueueMessage($OutTxt, 'error');
                        }

                        ?>

					</div>
				</div>

                <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

                <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_rsgallery2" />
                <input type="hidden" name="rsgOption" value="maintenance" /-->

			</div>
		</div>

		<input type="hidden" name="task" value=""/>
        <?php echo HTMLHelper::_('form.token'); ?>
	</div>

</form>


