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

//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/j3x.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/rsgallery.css', array('version' => 'auto', 'relative' => true));
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.site.galleryJ3x');



//if ($this->item->params->get('show_name')) {
//
//	if ($this->Params->get('show_rsgallery2_name_label')) {
//		echo Text::_('COM_RSGALLERY2_NAME') . $this->item->name;
//	} else {
//		echo $this->item->name;
//	}
//}
//
//echo $this->item->event->afterDisplayTitle;
//echo $this->item->event->beforeDisplayContent;
//
// echo '<h1> RSGallery2 "legacy" view </h1>';
//
//
//echo $this->item->event->afterDisplayContent;


// on develop show open tasks if existing
if (!empty ($this->isDevelopSite))
{
    echo '<span style="color:red">'
        . 'Tasks: rootgalleriesJ3x view<br>'
        . '* User limit selection box -> layout ? Nbr of galleries  -> yes no ?  <br>'
        . '* Format of date is already in database -> improve ... <br>'
        . '* Events in general<br>'
        . '* User count of galleries displayed not working: 0, 1,2,3<br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        . '</span><br><br>';
}


if ($this->menuParams->get('displaySearch')) {
    $searchLayout = new FileLayout('Search.search');
    // $searchData['options'] = $searchOptions ...; // gallery
}

$layoutName = $this->getLayout();

// default is 'ImagesAreaJ3x.default'
if($layoutName == 'default') {

	$layoutName = 'GalleriesAreaJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['galleries'] = $this->items;
$test = $this->params->toObject();
$displayData['params'] = $this->params->toObject();
$displayData['menuParams'] = $this->menuParams;
$displayData['pagination'] = $this->pagination;

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

?>
<!-- ToDo: is form here needed ? check core ...  -->
<!-- ToDo: form link ...  -->
<form id="rsg2_root_galleryJ3x__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=RootgalleriesJ3x'); ?>"
      method="post" class="form-validate form-horizontal well">

	<div class="rsg2__form rsg2__galleries_area">

	    <?php if (!empty($isDebugSite)): ?>
	        <h2>RSGallery2 "j3x legacy" root gallery and latest galleries overview </h2>
	    <?php endif; ?>

		<?php //--- display search ---------- ?>

		<?php if ($this->menuParams->get('displaySearch')): ?>
			<?php echo $searchLayout->render(); ?>
		<?php endif; ?>


		<?php //--- display root galleries ---------- ?>

		<?php echo $layout->render($displayData); ?>

	    <?php if (!empty($this->isDebugSite)): ?>
			<hr>
	    <?php endif; ?>


		<div class="rsg2-clr"></div>

		<?php //--- display latest images ---------- ?>

		<?php if ($this->menuParams->displayLatest): ?>
			<?php echo $this->loadTemplate('latest_images'); ?>
		<?php endif; ?>

		<?php //--- display random images ---------- ?>

		<?php if ($this->menuParams->displayRandom): ?>
			<?php echo $this->loadTemplate('random_images'); ?>
		<?php endif; ?>

	</div>
</form>
