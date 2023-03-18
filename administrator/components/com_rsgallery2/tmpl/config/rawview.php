<?php // no direct access
/**
* @package       RSGallery2
* @copyright (C) 2003-2023 RSGallery2 Team
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

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=config&layout=RawView'); ?>"
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

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'ConfigRawView')); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ConfigRawView', Text::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_VIEW', true)); ?>

                <p></p>
                <p><h3><?php echo Text::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_VIEW'); ?></h3></p>

				<?php
                /**
				echo '<pre>';
				// Old RSG2 config vars echo json_encode(get_object_vars($this->configVars), JSON_PRETTY_PRINT);
				echo json_encode($this->configVars, JSON_PRETTY_PRINT);
				echo '</pre>';
                echo '<HR>';
                /**/
				// echo '<pre>';
				// Old RSG2 config vars echo json_encode(get_object_vars($this->configVars), JSON_PRETTY_PRINT);

                echo '<section class="config_raw">';

                echo '<div class="card-body">';
                echo '<div class="card-text">';

                echo '<dl class="row">';
                foreach ($this->configVars as  $key => $value)  {

                    // Handle empty string
                    if (strlen($value) == 0) {
                        // $value = "''";
                        $value = '""';
                    }

                    echo '    <dt class="col-sm-3">' . $key . '</dt>';
                    echo '    <dd class="col-sm-9">' . $value . '</dd>';

                }
                echo '</dl>';

                echo '</div>';
                echo '</div>';

                echo '</section>';

                //--- show json string formatted ----------------------------------------------

                $json_string = json_encode($this->configVars, JSON_PRETTY_PRINT);

                echo '<p><strong>As json</strong></p>';

                echo '<div class="form-group  purple-border">';
                echo '    <label for="usr">RSGallery2 Configuration</label>';
                echo '    <textarea class="form-control manifest_input" id="manifest_input"  cols="40" rows="40" readonly >';
                echo             $json_string . '";';
                echo '     </textarea>';
                echo '</div>';

                ?>

				<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

				<?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>


                <?php
					// <!--input type="hidden" name="option" value="com_rsgallery2" />
				    // <input type="hidden" name="rsgOption" value="maintenance" /-->
                    // <input type="hidden" name="task" value="" /> ?>
				<?php echo HTMLHelper::_('form.token'); ?>

            </div>
		</div>
	</div>

    <input type="hidden" name="task" value="" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
