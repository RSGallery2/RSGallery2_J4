<?php
/**
/**
 * @package     RSGallery2
 * @subpackage  mod_rsg2_images
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Helper\ModuleHelper;
use Rsgallery2\Module\Rsg2_images\Site\Helper\Rsg2_imagesHelper;

HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));

// echo "<h1>mod_rsg2_images.php</h1>";

$model = $app->bootComponent('com_rsgallery2')->getMVCFactory()->createModel('Images', 'Site', ['ignore_request' => true]);
$images = Rsg2_imagesHelper::getList($params, $model, $app);

// standard display
// require ModuleHelper::getLayoutPath('mod_rsg2_images', $params->get('layout', 'default'));

// $lang = Factory::getApplication->getLanguage();
// toDO:
$lang =  $app->getLanguage();
//$lang->load('com_rsgallery2', JPATH_SITE, 'en-GB', true);
//$lang->load('com_rsgallery2', JPATH_SITE, $lang->getTag(), true);
$lang->load('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');

// Test
//$layout = new FileLayout('Test.search');
$layoutSearch    = new FileLayout('components.com_rsgallery2.layouts.Search.search', JPATH_SITE);
$layoutImages    = new FileLayout('components.com_rsgallery2.layouts.ImagesArea.default', JPATH_SITE);
//echo $tabLayout->render(array('id' => $id, 'active' => $active, 'title' => $title));
// echo $layout->render();

$displayData['images'] = $images;

// return;

?>

<div class="rsg2__mod rsg2__image_area">

		<h1> Modul RSGallery2 "images" view </h1>

		<hr>

	<?php
	echo $layoutSearch->render($displayData);
	?>
	<hr>
	<?php
	echo $layoutImages->render($displayData);
	?>

</div>
