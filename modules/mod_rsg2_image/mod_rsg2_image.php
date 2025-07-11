<?php
/**
 * @package       RSGallery2
 * @subpackage    mod_rsg2_image
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;
use Rsgallery2\Module\Rsg2_image\Site\Helper\Rsg2_imageHelper;

HTMLHelper::_('stylesheet', 'com_rsgallery2/site/image.css', ['version' => 'auto', 'relative' => true]);

echo "<h1>mod_rsg2_image.php</h1>";

// $model = $app->bootComponent('com_rsgallery2')->getMVCFactory()->createModel('Images', 'Site', ['ignore_request' => true]);
// $images = Rsg2_imagesHelper::getList($params, $model, $app);
$model = $app->bootComponent('com_rsgallery2')->getMVCFactory()->createModel(
    'Image',
    'Site',
    ['ignore_request' => true],
);
//$imgId = $params->get('layout', 'default');
$imgId = $params->get('SelectImage', '0');
$image = Rsg2_imageHelper::getItem($imgId);

// Return on no image found
if (empty($image)) {
    // toDo: enqueue message ...
    echo '<strong>mod_rsg2_image: Image with id= ' . $imgId . ' not found<strong>';

    return;
}

Rsg2_imageHelper::AssignImageUrl($image);


// standard display
// require ModuleHelper::getLayoutPath('mod_rsg2_image', $params->get('layout', 'default'));

// $lang = Factory::getApplication->getLanguage();
// toDO:
$lang = $app->getLanguage();
//$lang->load('com_rsgallery2', JPATH_SITE, 'en-GB', true);
//$lang->load('com_rsgallery2', JPATH_SITE, $lang->getTag(), true);
$lang->load('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');

// Test
//$layout = new FileLayout('Test.search');
//$layoutSearch    = new FileLayout('components.com_rsgallery2.layouts.Search.search', JPATH_SITE);
$layoutImage = new FileLayout('components.com_rsgallery2.layouts.ImageSingle.default', JPATH_SITE);
//echo $tabLayout->render(array('id' => $id, 'active' => $active, 'title' => $title));
// echo $layout->render();

$displayData['image'] = $image;

// return;

?>

<div class="rsg2__mod rsg2__image_area">

    <h1> Module RSGallery2 "image" view </h1>

    <hr>

    <?php
    if (!empty($image)) {
        echo $layoutImage->render($displayData);
    }

    ?>

</div>
