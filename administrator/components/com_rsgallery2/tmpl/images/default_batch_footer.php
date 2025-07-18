<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

// ?? wa script see article

?>

<button type="button" class="btn btn-secondary" data-dismiss="modal">
	<?php echo Text::_('JCANCEL'); ?>
</button>
<!--button type="submit" id='batch-submit-button-id' class="btn btn-success" data-submit-task='image.batch'>
	<?php echo Text::_('JGLOBAL_BATCH_PROCESS'); ?>
</button-->

<!--button type="submit" class="btn btn-success" onclick="Joomla.submitbutton('imagesProperties.PropertiesView');return false;">
	<?php echo Text::_('COM_RSGALLERY2_ADD_IMAGE_PROPERTIES'); ?>
</button-->
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('images.moveImagesToGallery');return false;">
	<?php echo Text::_('COM_RSGALLERY2_MOVE'); ?>
</button>
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('images.copyImagesToGallery');return false;">
	<?php echo Text::_('COM_RSGALLERY2_COPY'); ?>
</button>
