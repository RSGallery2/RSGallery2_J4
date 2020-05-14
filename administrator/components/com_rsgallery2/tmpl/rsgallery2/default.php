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
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Response\JsonResponse;
/**/
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

use Joomla\CMS\Language\Text;

//HTMLHelper::_('behavior.formvalidator');
//HTMLHelper::_('behavior.keepalive');

JHtml::_('stylesheet', 'com_rsgallery2/controlPanel.css', array('version' => 'auto', 'relative' => true));

HTMLHelper::_('script', 'mod_quickicon/quickicon.min.js', ['version' => 'auto', 'relative' => true]);



?>
<form action="<?php echo Route::_('index.php?option=com_rsgallery2'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">
    <div class="row">
        <?php if (false) : // ToDo: Remove this quick hack. do not show sidebar
            //if (!empty($this->sidebar)) :
            ?>
        <div id="j-sidebar-container" class="col-md-2">
            <?php echo $this->sidebar; ?>
        </div>
        <?php endif; ?>
        <div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
            <div id="j-main-container" class="j-main-container">

                <?php

                //--- Logo -----------------------------

                DisplayRSG2Logo();

                //--- Control buttons ------------------

                DisplayRSG2ControlButtons($this->buttons);

 //               echo '<hr>';

                //--- RSG2 info table -----------------------------

                // DisplayInfoRsgallery2($this->Rsg2Version);
                // ToDo: use real version
                DisplayInfoRsgallery2($this->Rsg2Version);

//                echo '<hr>';

                //--- Last galleries and last uploaded images -----------------------------

                // Info about last created galleries and last uploaded images
                // side by side
                DisplayInfoGalleryAndImages ($this->lastGalleries, $this->lastImages);

//                echo '<hr>';

                //--- Change log -----------------------------

                // Info about the change log of RSG3 sources
                DisplayInfoChangeLog ($this->changelogs);

                echo '<hr>';

                //--- Credits -----------------------------

                // Info about supporters of RSGallery 2
                DisplayInfoCredits ($this->credits);

                echo '<hr>';

                //--- External component licenses -----------------------------

                DisplayInfoExternalLicenses ($this->externalLicenses);

                //echo '<hr style="height:2px;border-width:0;color:gray;background-color:gray">';
                echo '<hr>';

                ?>
            </div>
        </div>
    </div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php


//--- Logo -----------------------------

function DisplayRSG2Logo()
{
	echo '    <div class="rsg2logo">';
//	             echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logo.big.png', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
	             echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logoText.svg', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
	echo '     </div>';
//	echo '<p class="test">';
//	echo '</p>

	echo '<div class="clearfix"></div>';
}

/**
 * @param        $displayData
 * @param string $class
 *
 * @return array
 *
 * @since version
 */
function HtmlButton ($displayData, $class = '')
{
	$id      = empty($displayData['id']) ? '' : (' id="' . $displayData['id'] . '"');
	$target  = empty($displayData['target']) ? '' : (' target="' . $displayData['target'] . '"');
	$onclick = empty($displayData['onclick']) ? '' : (' onclick="' . $displayData['onclick'] . '"');
	//$title   = empty($displayData['title']) ? '' : (' title="' . $this->escape($displayData['title']) . '"');
	$title   = empty($displayData['title']) ? '' : (' title="' . $displayData['title'] . '"');
	$text    = empty($displayData['text']) ? '' : ('<span class="j-links-link">' . $displayData['text'] . '</span>');

	$html[] = '<li>';
	$html[] = '<a' . $id . ' ' . $class . ' href="' . $displayData['link'] . '" ' . $target . $onclick . $title . '>';
	$html[] = '	      <div class="quickicon-icon d-flex align-items-end">';
    $html[] = '	           <span class="' . $displayData['image'] . '" aria-hidden="true"></span>';
    $html[] = '	      </div>';
	$html[] = '	      <div class="quickicon-text d-flex align-items-center">' . $text . '</div>';
	$html[] = '    </a>';
    $html[] = '</li>';

    return $html;
}

