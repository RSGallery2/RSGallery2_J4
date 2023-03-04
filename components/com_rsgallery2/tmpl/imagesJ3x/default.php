<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\FileLayout;

// https://blog.kulturbanause.de/2014/09/responsive-images-srcset-sizes-adaptive/

// ToDo:
// ToDo:

HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/j3x.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/rsgallery.css', array('version' => 'auto', 'relative' => true));



echo '';
// on develop show open tasks if existing
if (!empty ($this->isDevelopSite))
{
    echo '<span style="color:red">'
        . 'Tasks: imagesJ3x view<br>'
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

//if ($this->config->displaySearch) {
if (true) {
    $layout = new FileLayout('Search.search');
    echo $layout->render();
}

$layout = new FileLayout('ImagesArea.default');

$displayData['images'] = $this->items;
$displayData['galleryId'] = $this->galleryId;
$displayData['imageId'] = $this->imageId;

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

// return;

?>

<div class="rsg2__form rsg2__images_area">
    <form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images'); ?>" method="post" class="form-validate form-horizontal well">

        <?php if (!empty($this->isDebugSite)): ?>
            <h1> RSGallery2 "images list" view </h1>
        <?php endif; ?>

        <?php if (!empty($this->isDebugSite)): ?>
            <hr>
        <?php endif; ?>

	    <?php
	    echo $layout->render($displayData);
	    ?>


    </form>
</div>






