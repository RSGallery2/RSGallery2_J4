<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

use Joomla\CMS\Language\Text;

HTMLHelper::_('bootstrap.framework');

JHtml::_('stylesheet', 'com_rsgallery2/controlPanel.css', array('version' => 'auto', 'relative' => true));

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
            <div class="<?php 
                // if (!empty($this->sidebar)) {
                if (false) {
                echo 'col-md-10';
            } else {
                echo 'col-md-12';
            } ?>">
                <div id="j-main-container" class="j-main-container">

                    <?php

                    //--- Logo -----------------------------

                    DisplayRSG2Logo();

                    //--- Control buttons ------------------

                    DisplayRSG2ControlButtons($this->buttons);

                    echo '<hr>';

                    //--- RSG2 informations -----------------------------

                    // About RSG2
                    // ToDo: use real version
                    DisplayAboutRsgallery2($this->Rsg2Version);

                    echo '<hr>';

                    //--- Last galleries and last uploaded images -----------------------------

                    DisplayLastGalleriesAndImages($this->lastGalleries, $this->lastImages);

                    echo '<hr>';

                    //--- Change log -----------------------------

                    // Info about the change log of RSG3 sources
                    DisplayChangeLog($this->changelogs);

                    echo '<hr>';

                    //--- Credits -----------------------------

                    // Info about supporters of RSGallery 2
                    DisplayCredits($this->credits);

                    echo '<hr>';

                    //--- External component licenses -----------------------------

                    DisplayExternalLicenses($this->externalLicenses);

                    // echo '<hr>';

                    ?>
                </div>
            </div>
        </div>

	    <input type="hidden" name="task" value="" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>

<?php


//--- Logo -----------------------------

/**
 * Just displays the logo as svg
 *
 * @since version
 */
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

//--- Control buttons ------------------

/**
 * @param $buttons
 *
 *
 * @since version
 */
function DisplayRSG2ControlButtons($buttons)
{

    $htmlButtons = HTMLHelper::_('icons.buttons', $buttons);


    $html[] = '<div class="flex-buttons-table" >';
    $html[] = '';
    $html[] = '';
    $html[] = $htmlButtons;
    $html[] = '';
    $html[] = '';
    $html[] = '</div>';

    echo implode($html);

    echo '<div class="clearfix"></div>';
}

//--- About RSG2 ------------------

/**
 * @param $Rsg2Version
 *
 *
 * @since version
 */
function DisplayAboutRsgallery2($Rsg2Version)
{
    //$title = Text::_('COM_RSGALLERY2_ABOUT') . ' <strong>' . $Rsg2Version . ' </strong>';
    $title = Text::_('COM_RSGALLERY2_ABOUT') . ' ' . $Rsg2Version;
    $content = rsg2InfoHtml($Rsg2Version);
    $id = 'rsg2_info';

    collapseContent($title, $content, $id);
}

function rsg2InfoHtml($Rsg2Version)
{

    $html[] = '';

    $html[] = '<table class="table table-light w-auto table_morecondensed">';
    $html[] = '    <tbody>';


    $html[] = '        <tr>';
    $html[] = '            <td>' . Text::_('COM_RSGALLERY2_INSTALLED_VERSION') . ': ' . '</td>';
    $html[] = '            <td>';
    $html[] = '                   <strong>' . $Rsg2Version . '</strong>';
    $html[] = '            </td>';
    $html[] = '        </tr>';

    // License
    $html[] = '        <tr>';
    $html[] = '            <td>' . Text::_('COM_RSGALLERY2_LICENSE') . ': ' . '</td>';
    $html[] = '            <td>';
    $html[] = '               <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank"'
        . ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_GNU_ORG') . '" >GNU GPL</a>';
    $html[] = '            </td>';
    $html[] = '        </tr>';

    // Home page
    $html[] = '        <tr>';
    $html[] = '            <td>' . Text::_('COM_RSGALLERY2_HOME_PAGE') . '</td>';
    $html[] = '            <td>';
    $html[] = '                <a href="http://www.rsgallery2.org/" target="_blank" '
        . ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >www.rsgallery2.org</a>';
    $html[] = '            </td>';
    $html[] = '        </tr>';

    // Forum
    $html[] = '        <tr>';
    $html[] = '            <td>' . Text::_('COM_RSGALLERY2_FORUM') . '</td>';
    $html[] = '            <td>';
    $html[] = '                <a href="http://www.forum.rsgallery2.org/" target="_blank" '
        . ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >www.forum.rsgallery2.org</a>';
    $html[] = '            </td>';
    $html[] = '        </tr>';

    // Documentation
    $html[] = '        <tr>';
    $html[] = '            <td>' . Text::_('COM_RSGALLERY2_DOCUMENTATION') . '</td>';
    $html[] = '            <td>';
    $html[] = '                <a href="http://www.rsgallery2.org/index.php/documentation" target="_blank"'
        . ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_DOCUMENTATION') . '" >www.rsg.../documentation</a>';
    $html[] = '            </td>';
    $html[] = '        </tr>';


    $html[] = '    </tbody>';
    $html[] = '</table>';

    return implode($html);
}


