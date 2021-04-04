<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

use Joomla\CMS\Language\Text;

HTMLHelper::_('bootstrap.framework');

HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/controlPanel.css', array('version' => 'auto', 'relative' => true));

?>

    <form action="<?php echo Route::_('index.php?option=com_rsgallery2'); ?>"
          method="post" name="adminForm" id="adminForm" class="form-validate">
        <div class="d-flex flex-row">
            <?php if (false) : // ToDo: Remove this quick hack. do not show sidebar
                //if (!empty($this->sidebar)) :
                ?>
                <div id="j-sidebar-container" class="">
                    <?php echo $this->sidebar; ?>
                </div>
            <?php endif; ?>
<!--            <div class="--><?php
//            // if (!empty($this->sidebar)) {
//            if (false) {
//                echo 'col-md-10';
//            } else {
//                echo 'col-md-12';
//            } ?><!--">-->
                <!--div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
                <div class="flex-fill">
                <div id="j-main-container" class="j-main-container">

                    <?php

                    //--- Logo -----------------------------

                    DisplayRSG2Logo();

                    //--- tell j3x galleries must be initialized ---------------------------

                    if ($this->isJ3xDataExisting) {

                        if ($this->isMissingJ3xDbGalleries
                            || $this->isMissingJ3xDbImages
                            || $this->isMissingJ3xImages) {

                            echo DisplayRequestJ3xActions (
                                    $this->isMissingJ3xDbGalleries,
                                    $this->isMissingJ3xDbImages,
                                    $this->isMissingJ3xImages
                            );
                        }
                    }

                    //--- Control buttons ------------------

                    DisplayRSG2ControlButtons($this->buttons);

                    echo '<hr>';

                    //--- RSG2 informations -----------------------------

                    // About RSG2
                    // ToDo: use real version
                    DisplayAboutRsgallery2($this->Rsg2Version);

                    // echo '<hr>';

                    //--- Last galleries and last uploaded images -----------------------------

                    DisplayLastGalleriesAndImages($this->lastGalleries, $this->lastImages);

                    //echo '<hr>';

                    //--- Change log -----------------------------

                    // Info about the change log of RSG3 sources
                    DisplayChangeLog($this->changelogs);

                    //echo '<hr>';

                    //--- Credits -----------------------------

                    // Info about supporters of RSGallery 2
                    DisplayCredits($this->credits);

                    //echo '<hr>';

                    //--- External component licenses -----------------------------

                    DisplayExternalLicenses($this->externalLicenses);

                    // echo '<hr>';

                    ?>
                </div>
            </div>
        </div>

        <input type="hidden" name="task" value=""/>
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>

<?php


//--- Logo -----------------------------

/**
 * Just displays the logo as svg
 *
 * @since __BUMP_VERSION__
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

////--- Request to user: Save config -----------------------------
//
///**
// * Displays
// *
// * @since __BUMP_VERSION__
// */
//function DisplayRequestSaveConfigOnce()
//{
//    $rsg2ConfigurationLink = Route::_('index.php?option=com_config&view=component&component=com_rsgallery2');
//
//
//    echo '    <div class="rsg2requestSaveConfig">';
////	             echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logo.big.png', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
////    echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logoText.svg', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
//
////    echo '        <button type="button" class="btn btn-primary"';
//    echo '        <button type="button" class="btn btn-warning"';
//    echo '               onclick="location.href=\'' . $rsg2ConfigurationLink . '\'">';
////    echo '            <span class="badge badge-pill badge-info">' . Text::_('COM_RSGALLERY2_PLEASE_GOTO_CONFIGURATION') . '</span>';
//    echo '            <strong >' . Text::_('COM_RSGALLERY2_PLEASE_GOTO_CONFIGURATION') . '</strong>';
//    echo '        </button>';
//    echo '    </div>';
//    echo '    <br>';
//
////	echo '<p class="test">';
////	echo '</p>
//
//    echo '<div class="clearfix"></div>';
//}

//--- Request to user: Save config -----------------------------

/**
 * Displays info and links to needed j3x action
 *
 * @since __BUMP_VERSION__
 */
