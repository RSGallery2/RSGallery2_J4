<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
use Joomla\CMS\Changelog\Changelog;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
/**/

use Joomla\CMS\Factory;

echo 'default.php: ' . realpath(dirname(__FILE__));

$CssText = <<<'EOT'
$primary-color: #ccc;
$col-bg-color: #eee;
$col-footer-bg-color: #eee;
$col-header-bg-color: #007bff;
$col-content-bg-color: #fff;

body {
  background-color: $primary-color;
}  

.custom-column {  
  background-color: $col-bg-color;
  border: 5px solid $col-bg-color;    
  padding: 10px;
  box-sizing: border-box;  
}

.custom-column-header {
  font-size: 24px;
  background-color: #007bff;  
  color: white;
  padding: 15px;  
  text-align: center;
}

.custom-column-content {
  background-color: $col-content-bg-color;
  border: 2px solid white;  
  padding: 20px;  
}

.custom-column-footer {
  background-color: $col-footer-bg-color;   
  padding-top: 20px;
  text-align: center;
}
EOT;

$doc = Factory::getDocument();
$doc->addStyleDeclaration($CssText);

?>



<div class="container">
  <div class="row">
    <div class="col-sm-12 col-md-4">
      <div class="custom-column">
        <div class="custom-column-header">Header</div>
        <div class="custom-column-content">
          <ul class="list-group">
            <li class="list-group-item"><i class="fa fa-check"></i> Cras justo odio</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Dapibus ac facilisis in</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Morbi leo risus</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Porta ac consectetur ac</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Vestibulum at eros</li>
          </ul>
        </div>
        <div class="custom-column-footer"><button class="btn btn-primary btn-lg">Click here</button></div>
      </div>
    </div>
    <div class="col-sm-12 col-md-4">
      <div class="custom-column">
        <div class="custom-column-header">Header</div>
        <div class="custom-column-content">
          <ul class="list-group">
            <li class="list-group-item"><i class="fa fa-check"></i> Cras justo odio</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Dapibus ac facilisis in</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Morbi leo risus</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Porta ac consectetur ac</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Vestibulum at eros</li>
          </ul>
        </div>
        <div class="custom-column-footer"><button class="btn btn-primary btn-lg">Click here</button></div>
      </div>
    </div>
    <div class="col-sm-12 col-md-4">
      <div class="custom-column">
        <div class="custom-column-header">Header</div>
        <div class="custom-column-content">
          <ul class="list-group">
            <li class="list-group-item"><i class="fa fa-check"></i> Cras justo odio</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Dapibus ac facilisis in</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Morbi leo risus</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Porta ac consectetur ac</li>
            <li class="list-group-item"><i class="fa fa-check"></i> Vestibulum at eros</li>
          </ul>
        </div>
        <div class="custom-column-footer"><button class="btn btn-primary btn-lg">Click here</button></div>
      </div>
    </div>
  </div>
</div>


