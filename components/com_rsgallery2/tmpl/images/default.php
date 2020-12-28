<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// https://blog.kulturbanause.de/2014/09/responsive-images-srcset-sizes-adaptive/

//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/upload.js', ['version' => 'auto', 'relative' => true]);

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
// var_dump($_SERVER);
echo '<h3> parent gallery: ' .$this->galleryId .'</h3><br>';
?>

    <button  type="button" class="btn btn-success">bootstrap </button>

    <div class="row">

        <?php
        foreach ($this->items as $image) {


        ?>
        <div class="col-md-4">
            <div class="thumbnail">
                <a href="<?php echo $image->UrlOriginalFile ?>" target="_blank">
                    <img src="<?php echo $image->UrlThumbFile ?>"
                         alt="<?php echo $image->name ?>"
                         style="width:100% height:100%"
                         class="img-thumbnail"
                    >
                    <div class="caption">
                        <p><?php echo $image->name; ?></p>
                    </div>
                </a>
            </div>
        </div>

        <?php
        }
        ?>
    </div>


   <?php

        //
        //
        //echo $this->item->event->afterDisplayContent;

        /**
        foreach ($this->items as $image) {

            //    echo 'image: ' . $image->name . '<br>';
            //    echo 'image: ' . $image->UrlDisplayFile . '<br>';


            //--- srcset: ----------------------------------

            // srcset="small.jpg 320w, medium.jpg 600w, large.jpg 900w"
            $smallestSize = 9999999999999;
            $srcSet = "";
            $UrlDisplayFiles = $image->UrlDisplayFiles;

            foreach (array_keys($UrlDisplayFiles) as $imageSize) {
                // ', ' in folloqwing item

                if (strlen($srcSet) > 0) {
                    $srcSet .= ', ';
                }

                $srcSet .= $UrlDisplayFiles [$imageSize] . " " . $imageSize . 'w';

                // prepare <img ..> for smallest image
                if ($smallestSize > $imageSize) {
                    $smallestSize = $imageSize;
                }
            }
            $smallestSizeUrl = $UrlDisplayFiles [$smallestSize];

            // ... html
        }
        /**/

        ?>

    <hr>

    <!--        <div class="col-md-4">-->
    <!--            <div class="thumbnail">-->
    <!--                <a href="--><?php //echo $image->UrlOriginalFile ?><!--" target="_blank">-->
    <!--                    <img src="--><?php //echo $smallestSizeUrl ?><!--"-->
    <!--                         alt="--><?php //echo $smallestSizeUrl ?><!--"-->
    <!--                         srcSet="--><?php //echo $srcSet; ?><!--"-->
    <!--                         style="width:100%">-->
    <!--                    <div class="caption">-->
    <!--                        <p>--><?php //echo $image->name ?><!--</p>-->
    <!--                    </div>-->
    <!--                </a>-->
    <!--            </div>-->
    <!--        </div>-->

    <?php

/**

    https://css-tricks.com/creating-a-modal-image-gallery-with-bootstrap-components/

  the Best form to hide and show a modal with bootstrap it's

// SHOW
$('#ModalForm').modal('show');
// HIDE
$('#ModalForm').modal('hide');

/**/

