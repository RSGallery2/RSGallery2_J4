<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;

// on develop show open tasks if existing
if (!empty ($this->isDevelopSite))
{
    echo '<span style="color:red">'
        . 'Task call latest images: <br>'
        . '* Format needs frame and others <br>'
        . '* <br>'
        . '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        . '</span><br><br>';
}



$layoutName = $this->getLayout();

// default is 'ImagesAreaJ3x.default'
if($layoutName == 'default') {

	$layoutName = 'ImagesFramedAreaJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['images'] = $this->latestImages;
$displayData['params'] = $this->params;
$displayData['title'] = Text::_('COM_RSGALLERY2_LATEST_IMAGES');

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

if (!empty($this->isDebugSite)) {
    echo '--- latestImages (3)' . '-------------------------------' . '<br>';
}

echo $layout->render($displayData);

/**
echo '--- latesImages (4)' . '-------------------------------' . '<br>';


<ul id="rsg2-galleryList">
	<li class="rsg2-galleryList-item">
		<table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tbody>
			<tr>
				<td colspan="3">Latest images</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td align="center">
					<div class="shadow-box">
						<div class="img-shadow">
							<a href="/index.php/demo/demo-menu-root-galleries/item/88/asInline">
								<img src="http://rsgallery2.org/images/rsgallery/original/dsc_5520.jpg" alt="dsc_5520" width="80">
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
							<a href="/index.php/demo/demo-menu-root-galleries/item/89/asInline">
								<img src="http://rsgallery2.org/images/rsgallery/original/dsc_5526.jpg" alt="dsc_5526" width="80">
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
							<a href="/index.php/demo/demo-menu-root-galleries/item/90/asInline">
								<img src="http://rsgallery2.org/images/rsgallery/original/dsc_5527.jpg" alt="dsc_5527" width="80">
							</a>
						</div>
						<div class="rsg2-clr"></div>
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

