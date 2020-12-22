<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

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

echo '<h1> RSGallery2 "images" view </h1>';

?>
<div class="container">
    <h2>Image Gallery</h2>
    <p>The .thumbnail class can be used to display an image gallery.</p>
    <p>The .caption class adds proper padding and a dark grey color to text inside thumbnails.</p>
    <p>Click on the images to enlarge them.</p>
<?php
echo 'parent gallery: ' .$this->galleryId .'<br>';
?>
    <div class="row">

<?php
foreach ($this->items as $image) {

//    echo 'image: ' . $image->name . '<br>';
//    echo 'image: ' . $image->UrlDisplayFile . '<br>';

?>
        <div class="col-md-4">
            <div class="thumbnail">
                <a href="<?php echo $image->UrlOriginalFile ?>" target="_blank">
                    <img src="<?php echo $image->UrlDisplayFile ?>" alt="Lights" style="width:100%">
                    <div class="caption">
                        <p><?php echo $image->name ?></p>
                    </div>
                </a>
            </div>
        </div>
<?php
}

//
//
//echo $this->item->event->afterDisplayContent;

?>