function HtmlButtons ($buttons = [])
{
	$html = [];

    foreach($buttons as  $button)
    {
        $htmlButton =  HtmlButton ($button);
        array_merge($html, $htmlButton);
    }

    return $html;
}

//--- Control buttons ------------------
//
function DisplayRSG2ControlButtonsTest($buttons)
{
    /**/
	$html = HTMLHelper::_('icons.buttons', $buttons);
	if (!empty($html))
	{
//		echo '<nav class="quick-icons" aria-label="' . Text::_('MOD_QUICKICON_NAV_LABEL') . '">';
		// echo '    <ul class="nav flex-wrap row-fluid">';
		echo '    <ul>';
		echo           $html;
		echo '    </ul>';
//		echo '</nav>';
	}
    /**/

	/** Test standard quick.icons format *
	if (!empty($html))
	{
	echo '<nav class="quick-icons" aria-label="'
	. Text::_('MOD_QUICKICON_NAV_LABEL') . '">';
	echo '    <ul class="nav flex-wrap row-fluid">';
	echo           $html;
	echo '    </ul>';
	echo '</nav>';
	}
	/**/

	echo '<div class="clearfix"></div>';
}


//--- Control buttons ------------------
//
function DisplayRSG2ControlButtons($buttons)
{

	$htmlButtons = HTMLHelper::_('icons.buttons', $buttons);



    $html[] = '<div class="flex-buttons-table" >';
    $html[] = '';
    $html[] = '';
    $html[] =     $htmlButtons;
    $html[] = '';
    $html[] = '';
    $html[] = '</div>';

	echo implode($html);
	/**
	$html = HTMLHelper::_('icons.buttons', $buttons);
	if (!empty($html))
	{
		//echo '<nav class="quick-icons" aria-label="'
		//    . Text::_('MOD_QUICKICON_NAV_LABEL') . '">';
		echo '    <ul class="nav flex-wrap row-fluid">';
		echo           $html;
		echo '    </ul>';
		//echo '</nav>';
	}
    /**/

	/** Test standard quick.icons format *
	if (!empty($html))
	{
	echo '<nav class="quick-icons" aria-label="'
	. Text::_('MOD_QUICKICON_NAV_LABEL') . '">';
	echo '    <ul class="nav flex-wrap row-fluid">';
	echo           $html;
	echo '    </ul>';
	echo '</nav>';
	}
	/**/

	echo '<div class="clearfix"></div>';
}


function DisplayInfoRsgallery2($Rsg2Version)
{
	// First column
//	echo '<div class="container-fluid">';
//	echo '<div class="clearfix"></div>';

	echo '<row>';
	//echo '<span class="rsg2logo-container col-md-6">';

	echo '   <div class="card bg-light data-toggle="collapse">';
	echo '      <div class="card-header">';
	echo '          ' . Text::_('COM_RSGALLERY2_GALLERY_INFORMATION');
	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';

	echo '<div class="rsg2-panel-info">';
	/**/
//    echo '<table class="table table-striped">';
//	echo '<table class="table table-bordered table-striped w-auto text-xsmall table-hover table-light">';
//	echo '<table class="table table-sm w-auto text-xsmall table-hover table-light">';
//	echo '<table class="table w-auto text-xsmall table-hover table-sm table-condensed table-light">';
	echo '<table class="table table-light w-auto table_morecondensed">';
//    echo '<table class="table">';
//    echo '<table>';
//    echo '<table>';
	echo '    <tbody>';
	/**/
	/**/
	echo '        <tr>';
	echo '            <td>' . Text::_('COM_RSGALLERY2_INSTALLED_VERSION') . ': ' . '</td>';
	echo '            <td>';
//	echo '                <a href="' . JRoute::_('index.php?option=com_rsgallery2&view=rsgallery2&layout=ChangeLog') . '"';
//	echo '                   title="' . Text::_('COM_RSGALLERY2_VIEW_CHANGE_LOG') . '""';
//	echo '                   class="modal">' . $Rsg2Version . '</a>';
	echo '                   <strong>' . $Rsg2Version . '</strong>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// License
	echo '        <tr>';
	echo '            <td>' . Text::_('COM_RSGALLERY2_LICENSE') . ': ' . '</td>';
	echo '            <td>';
	echo '               <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank"'
		. ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_GNU_ORG') . '" >GNU GPL</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Home page
	echo '        <tr>';
	echo '            <td>' . Text::_('COM_RSGALLERY2_HOME_PAGE') . '</td>';
	echo '            <td>';
	echo '                <a href="http://www.rsgallery2.org/" target="_blank" '
		. ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >www.rsgallery2.org</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Forum
	echo '        <tr>';
	echo '            <td>' . Text::_('COM_RSGALLERY2_FORUM') . '</td>';
	echo '            <td>';
	echo '                <a href="http://www.forum.rsgallery2.org/" target="_blank" '
		. ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >www.forum.rsgallery2.org</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Documentation
	echo '        <tr>';
	echo '            <td>' . Text::_('COM_RSGALLERY2_DOCUMENTATION') . '</td>';
	echo '            <td>';
	echo '                <a href="http://www.rsgallery2.org/index.php/documentation" target="_blank"'
		. ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_DOCUMENTATION') . '" >www.rsg.../documentation</a>';
	echo '            </td>';
	echo '        </tr>';

	/**/
	echo '    </tbody>';
	echo '</table>';
	/**/
	//echo '</span>';
	echo '</div>';

	echo '      </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '   </div>';

	echo '</row>';
//	echo '</div>'; // container fluid

	echo '<div class="clearfix"></div>';

	return;
}

