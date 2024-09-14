<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

//if ($this->item->params->get('show_name')) {
//	if ($this->Params->get('show_rsgallery2_name_label')) {
//		echo Text::_('COM_RSGALLERY2_NAME') . $this->item->name;
//	} else {
//		echo $this->item->name;
//	}
//}
//
//echo $this->item->event->afterDisplayTitle;
//echo $this->item->event->beforeDisplayContent;
//echo $this->item->event->afterDisplayContent;

echo '<h1> RSGallery2 "images random" view </h1>';

echo 'parent gallery: ' . $this->galleryId . '<br>';

foreach ($this->items as $image) {
    echo 'image: ' . $image->name . '<br>';
}

//
//
//echo $this->item->event->afterDisplayContent;

?>





