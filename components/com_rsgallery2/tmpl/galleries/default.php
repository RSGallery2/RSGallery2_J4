<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
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


echo '';
// on develop show open tasks if existing
if (!empty ($this->isDevelopSite))
{
    echo '<span style="color:red">'
        . 'Tasks: <br>'
        . '* extract image and modal slider into layouts to be called<br>'
        . '* make rsgConfig global<br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        . '</span><br><br>';
}

//if ($this->config->displaySearch) {
if (true) {
    $layout = new FileLayout('Search.search');
    echo $layout->render();
}

//foreach ($this->items as $gallery) {
//
//    echo 'gallery: ' . $gallery->name . '<br>';
//
//}

$layout = new FileLayout('ImagesArea.default');

$displayData['images'] = $this->items;

?>
<div class="rsg2__form rsg2__galleries_thumbs">
    <form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images'); ?>" method="post" class="form-validate form-horizontal well">

        <?php echo '<h1> RSGallery2 "galleries" view </h1>';?>
        <h2>Thumbs of galleries</h2>

        <hr>

        <?php
        echo $layout->render($displayData);
        ?>





    </form>
</div>

