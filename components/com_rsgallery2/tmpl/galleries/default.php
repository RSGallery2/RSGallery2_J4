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
    $layout = new FileLayout('Test.search');
    echo $layout->render();
}

echo '<h1> RSGallery2 "galleries" view </h1>';

foreach ($this->items as $gallery) {

    echo 'gallery: ' . $gallery->name . '<br>';

}



?>
<div class="rsg2__form rsg2_gallery-form">
    <form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images'); ?>" method="post" class="form-validate form-horizontal well">

        <h1> RSGallery2 "images" view </h1>
        <h2>Image Gallery</h2>

        <hr>





    </form>
</div>

