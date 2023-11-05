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

// https://blog.kulturbanause.de/2014/09/responsive-images-srcset-sizes-adaptive/

// ToDo:
// ToDo:

//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));
$this->document->getWebAssetManager()->useStyle('com_rsgallery2.site.images');


echo '';
// on develop show open tasks if existing
if (!empty ($this->isDevelopSite))
{
    echo '<span style="color:red">'
        . 'Tasks: gallery view<br>'
        . '* <br>'
        . '* make rsgConfig global<br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        . '</span><br><br>';
}

//$displayData['images'] = $this->images;
//$displayData['pagination'] = $this->pagination;
//echo $layout->render($displayData);

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

$layout = new FileLayout('ImagesArea.default');

$displayData['images'] = $this->items;
$displayData['params'] = $this->params->toObject();
//$displayData['menuParams'] = $this->menuParams;
$displayData['pagination'] = $this->pagination;

//$displayData['gallery'] = $this->gallery;
//$displayData['galleryId'] = $this->galleryId;

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

$displaySearch = $this->params->get('displaySearch', false);
if ($displaySearch) {
    $searchLayout = new FileLayout('Search.search');
    // $searchData['options'] = $searchOptions ...; // gallery
}

?>

<div class="rsg2__form rsg2__images_area">
    <form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=gallery'); ?>" method="post" class="form-validate form-horizontal well">

        <?php if (!empty($this->isDebugSite)): ?>
            <h1> RSGallery2 "gallery images" view </h1>
        <?php endif; ?>

        <?php if (!empty($this->isDebugSite)): ?>
    	    <h1><?php echo text::_('RSGallery2 "gallery j3x legacy standard"'); ?> view </h1>
            <hr>
        <?php endif; ?>

	    <?php //--- display search ---------- ?>

	    <?php if ($displaySearch): ?>
		    <?php echo $searchLayout->render(); ?>
	    <?php endif; ?>

	    <?php //--- display gallery images ---------- ?>

	    <?php echo $layout->render($displayData); ?>

    </form>
</div>






