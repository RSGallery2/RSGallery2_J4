<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
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




<button type="submit" class="btn btn-success" onclick="Joomla.submitbutton('imagesProperties.PropertiesView');return false;">
	<?php echo JText::_('COM_RSGALLERY2_ADD_IMAGE_PROPERTIES'); ?>
</button>
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('images.moveImagesTo');return false;">
	<?php echo JText::_('COM_RSGALLERY2_MOVE_TO'); ?>
</button>
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('images.copyImagesTo');return false;">
	<?php echo JText::_('COM_RSGALLERY2_COPY'); ?>
</button>