//--- Latest galleries images ------------------

function DisplayLastGalleriesAndImages($lastGalleries, $lastImages)
{

    //--- galleries -----------------------------------------------------

    echo '<row>';

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
    if (count($lastGalleries) < 2) {
        echo '        <tr>';
        echo '        %';
        // echo Text::_('COM_RSGALLERY2_NO_NEW_GALLERIES');
        echo '        </tr>';
    } else {
        // Header ----------------------------------

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

        foreach ($lastGalleries as $GalleryInfo) {
            if ($GalleryInfo['id'] != '1') {
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
    echo '                    </div>';
    echo '                </div>';

    echo '            </div>';
    echo '        </div>';
    echo '    </div>';
    echo '</row>';

    //--- images -----------------------------------------------------

    echo "<hr>";

    echo '<row>';

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
    if (count($lastImages) == 0) {
        echo '        <tr>';
        echo '        %';
        // echo Text::_('COM_RSGALLERY2_NO_NEW_IMAGES');
        echo '        </tr>';
    } else {

        // Header ----------------------------------

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

        foreach ($lastImages as $ImgInfo) {

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
    echo '                    </div>';
    echo '                </div>';

    echo '            </div>';
    echo '        </div>';
    echo '    </div>';


    echo '</row>';

    echo '<div class="clearfix"></div>';

}

//--- display change log ------------------

/**
 * Info about the change log og RSG2 sources
 * @param $changelogs
 *
 *
 * @since version
 */
function DisplayChangeLog($changelogs)
{
    $title = Text::_('COM_RSGALLERY2_CHANGELOG');
    $content = tableFromXml($changelogs);
    $id = 'rsg2_changelog';

    collapseContent($title, $content, $id);
}

/**
 * @param $changelogs
 *
 * @return string
 *
 * @since version
 */
function tableFromXml($changelogs)
{
    foreach ($changelogs as $htmlElements) {
        $html[] = '            ' . $htmlElements;
    }

    // return implode($html);
    return implode('</br>', $html);
}

//--- Credits ------------------

/**
 * Info about supporters of RSGallery 2
 * @param $credits
 *
 *
 * @since version
 */
function DisplayCredits($credits)
{
    $title = Text::_('COM_RSGALLERY2_CREDITS');
//    $content = CreditsHtml ($credits);
    $id = 'rsg2_credits';

    collapseContent($title, $credits, $id);
}

//--- display external licenses ------------------

/**
 * display external licenses
 * @param $externalLicenses
 *
 *
 * @since version
 */
function DisplayExternalLicenses($externalLicenses)
{
    $title = Text::_('COM_RSGALLERY2_EXTERNAL_LICENSES');
    $content = $externalLicenses;
    $id = 'rsg2_externalLicenses';

    collapseContent($title, $content, $id);
}

//--- general collapse function ------------------------

/**
 * @param $title
 * @param $content
 * @param $id
 *
 *
 * @since version
 */
function collapseContent($title, $content, $id)
{

    $collapsed = <<<EOT
        <row>
            <div class="card">
                <h5 class="card-header">
                    <button class="btn collapsed " type="button" data-toggle="collapse" data-target="#collapse-collapsed-$id" 
                        aria-expanded="false" aria-controls="collapse-collapsed-$id" id="heading-collapsed-$id">
                        <i class="fa fa-chevron-down pull-right"></i>
                        $title
                    </button>
                </h5>
                <div id="collapse-collapsed-$id" class="collapse" aria-labelledby="heading-collapsed-$id">
                    <div class="card-body">
                        $content
                    </div>
                </div>
            </div>
        </row>
EOT;

    echo $collapsed;

    return;
}

