<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2024 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('bootstrap.framework');

//HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/images.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/backend/images.js', ['version' => 'auto', 'relative' => true]);

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=develop&layout=Rsg2GeneralInfo'); ?>"
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

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'Rsg2GeneralInfoView')); ?>

                <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'Rsg2GeneralInfoView', Text::_('COM_RSGALLERY2_GENERAL_INFO_VIEW', true)); ?>

                <p>
                    <button id="copy_to_clipboard" type="button" class="btn btn-info btn-rsg2 btn-file w-25"
                            title="<?php echo Text::_('COM_RSGALLERY2_COPY_TO_CLIPBOARD_DESC'); ?>"
                            disabled
                    >
                        <span class="icon-attachment" aria-hidden="true"></span>
                        <?php echo Text::_('COM_RSGALLERY2_COPY_TO_CLIPBOARD'); ?>
                    </button>
                </p>
                <p></p>
                <?php

					try
					{

                        //--- configuration json string formatted ----------------------------------------------

                        echo '<p><h3>' . Text::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_VIEW') . '</h3></p>';

                        $json_string = json_encode($this->rsg2Configuration, JSON_PRETTY_PRINT);

                        echo '<div class="form-group  purple-border">';
                        echo '    <label for="configuration_input">RSGallery2 configuration</label>';
                        echo '    <textarea class="form-control configuration_input" id="configuration_input"  cols="40" rows="30" readonly >';
                        echo             $json_string . '";';
                        echo '     </textarea>';
                        echo '</div>';

                        //--- manifest data json string formatted ----------------------------------------------

                        echo '<p><h3>' . Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW') . '</h3></p>';

                        $json_string = json_encode($this->rsg2Manifest, JSON_PRETTY_PRINT);

                        echo '<div class="form-group  purple-border">';
                        echo '    <label for="manifest_input">RSGallery2 manifest</label>';
                        echo '    <textarea class="form-control manifest_input" id="manifest_input"  cols="40" rows="15" readonly >';
                        echo             $json_string . '";';
                        echo '     </textarea>';
                        echo '</div>';

                        //--- show json string formatted ----------------------------------------------

                        //echo '<p> RSG2 Version: ' . $this->rsg2Manifest->version . '</p>';
                        echo '<p> RSG2 Version: <strong>' . $this->rsg2Manifest['version'] . '</strong></p>';

                        //--- configuration j3x json string formatted ----------------------------------------------

                        if ( ! empty($this->rsg2Configuration_j3x)) {
                            echo '<p><h3>' . Text::_('COM_RSGALLERY2_CONFIGURATION_3x') . '</h3></p>';

                            $json_string = json_encode($this->rsg2Configuration_j3x, JSON_PRETTY_PRINT);

                            echo '<div class="form-group  purple-border">';
                            echo '    <label for="configuration_input_3x">RSGallery2 configuration</label>';
                            echo '    <textarea class="form-control configuration_input" id="configuration_input_3x"  cols="40" rows="30" readonly >';
                            echo $json_string . '";';
                            echo '     </textarea>';
                            echo '</div>';
                        }

                    }
					catch (\RuntimeException $e)
					{
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

				<input type="hidden" name="task" value="" />
				<?php echo HTMLHelper::_('form.token'); ?>
            </div>
		</div>
	</div>

	<?php echo HTMLHelper::_('form.token'); ?>
</form>


