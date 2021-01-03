<?php
/**
/**
 * @package     RSGallery2
 * @subpackage  mod_rsg2_images
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Helper\ModuleHelper;
use Rsgallery2\Module\Rsg2_images\Site\Helper\Rsg2_imagesHelper;

echo "<h1>mod_rsg2_images.php</h1>";

$model = $app->bootComponent('com_rsgallery2')->getMVCFactory()->createModel('Images', 'Site', ['ignore_request' => true]);

$list = Rsg2_imagesHelper::getList($params, $model, $app);

// standard display
// require ModuleHelper::getLayoutPath('mod_rsg2_images', $params->get('layout', 'default'));

// $lang = Factory::getLanguage();
$lang =  $app->getLanguage();
//$lang->load('com_rsgallery2', JPATH_SITE, 'en-GB', true);
//$lang->load('com_rsgallery2', JPATH_SITE, $lang->getTag(), true);
$lang->load('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');

// Test
//$layout = new FileLayout('Test.search');
$layout    = new FileLayout('components.com_rsgallery2.layouts.Test.search', JPATH_SITE);
//echo $tabLayout->render(array('id' => $id, 'active' => $active, 'title' => $title));
echo $layout->render();