function DisplayInfoGalleryAndImages ($lastGalleries, $lastImages)
{
//	echo '<div class="clearfix"></div>';

    //--- galleries -----------------------------------------------------

	echo '<hr>';

	echo '<row>';
//	echo '   <div class="card bg-light w-25 data-toggle="collapse">';
//	echo '   <div class="col-md-4">';
	echo '    <div>';
	echo '        <div class="custom-column">';
	echo '            <div class="custom-column-content">';
	echo '                <div class="card bg-light data-toggle="collapse">';
	echo '                    <div class="card-header">';
	echo '                        ' . Text::_('COM_RSGALLERY2_GALLERIES');
	echo '                    </div>';

	echo '                    <div id="credit-card-body" class="card-body">';

	echo '                        <div class="rsg2-gallery-info-table">';

	// only root gallery item existing
	if (count($lastGalleries) < 2)
	{
		echo '        <tr>';
		echo '        %';
		// echo Text::_('COM_RSGALLERY2_NO_NEW_GALLERIES');
		echo '        </tr>';
	}
	else
	{
        // Header ----------------------------------

        //echo '<table class="table table-striped table-condensed">';
        echo '<table class="table table-striped table-light w-auto table_morecondensed">';
        echo '    <caption>' . Text::_('COM_RSGALLERY2_MOST_RECENTLY_ADDED_GALLERIES') . '</caption>';
        echo '    <thead>';
        echo '        <tr>';
        echo '            <th>' . Text::_('COM_RSGALLERY2_GALLERY') . '</th>';
        echo '            <th>' . Text::_('COM_RSGALLERY2_USER') . '</th>';
        echo '            <th>' . Text::_('COM_RSGALLERY2_ID') . '</th>';
        echo '        </tr>';
        echo '    </thead>';

        //--- data ----------------------------------

        echo '    <tbody>';

            foreach ($lastGalleries as $GalleryInfo)
            {
                if ($GalleryInfo['id'] != '1')
                {
                    echo '        <tr>';
                    echo '            <td>' . $GalleryInfo['name'] . '</td>';
                    echo '            <td>' . $GalleryInfo['user'] . '</td>';
                    echo '            <td>' . $GalleryInfo['id'] . '</td>';
                    echo '        </tr>';
                }
            }

        echo '    </tbody>';

        //--- footer ----------------------------------
        echo '</table>';
    }

	echo '                        </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '                    </div>';
	echo '                </div>';

	echo '            </div>';
	echo '        </div>';
	echo '    </div>';
	echo '</row>';

    //--- images -----------------------------------------------------

	echo "<hr>";

	echo '<row>';

//	echo '   <div class="card bg-light w-50 data-toggle="collapse">';
//	echo '   <div class="col-md-8">';
	echo '    <div>';
	echo '        <div class="custom-column">';
	echo '            <div class="custom-column-content">';
	echo '                <div class="card bg-light data-toggle="collapse">';
	echo '                    <div class="card-header">';
	echo '                        ' . Text::_('COM_RSGALLERY2_IMAGES');
	echo '                    </div>';

	echo '                    <div id="credit-card-body" class="card-body">';

	echo '                        <div class="rsg2-images-info-table">';


    // no image existing
    if (count($lastImages) == 0)
    {
        echo '        <tr>';
        echo '        %';
        // echo Text::_('COM_RSGALLERY2_NO_NEW_IMAGES');
        echo '        </tr>';
    }
    else
    {

        // Header ----------------------------------

    //	echo '<table class="table table-striped table-condensed">';
        echo '<table class="table table-striped table-light w-auto table_morecondensed">';
        echo '    <caption>' . Text::_('COM_RSGALLERY2_MOST_RECENTLY_ADDED_ITEMS') . '</caption>';
        echo '    <thead>';
        echo '        <tr>';
        echo '            <th>' . Text::_('COM_RSGALLERY2_FILENAME') . '</th>';
        echo '            <th>' . Text::_('COM_RSGALLERY2_GALLERY') . '</th>';
        echo '            <th>' . Text::_('COM_RSGALLERY2_DATE') . '</th>';
        echo '            <th>' . Text::_('COM_RSGALLERY2_USER') . '</th>';
        echo '        </tr>';
        echo '    </thead>';

        //--- data ----------------------------------

        echo '    <tbody>';

        foreach ($lastImages as $ImgInfo)
        {

            echo '        <tr>';
            echo '            <td>' . $ImgInfo['name'] . '</td>';
            echo '            <td>' . $ImgInfo['gallery'] . '</td>';
            echo '            <td>' . $ImgInfo['date'] . '</td>';
            echo '            <td>' . $ImgInfo['user'] . '</td>';
            echo '        </tr>';
        }
        echo '    </tbody>';

        //--- footer ----------------------------------
        echo '</table>';
    }

	echo '                        </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '                    </div>';
	echo '                </div>';

	echo '            </div>';
	echo '        </div>';
	echo '    </div>';
	echo '</row>';

	echo '<div class="clearfix"></div>';

}

