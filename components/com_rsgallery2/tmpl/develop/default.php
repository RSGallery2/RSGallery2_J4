<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Language\Text;

//if ($this->item->params->get('show_name')) {
//  if ($this->Params->get('show_rsgallery2_name_label')) {
//      echo Text::_('COM_RSGALLERY2_NAME') . $this->item->name;
//  } else {
//      echo $this->item->name;
//  }
//}
//
//echo $this->item->event->afterDisplayTitle;
//echo $this->item->event->beforeDisplayContent;
//echo $this->item->event->afterDisplayContent;

echo '<h1> RSGallery2 "develop" view </h1>';

//echo '<dl class="xrowx">';
//foreach ($this->routeResults as $route => $routeResult) {
//
//  echo '<dt class="xcol-xsm-3x>' . $route . '</dt>';
//  echo '<dd class="xcol-xsm-9">' . $routeResult . '</dd>';
//
//
//}
//
echo '<dl>';
foreach ($this->routeResults as $routePure => $routeResult) {
    echo '<dt>' . 'Route:     ' . $routePure . '</dt>';
    echo '<dd>' . 'Route sef: ' . $routeResult . '</dd>';
}

echo '</dl>';
echo '<hr>';

foreach ($this->routeResults as $routePure => $routeResult) {
    echo 'Route org&nbsp;: ' . '<a href="' . $routePure . '">' . $routePure . '</a>';
    echo '<br>';
    echo 'Route sef:&nbsp;' . '<a href="' . $routeResult . '">' . $routeResult . '</a>';
}

echo '<hr>';
echo '<hr>';

//$routeResult = 'http://127.0.0.1/Joomla5x/index.php/rsg2-develop.html?view=galleryj3x&id=4';
//$routeResult = 'http://127.0.0.1/Joomla5x/index.php/rsg2-develop/galleryj3x/4:dummy-gallery';
//$routeResult = 'http://127.0.0.1/Joomla5x/index.php/rsg2-develop?galleryj3x/4:dummy-gallery';
//$routeResult = 'http://127.0.0.1/Joomla5x/index.php/rsg2-galleryj3x/4:dummy-gallery';
$routeResult = 'http://127.0.0.1/Joomla5x/index.php/rsg2-develop/galleryj3x/4:dummy-gallery';
$routeResult = 'http://127.0.0.1/Joomla5x/index.php/rsg2-develop/view=galleryj3x/4:dummy-gallery';
echo 'Route sim:&nbsp;' . '<a href="' . $routeResult . '">' . $routeResult . '</a>';

// <dl class="row">
//  <dt class="col-sm-3">Description lists</dt>
//  <dd class="col-http://127.0.0.1/Joomla5x/index.php/rsg2-develop.html?view=galleryj3x&id=4sm-9">A description list is perfect for defining terms.</dd>
