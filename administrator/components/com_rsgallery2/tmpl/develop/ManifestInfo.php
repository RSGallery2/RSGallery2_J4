<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2020 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.framework');

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=develop&layout=ManifestInfo'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>
		<div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
			<div id="j-main-container" class="j-main-container">

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'ManifestInfoView')); ?>

                <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ManifestInfoView', Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW', true)); ?>

                <p></p>
                <p><h3><?php echo Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW'); ?></h3></p>

                <?php

					try
					{
                        echo '<p><strong>DL DT DD definition</strong></p>';

                        echo '<section class="manifest_definition">';

//                        echo '<div class="container">';

                        echo '<div class="card-body">';
                        echo '<div class="card-text">';

                        echo '<dl class="row">';
                        foreach($this->rsg2Manifest as $key => $value) {

                            // Handle empty string
                            if (strlen($value) == 0) {
                                // $value = "''";
                                $value = '""';
                            }

                            echo '    <dt class="col-sm-1">' . $key . '</dt>';
                            echo '    <dd class="col-sm-11">' . $value . '</dd>';

                        }
                        echo '</dl>';

                        echo '</div>';
                        echo '</div>';

                        echo '</section';

                        //--- manifest data json string formatted ----------------------------------------------

                        echo '<p><strong>As json</strong></p>';

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