// Info about supporters of RSGallery 2
function DisplayInfoCredits ($credits)
{
//	echo '<div class="clearfix"></div>';

	echo '<hr>';

	echo '<row>';
	echo '   <div class="card bg-light w-auto data-toggle="collapse" data-target="#credit-card-body">';
	echo '      <div class="card-header">';
	echo '          ' . Text::_('COM_RSGALLERY2_CREDITS');
   	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';
//	echo '         credits: ' . implode("|", $credits);
	echo '         ' . $credits;
	echo '      </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '   </div>';
	echo '</row>';

}

function tableFromXml($changelogs)
{
    /**
    $html[] = '';
    $html[] = '<table class="table table-striped thead-dark table-bordered">';
    $html[] = '    <thead>';
    $html[] = '        <tr>';
//    $html[] = '            <th>' . 'Name' . '</th>';
//    $html[] = '            <th>Call Sign</th>';
//    $html[] = '            <th>Type</th>';
//    $html[] = '            <th>Status</th>';
//    $html[] = '            <th>Expiration Date</th>';
    $html[] = '        </tr>';
    $html[] = '    </thead>';

    $html[] = '    <tbody>';
    $html[] = '';
    /**/

	// foreach ($changelogs as $xmlElement)
    foreach ($changelogs as $htmlElements)
    {
        /**
        $html[] = '        <tr class="">';
//       $html[] = '            <td>' . $xmlElement->asXML() . '</td>';
	    $html[] = '            <td>';
	    foreach ($htmlElements as $htmlElement)
	    {
		    $html[] = '            ' . $htmlElement;
	    }
	    $html[] = '            </td>';

    //    $html[] = '            <td><?php echo $xmlElement->asXML();</td>';
    //    $html[] = '            <td><?php echo $xmlElement->asXML();</td>';
    //    $html[] = '            <td><?php echo $xmlElement->asXML();</td>';
    //    $html[] = '            <td><?php echo $xmlElement->asXML();</td>';
        $html[] = '        </tr>';
        /**/

        $html[] = '            ' . $htmlElements;
    };

    /**
    $html[] = '    </tbody>';
    $html[] = '</table>';
    /**/

    // implode($html);
    // implode(' ', $html);
    // implode('< /br>', $html);
    return implode($html);
}

