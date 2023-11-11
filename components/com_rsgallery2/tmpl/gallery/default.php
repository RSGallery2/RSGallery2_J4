<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


//  J3x legacy view default => gallery images


\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\FileLayout;

// https://kulturbanause.de/blog/responsive-images-srcset-sizes-adaptive/

// ToDo:
// ToDo:

//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));
// ToDo: use but change $this->document->getWebAssetManager()->useStyle('com_rsgallery2.site.images');
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.site.galleryJ3x');

$layoutName = $this->getLayout();

// default is 'ImagesAreaJ3x.default'
if($layoutName == 'default') {

    // Auto layout => Flex
    if ($this->params->get('images_column_arrangement_j3x') == 0)
    {
	    $layoutName = 'ImagesFlexJ3x.default';
    }
    else
    {
        // Standard j3x layout
	    // $layoutName = 'ImagesArea.default';
	    $layoutName = 'ImagesAreaJ3x.default';

	    // test layout II
	    // $layoutName = 'ImagesFramedAreaJ3x.default';
    }
}

$layout = new FileLayout($layoutName);


//$displayData['images'] = $this->images;
//$displayData['pagination'] = $this->pagination;
//echo $layout->render($displayData);

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

$displayData['images'] = $this->items;
$displayData['params'] = $this->params->toObject();
//$displayData['menuParams'] = $this->menuParams;
$displayData['pagination'] = $this->pagination;

$displayData['gallery'] = $this->gallery;
$displayData['galleryId'] = $this->galleryId;

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

$displaySearch = $this->params->get('displaySearch', false);
if ($displaySearch) {
    $searchLayout = new FileLayout('Search.search');
    // $searchData['options'] = $searchOptions ...; // gallery
}

?>

<!-- ToDo: is form here needed ? check core ...  -->
<!-- ToDo: form link ...  -->
<form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=gallery'); ?>"
      method="post" class="form-validate form-horizontal well">

    <div class="rsg2__form rsg2__images_area">

            <?php if (!empty($this->isDebugSite)): ?>
                <h3><?php echo text::_('RSGallery2 "gallery j3x legacy standard (default)"'); ?> view </h3>
                <div><?php
                    echo ' GID: ' . $this->galleryId; ?>
                </div>
                <hr>
            <?php endif; ?>

            <?php //--- display search ---------- ?>

            <?php if ($displaySearch): ?>
                <?php echo $searchLayout->render(); ?>
            <?php endif; ?>

            <?php //--- display gallery images ---------- ?>

            <?php echo $layout->render($displayData); ?>


	        <?php
            // if ($displaySearch): show_pagination "2" / show_pagination_results "1"
            // limit 20
            // total 19
            ?>

            <?php
                // echo $this->pagination->getListFooter(); ?>
    	    <?php
    	    // endif;
            ?>



    </div>
</form>






