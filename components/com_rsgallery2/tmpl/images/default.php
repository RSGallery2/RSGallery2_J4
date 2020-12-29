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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

// https://blog.kulturbanause.de/2014/09/responsive-images-srcset-sizes-adaptive/



HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));


echo '';

?>
<div class="rsg2__form rsg2_gallery-form">
    <form id="rsg2_gallery__form" action="<?php echo Route::_('index.php'); ?>" method="post" class="form-validate form-horizontal well">

        <h1> RSGallery2 "images" view </h1>
        <h2>Image Gallery</h2>

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

        <div id="rsg2_gallery" class="rsg2_gallery" >

            <div class="rsg2_gallery__images" id="gallery"  data-toggle="modal" data-target="#exampleModal">

                <?php
                /**/
                foreach ($this->items as $idx => $image) {
                ?>
                    <figure>
                            <img src="<?php echo $image->UrlThumbFile ?>"
                                 alt="<?php echo $image->name ?>"
                                 class="img-thumbnail rsg2_gallery__images_image"
                                 data-target="#carouselExample"
                                 data-slide-to="<?php echo $idx ?>"
                            >
                            <figcaption><?php echo $image->name; ?></figcaption>
                    </figure>
                <?php
                }
                /**/
                ?>

            </div>
        </div>
    </form>
</div>






