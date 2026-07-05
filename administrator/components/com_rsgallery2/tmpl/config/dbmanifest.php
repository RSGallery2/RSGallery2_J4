<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */
/** @var \Rsgallery2\Component\Rsgallery2\Administrator\View\Config\HtmlView $this */
namespace Rsgallery2\Component\Rsgallery2\Administrator\Tmpl\Maintenance;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('bootstrap.framework');

//$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

/* Sort manifest variables */
$manifestVars = [];
foreach ($this->manifest as $name => $value) {
    $manifestVars [$name] = $value;
}
ksort($manifestVars);

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=config&layout=dbmanifest'); ?>"
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

<!--                --><?php //echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'ConfigRawView']); ?>
<!---->
<!--                --><?php //echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ConfigRawView', Text::_('COM_RSGALLERY2_DB_MAINIFEST', true)); ?>

                <p>
                    <h3><?php echo Text::_('COM_RSGALLERY2_DB_MAINIFEST'); ?></h3>
                </p>
                <legend><strong><?php echo Text::_('COM_RSGALLERY2_DB_MAINIFEST_DESC'); ?></strong></legend>

                <?php
                /**
                echo '<pre>';
                // Old RSG2 config vars echo json_encode(get_object_vars($configVars), JSON_PRETTY_PRINT);
                echo json_encode($configVars, JSON_PRETTY_PRINT);
                echo '</pre>';
                echo '<HR>';
                /**/
                // echo '<pre>';
                // Old RSG2 config vars echo json_encode(get_object_vars($configVars), JSON_PRETTY_PRINT);

                echo '<section class="config_raw">';

                echo '<div class="card-body">';
                echo '<div class="card-text">';

                echo '<dl class="row">';
                foreach ($manifestVars as $key => $value) {
                    // Handle empty string
                    if (strlen((string) $value) == 0) {
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

                //--- manifest data json string formatted ----------------------------------------------

                echo '<section class="config_raw">';

//                echo '<p><h3>' . Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW') . '</h3></p>';

                $json_string = json_encode($manifestVars, JSON_PRETTY_PRINT);

                echo '<div class="form-group  purple-border">';
                echo '    <legend><strong><label for="manifest_input">' . Text::_('RSGallery2 manifest as json') . '</label></strong></legend>';
                echo '    <textarea class="form-control manifest_input" id="manifest_input"  cols="40" rows="18" readonly >';
                echo $json_string . '";';
                echo '     </textarea>';
                echo '</div>';

                echo '</div>';
                echo '</div>';

                ?>

<!--                --><?php //echo HTMLHelper::_('bootstrap.endTab'); ?>
<!---->
<!--                --><?php //echo HTMLHelper::_('bootstrap.endTabSet'); ?>


                <?php
                // <!--input type="hidden" name="option" value="com_rsgallery2" />
                // <input type="hidden" name="rsgOption" value="maintenance" /-->
                // <input type="hidden" name="task" value="" /> ?>
                <?php echo HTMLHelper::_('form.token'); ?>

            </div>
        </div>
    </div>

    <input type="hidden" name="task" value=""/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
