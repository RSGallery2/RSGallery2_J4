<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/imagesProperties.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_rsgallery2/backend/imagesProperties.js', ['version' => 'auto', 'relative' => true]);

echo 'default.php: ' . realpath(dirname(__FILE__)) . '<br>';
?>



<form action="<?php echo Route::_('index.php?option=com_rsgallery2'); ?>"
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



			</div>
		</div>
	</div>

	<?php echo HTMLHelper::_('form.token'); ?>
</form>



