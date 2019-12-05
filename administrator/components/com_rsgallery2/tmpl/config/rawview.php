<?php // no direct access
/**
* @package       RSGallery2
* @copyright (C) 2003-2018 RSGallery2 Team
* @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
*/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.framework');

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=config&layout=RawView'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="col-md-2">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>

		<div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
			<div id="j-main-container" class="j-main-container">

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'ConfigRawView')); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ConfigRawView', JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_VIEW', true)); ?>

                <legend><strong><?php echo JText::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_VIEW'); ?></strong></legend>

                <p><h3>RAW view</h3></p>

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

                echo '<div class="container-fluid">';
                    echo '<div class="row">';
                    //echo '<dl class="row">';
                    echo '<dl class="dl-horizontal">';

                    foreach ($this->configVars as  $key => $value)  {

                            // echo '<div>Key: <strong>' . $key . '</strong> value:'  . $value . '</div>';
    	    				echo '<dt>' . $key . '</dt> <dd>'  . $value . '</dd>';
                            //echo '<dt class="col-sm-2">' . $key . ':</dt><dd class="col-sm-10">'  . $value . '</dd>';
                            //echo '<dt class="col-sm-2 col-md-1">' . $key . ':</dt><dd class="col-sm-10 col-md-11">'  . $value . '</dd>';
                            //echo '<dt class="col-sm-2 col-lg-1">' . $key . ':</dt><dd class="col-sm-10 col-lg-11">'  . $value . '</dd>';


                    }
                    echo '</dl>';
    				echo '</div>';

				//echo '</pre>';
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
