<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2021 - 2020
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

//$images = $displayData['images'];
extract($displayData); // $images
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . '* Slide image J3x Tasks: <br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* modal image (->slider)<br>'
        . '* length of filenames<br>'
        . '* what happens on empty galleries/ image lists<br>'
        . '* Size of replace images (missing/no images) <br>'
        . '* translate Slideshow in all layouts ...<br>'
        . '* <br>'
        . '* <br>'
        . '* <br>'
        . '* <br>'
        . '</span><br><br>';
}


//--- sanitize URLs -----------------------------------

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.svg';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.svg';

// assign dummy images if not found
foreach ($images as $idx => $image) {

    // show dummy thumb on galleries with no images
    if (! empty($image->isHasNoImages))
    {
        $image->UrlOriginalFile = $noImageUrl;
        $image->UrlDisplayFiles = $noImageUrl;;
        $image->UrlThumbFile = $noImageUrl;

    }

//    else {
//
//        if (!$image->isOriginalFileExist) {
//            $image->UrlOriginalFile = $missingUrl;
//            ;
//        }
//
//        if (!$image->isDisplayFileExist) {
//            $image->UrlDisplayFiles = $missingUrl;;
//        }
//
//        if (!$image->isThumbFileExist) {
//            $image->UrlThumbFile = $missingUrl;
//        }
//
//    }
}


?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 J3x slide image layout</h3>
    <hr>
<?php endif; ?>


<div class="rsg2">
    <?php if (true || $menuParams->galleries_show_slideshow): ?>
        <div class="rsg2_slideshow_link">
            <a href="<?php echo $gallery->UrlSlideshow?>">
                Slideshow
            </a>
        </div>
    <?php endif; ?>

    <?php echo $this->render('image'); ?>
    <?php echo $this->render('properties'); ?>


    <div class="rsg_sem_inl">
        <div class="rsg_sem_inl_Nav">
            top pagination
        </div>
        <div class="rsg_sem_inl_Nav">
            bottom pagination
            <div align="center">
                <div class="pagination">
                    <nav role="navigation"
                         aria-label="Pagination">
                        <ul class="pagination-list">
                            <li>
                                <a title="Start"
                                   href="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=114&amp;gid=1&amp;limitstart=0"
                                   class="pagenav"
                                   aria-label="Go to start page">
											<span class="icon-first"
                                                  aria-hidden="true"/>
                                </a>
                            </li>
                            <li>
                                <a title="Prev"
                                   href="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=114&amp;gid=1&amp;limitstart=5"
                                   class="pagenav"
                                   aria-label="Go to prev page">
											<span class="icon-previous"
                                                  aria-hidden="true"/>
                                </a>
                            </li>
                            <li class="hidden-phone">
                                <a title="1"
                                   href="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=114&amp;gid=1&amp;limitstart=0"
                                   class="pagenav"
                                   aria-label="Go to page 1">1</a>
                            </li>
                            <li class="hidden-phone">
                                <a title="2"
                                   href="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=114&amp;gid=1&amp;limitstart=1"
                                   class="pagenav"
                                   aria-label="Go to page 2">2</a>
                            </li>
                            <li class="hidden-phone">
                                <a title="3"
                                   href="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=114&amp;gid=1&amp;limitstart=2"
                                   class="pagenav"
                                   aria-label="Go to page 3">3</a>
                            </li>
                            <li class="hidden-phone">
                                <a title="4"
                                   href="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=114&amp;gid=1&amp;limitstart=3"
                                   class="pagenav"
                                   aria-label="Go to page 4">4</a>
                            </li>
                            <li class="hidden-phone">
                                <a title="5"
                                   href="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=114&amp;gid=1&amp;limitstart=4"
                                   class="pagenav"
                                   aria-label="Go to page 5">...</a>
                            </li>
                            <li class="hidden-phone">
                                <a title="6"
                                   href="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;Itemid=114&amp;gid=1&amp;limitstart=5"
                                   class="pagenav"
                                   aria-label="Go to page 6">6</a>
                            </li>
                            <li class="active hidden-phone">
                                <a aria-current="true"
                                   aria-label="Page 7">7</a>
                            </li>
                            <li class="disabled">
                                <a>
											<span class="icon-next"
                                                  aria-hidden="true"/>
                                </a>
                            </li>
                            <li class="disabled">
                                <a>
											<span class="icon-last"
                                                  aria-hidden="true"/>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

    </div>
    <div class="rsg_sem_inl_footer">
        <div id="rsg2-footer">
            <br>
            <br>com_rsgallery2 4.5.2.0<br>(c) 2005-2021 RSGallery2 Team		</div>
        <div class="rsg2-clr">&nbsp;</div>
    </div>
</div>

