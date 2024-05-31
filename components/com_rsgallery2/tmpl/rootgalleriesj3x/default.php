<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2024 RSGallery2 Team 
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

//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/j3x.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/rsgallery.css', array('version' => 'auto', 'relative' => true));
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.site.galleryJ3x');

//--- determine layout -------------------------------------------------

$layoutName = $this->getLayout();

// default is 'ImagesAreaJ3x.default'
if($layoutName == 'default') {

	$layoutName = 'GalleriesAreaJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['galleries'] = $this->items;
$test = $this->params->toObject();
$displayData['params'] = $this->params->toObject();
//$displayData['menuParams'] = $this->menuParams;
$displayData['pagination'] = $this->pagination;

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

$params = $this->params;

// $displaySearch = $this->menuParams->get('displaySearch')
$displaySearch = $this->params->get('displaySearch');
$displaySearch = $this->params->get('displaySearch', false);
if ($displaySearch) {
	$searchLayout = new FileLayout('Search.search');
	// $searchData['options'] = $searchOptions ...; // gallery
}

$displayLatest = $this->params->get('displayLatest');
$displayLatest = $this->params->get('displayLatest');
$displayRandom = $this->params->get('displayRandom');
$displayRandom = $this->params->get('displayRandom');

?>
<!-- ToDo: is form here needed ? check core ...  -->
<!-- ToDo: form link ...  -->
<form id="rsg2_root_galleryJ3x__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=rootgalleriesj3x'); ?>"
      method="post" class="form-validate form-horizontal well">

	<div class="rsg2__form rsg2__galleries_area">

	    <?php if (!empty($isDebugSite)): ?>
	        <h5>RSGallery2 "j3x legacy" root gallery and latest galleries overview </h5>
	    <?php endif; ?>

		<?php //--- display search ---------- ?>

		<?php if ($displaySearch): ?>
			<?php echo $searchLayout->render(); ?>
		<?php endif; ?>


        <?php //--- display root galleries ---------- ?>

		<?php echo $layout->render($displayData); ?>

	    <?php if (!empty($this->isDebugSite)): ?>
			<hr>
	    <?php endif; ?>


		<div class="rsg2-clr"></div>

		<?php //--- display latest images ---------- ?>

		<?php if ($displayLatest): ?>
			<?php echo $this->loadTemplate('latest_images'); ?>
		<?php endif; ?>

		<?php //--- display random images ---------- ?>

		<?php if ($displayRandom): ?>
			<?php echo $this->loadTemplate('random_images'); ?>
		<?php endif; ?>

	</div>
</form>
