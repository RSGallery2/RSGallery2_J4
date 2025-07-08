<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (c)  2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('bootstrap.framework');

//$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

?>
<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=config'); ?>"
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

                <?php echo '<h3>Config default</h3>' . '<br>';
                echo 'default.php: ' . realpath(dirname(__FILE__)) . '<br>';
                ?>


			</div>
		</div>
	</div>

	<input type="hidden" name="task" value=""/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


