<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;

// on develop show open tasks if existing
if (!empty ($this->isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: default_random images view<br>'
        . '* Change date format<br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
        . '</span><br><br>';
}

$layoutName = $this->getLayout();

// default is 'ImagesAreaJ3x.default'
if ($layoutName == 'default') {
    $layoutName = 'ImagesFramedAreaJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['images'] = $this->randomImages;
$displayData['params'] = $this->params->toObject();
$displayData['title']  = Text::_('COM_RSGALLERY2_RANDOM_IMAGES');

$displayData['isDebugSite']   = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

if (!empty($this->isDebugSite)) {
    echo '--- randomImages (3)' . '-------------------------------' . '<br>';
}
echo $layout->render($displayData);


/**
?>
<ul id="rsg2-galleryList">
	<li class="rsg2-galleryList-item">
		<table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tbody>
			<tr>
				<td colspan="3">Random images</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td align="center">
					<div class="shadow-box">
						<div class="img-shadow">
							<a href="/index.php/demo/demo-menu-root-galleries/item/6/asInline">
								<img src="http://rsgallery2.org/images/rsgallery/thumb/test_150x150.jpg.jpg" alt="test_150x150" width="80">
							</a>
						</div>
						<div class="rsg2-clr">
						</div>
						<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
					</div>
				</td>
				<td align="center">
					<div class="shadow-box">
						<div class="img-shadow">
							<a href="/index.php/demo/demo-menu-root-galleries/item/17/asInline">
								<img src="http://rsgallery2.org/images/rsgallery/original/154_5497.jpg" alt="154_5497" width="80">
							</a>
						</div>
						<div class="rsg2-clr">
						</div>
						<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
					</div>
				</td>
				<td align="center">
					<div class="shadow-box">
						<div class="img-shadow">
							<a href="/index.php/demo/demo-menu-root-galleries/item/62/asInline">
								<img src="http://rsgallery2.org/images/rsgallery/thumb/2015-10-11_00012-1.jpg.jpg" alt="2015-10-11_00012-1" width="80">
							</a>
						</div>
						<div class="rsg2-clr">
						</div>
						<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			</tbody>
		</table>
	</li>
</ul>
/**/