function DisplayRequestJ3xActions($isMissingJ3xDbGalleries=false,
                                    $isMissingJ3xDbImages=false,
                                    $isMissingJ3xImages=false)
{
    $html = '';

    $rsg2J3xCopyDbGalleriesLink = Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DBTransferJ3xGalleries');
    $rsg2J3xCopyDbImagesLink = Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbTransferJ3xImages');
    $rsg2J3xCopyImagesLink = Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=MoveJ3xImages');

    $CopyDbGalleries = Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES');
    $CopyDbGalleriesDesc = Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_DESC');
    $CopyDbImages = Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES');
    $CopyDbImagesDesc = Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_DESC');
    $CopyImages = Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES');
    $CopyImagesDesc = Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_DESC');

    $header = Text::_('COM_RSGALLERY2_J3X_ACTIONS_NEEDED');
    $headerDesc = Text::_('COM_RSGALLERY2_J3X_ACTIONS_NEEDED_DESC');

    $link1 = '';
    if ($isMissingJ3xDbGalleries) {
        $link1 = <<<EOT
                                <span class="badge badge-pill badge-success">1</span> <a href="$rsg2J3xCopyDbGalleriesLink" class="btn btn-success btn-sm" Title="$CopyDbGalleriesDesc" role="button">$CopyDbGalleries</a>
EOT;
    }
    $link2 = '';
    if ($isMissingJ3xDbImages) {
        $link2 = <<<EOT
                                <span class="badge badge-pill badge-success">2</span> <a href="$rsg2J3xCopyDbImagesLink" class="btn btn-success btn-sm" Title="$CopyDbImagesDesc" role="button">$CopyDbImages</a>
EOT;
    }
    $link3 = '';
    if ($isMissingJ3xImages) {
        $link3 = <<<EOT
                                <span class="badge badge-pill badge-success">3</span> <a href="$rsg2J3xCopyImagesLink" class="btn btn-success btn-sm" Title="$CopyImagesDesc" role="button">$CopyImages</a>
EOT;
    }

    $html = <<<EOT
            <div class="rsg2requestJ3xActions">
            
                <div class="card w-30" >                
                    <h5 class="card-header">$header</h5>
                    <div class="card-body">
                        <p class="card-text">$headerDesc</p>
                        <ul>
                            <li style="list-style: none; margin-bottom: 10px">
                            $link1
                            </li>
                            <li style="list-style: none; margin-bottom: 10px">
                            $link2                               
                            </li>
                            <li style="list-style: none; margin-bottom: 0px">
                            $link3
                            </li>
                        </ul>
                    </div>
            
                </div>
            </div>
            
            <br />
EOT;

    return $html;
}



//--- Control buttons ------------------

/**
 * @param $buttons
 *
 *
 * @since __BUMP_VERSION__
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
 * @since __BUMP_VERSION__
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

    echo '    <div id="GalImg_outer">';

    echo '    <div>';
    echo '        <div class="custom-column">';
    echo '            <div class="custom-column-content">';
    echo '                <div class="card bg-light" id="galleriesCard" >';
    echo '                    <div class="card-header">';
    echo '                        ' . Text::_('COM_RSGALLERY2_GALLERIES');
    echo '                    </div>';

    echo '                    <div id="credit-card-body" class="card-body">';
    echo '                        <div class="rsg2-gallery-info-table">';

    // only root gallery item existing
    if (count($lastGalleries) < 2) {
        echo '<table class="table table-striped table-sm table_morecondensed" id="galleriesTable" >';
        echo '    <caption>' . Text::_('COM_RSGALLERY2_MOST_RECENTLY_ADDED_GALLERIES') . '</caption>';

        echo '    <tbody>';

        echo '        <tr>';
        echo '        %';
        // echo Text::_('COM_RSGALLERY2_NO_NEW_GALLERIES');
        echo '        </tr>';
        echo '    </tbody>';

        //--- footer ----------------------------------
        echo '</table>';
    } else {

        // Header ----------------------------------

//        echo '<table class="table table-striped table-light w-auto table_morecondensed" id="galleriesTable" >';
        echo '<table class="table table-striped table-sm table_morecondensed" id="galleriesTable" >';
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

    // echo '</row>';

    //--- images -----------------------------------------------------

//    echo "<hr>";

//    echo '<row>';

    echo '    <div>';
    echo '        <div class="custom-column">';
    echo '            <div class="custom-column-content">';
    echo '                <div class="card bg-light" id="imagesCard" >';
    echo '                    <div class="card-header">';
    echo '                        ' . Text::_('COM_RSGALLERY2_IMAGES');
    echo '                    </div>';

    echo '                    <div id="credit-card-body" class="card-body">';
    echo '                        <div class="rsg2-images-info-table">';


    // no image existing
    if (count($lastImages) == 0) {
        echo '<table class="table table-striped table-light w-auto table_morecondensed" id="imagesTable" >';
        echo '    <caption>' . Text::_('COM_RSGALLERY2_MOST_RECENTLY_ADDED_ITEMS') . '</caption>';
        echo '    <tbody>';

        echo '        <tr>';
        echo '        %';
        // echo Text::_('COM_RSGALLERY2_NO_NEW_IMAGES');
        echo '        </tr>';
        echo '    </tbody>';

        //--- footer ----------------------------------
        echo '</table>';
    } else {

        // Header ----------------------------------

        echo '<table class="table table-striped table-light w-auto table_morecondensed" id="imagesTable" >';
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

    echo '    </div>'; // id="GalImg_outer"


    echo '</row>';

    echo '<div class="clearfix"></div>';

}

//--- display change log ------------------

/**
 * Info about the change log og RSG2 sources
 * @param $changelogs
 *
 *
 * @since __BUMP_VERSION__
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
 * @since __BUMP_VERSION__
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
 * @since __BUMP_VERSION__
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
 * @since __BUMP_VERSION__
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
 * @since __BUMP_VERSION__
 */
function collapseContent($title, $content, $id)
{

    $collapsed = <<<EOT
        <row>
            <div class="card forCollapse">
                <h5 class="card-header">
                    <button class="btn collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-collapsed-$id" 
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
