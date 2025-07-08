<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */


//  J3x legacy view => slideshow

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;

// https://blog.kulturbanause.de/2014/09/responsive-images-srcset-sizes-adaptive/

// ToDo:
// ToDo:

HTMLHelper::_('stylesheet', 'com_rsgallery2/site/slideshow.css', ['version' => 'auto', 'relative' => true]);


echo '';
// on develop show open tasks if existing
if (!empty ($this->isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: slideshow view<br>'
        . '* user: order of images ?<br>'
        . '* make rsgConfig global<br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        . '</span><br><br>';
}

$layoutName = $this->getLayout();

// default is 'ImagesAreax.default'
if ($layoutName == 'default') {
    $layoutName = 'Slideshow.default';
}

$layout = new FileLayout($layoutName);

$displayData['images'] = $this->items;
$displayData['params'] = $this->params->toObject();
//$displayData['menuParams'] = $this->menuParams;

$displayData['isDebugSite']   = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;


?>

<form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=slideshow'); ?>" method="post"
      class="form-validate form-horizontal well">

    <div class="rsg2__form rsg2__slide_area">

        <?php if (!empty($this->isDebugSite)): ?>
            <h1> Menu RSGallery2 "slideshow" view </h1>
            <hr>
        <?php endif; ?>

        <?php echo $layout->render($displayData); ?>

    </div>
</form>

