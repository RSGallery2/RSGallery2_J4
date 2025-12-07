<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Tmpl\Configj3x;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('bootstrap.framework');

//$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

?>
<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=configJ3x'); ?>"
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

                <?php echo '<h3>Config J3x default</h3>' . '<br>';
                echo 'default.php: ' . realpath(dirname(__FILE__)) . '<br>';
                ?>


            </div>
        </div>
    </div>

// HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/images.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/backend/images.js', ['version' => 'auto', 'relative' => true]);

?>
<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=configJ3x'); ?>"
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

                <?php echo '<h3>Config J3x default</h3>' . '<br>';
                echo 'default.php: ' . realpath(dirname(__FILE__)) . '<br>';
                ?>

    <input type="hidden" name="task" value=""/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