// Info about the change log og RSG3 sources
function DisplayInfoChangeLog ($changelogs)
{
    /**
	$item = new stdClass();
	$item->id = 209;
	$item->name = "com_sgallery2";
	$item->version = "5.0.0.2";
    /**/

//	echo '<div class="clearfix"></div>';

	echo '<hr>';

	/**
	echo HTMLHelper::_(
		'bootstrap.renderModal',
		'changelogModal' . $item->id, // $item->extension_id,
		array(
			'title' => Text::sprintf('COM_INSTALLER_CHANGELOG_TITLE', $item->name, $item->version),
		),
		''
	);
    /**/

	//====================================================================
    //echo '$changelogs: ' . json_encode ($changelogs);

    /**
    if (!empty($xml))
    {
        $json = json_encode($xml);
        echo '$json: ' . json_encode ($json);
        echo ('<br>');
        $array = json_decode($json,TRUE);
        echo '$array: ' . json_encode ($array);
        echo ('<br>');
    }
    /**/

	$htmlChangelogs = tableFromXml($changelogs);

	echo '<row>';
	echo '   <div class="card bg-light w-auto data-toggle="collapse" data-target="#credit-card-body">';
	echo '      <div class="card-header">';
	echo '          ' . Text::_('COM_RSGALLERY2_CHANGELOG');
	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';
	//echo '         htmlChangelogs: ' . $htmlChangelogs;
	echo '         ' . $htmlChangelogs;
	echo '      </div>';
	echo '   </div>';
	echo '</row>';


	/**
	$headers=get_headers($changelogUrl);
	echo 'headers: ' . json_encode ($headers);
	echo ('<br>');

	$changelog = new Changelog;
	//$changelog->setVersion($source === 'manage' ? $extension->version : $extension->updateVersion);
	$loaded = $changelog->loadFromXml($changelogUrl);

	echo '$loaded: ' . $loaded;
	echo '<br>';
	echo 'changelog: ' . json_encode ($changelog);
	echo ('<br>');

	// Read all the entries
	$entries = array(
		'security' => array(),
		'fix'      => array(),
		'addition' => array(),
		'change'   => array(),
		'remove'   => array(),
		'language' => array(),
		'note'     => array()
	);

	array_walk(
		$entries,
		function (&$value, $name) use ($changelog) {

			if ($field = $changelog->get($name))
			{
				$value = $changelog->get($name)->data;
			}
		}
	);

	echo '$entries: ' . json_encode ($entries);
	echo '<br>';

	$layout = new FileLayout('joomla.installer.changelog');
	$output = $layout->render($entries);

	echo 'OutPut: ' . json_encode ($output);
	echo '<br>';
	echo ('JsonResponse: ' . new JsonResponse($output));
	echo '<br>';
	/**/
}

// Info about
function DisplayInfoExternalLicenses ($externalLicenses)
{
	echo '<div class="clearfix"></div>';

	echo '<row>';
	echo '   <div class="card bg-light w-auto data-toggle="collapse" data-target="#credit-card-body">';
	echo '      <div class="card-header">';
	echo '          ' . Text::_('COM_RSGALLERY2_EXTERNAL_LICENSES');
	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';
	echo '         ' . $externalLicenses;
	echo '      </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '   </div>';
	echo '</row>';
}
