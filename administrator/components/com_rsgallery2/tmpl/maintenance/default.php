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


JHtml::_('stylesheet', 'com_rsgallery2/maintenance.css', array('version' => 'auto', 'relative' => true));

HTMLHelper::_('script', 'mod_quickicon/quickicon.min.js', ['version' => 'auto', 'relative' => true]);


class zoneContainer {
    public $textTitle;
    public $textInfo;
    public $classContainer;
    public $classTitle;

    public function __construct($textTitle='?', $textInfo='?', $classContainer='?', $classTitle='?')
    {
        $this->textTitle      = $textTitle;
        $this->textInfo       = $textInfo;
        $this->classContainer = $classContainer;
        $this->classTitle     = $classTitle;
    }

}
class zoneButtons
{
    public $link;
    public $textTitle;
    public $textInfo;
    public $classIcons;
    public $classButton;

    public function __construct($link='?', $textTitle='?', $textInfo='?',
                                $classIcons=array('?', '?'), $classButton='?')
    {
        $this->link        = $link;
        $this->textTitle   = $textTitle;
        $this->textInfo    = $textInfo;
        $this->classIcons  = $classIcons;
        $this->classButton = $classButton;
    }

}



//--- rsg2 zone -----------------------------

$RSG2_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_RSGALLERY2_ZONE'), Text::_('COM_RSGALLERY2_RSGALLERY2_ZONE_DESC'), 'rsg2', 'rsg2Zone');

// maint. slideshows
$RSG2_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&amp;view=maintslideshows'),
    Text::_('COM_RSGALLERY2_SLIDESHOW_CONFIGURATION'),
    Text::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION_DESC'),
    array ('icon-equalizer', 'icon-play'),
    'viewConfigSlideshow'
);

//--- Raw database zone -----------------------------

$rawDatabase_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_RAW_DB_ZONE'), Text::_('COM_RSGALLERY2_RAW_DB_ZONE_DESCRIPTION'), 'rawDb', 'rawDbZone');

$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&amp;view=config&amp;layout=RawView'),
    Text::_('COM_RSGALLERY2_CONFIGURATION_VARIABLES'),
    Text::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT') . '                        ',
    array('icon-equalizer', 'icon-eye'),
    'viewConfigRaw'
);

//$link = Route::_('index.php?option=com_rsgallery2&amp;view=images');
$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&amp;view=images&amp;layout=images_raw'),
    Text::_('COM_RSGALLERY2_IMAGES_LIST'),
    Text::_('COM_RSGALLERY2_RAW_IMAGES_TXT'),
    array('icon-image', 'icon-list-2'),
    'consolidateDB'
);

$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&amp;view=galleries&amp;layout=galleries_raw'),
    Text::_('COM_RSGALLERY2_GALLERIES_LIST'),
    Text::_('COM_RSGALLERY2_RAW_GALLERIES_TXT'),
    array('icon-images', 'icon-list-2'),
    'consolidateDB'
);

$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&amp;view=comments&amp;layout=comments_raw'),
    Text::_('COM_RSGALLERY2_COMMENTS_LIST'),
    Text::_('COM_RSGALLERY2_RAW_COMMENTS_TXT'),
    array('icon-comment', 'icon-list-2'),
    'consolidateDB'
);
/**/
$rawDatabase_ZoneButtons[] =  new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&amp;view=acl_items&amp;layout=acls_raw'),
    Text::_('COM_RSGALLERY2_ACLS_LIST'),
    Text::_('COM_RSGALLERY2_RAW_ACLS_TXT'),
    array('icon-eye-close', 'icon-list-2'),
    'consolidateDB'
);
/**/

//--- Repair zone -----------------------------

$repair_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_REPAIR_ZONE'), Text::_('COM_RSGALLERY2_FUNCTIONS_MAY_CHANGE_DATA'), 'repair', 'repairZone');

/**/
$repair_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&amp;view=maintConsolidateDB'),
	Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'),
	Text::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'),
	array('icon-database', 'icon-checkbox-checked'),
	'consolidateDB'
);
/**/

/**/
$repair_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&amp;view=config&amp;layout=RawEdit'),
	Text::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'),
	Text::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'),
	array('icon-equalizer', 'icon-edit'),
	'editConfigRaw'
);
/**/


//--- danger zone  -----------------------------

$danger_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_DANGER_ZONE'), Text::_('COM_RSGALLERY2_DANGER_ZONE_DESCRIPTION'), 'danger', 'dangerZone');

$danger_ZoneButtons = [];

//--- upgrade zone -----------------------------

$upgrade_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_UPGRADE_ZONE'), Text::_('COM_RSGALLERY2_UPGRADE_ZONE_DESCRIPTION'), 'upgrade', 'upgradeZone');

$upgrade_ZoneButtons = [];

//--- outdated zone -----------------------------

$outdated_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_OUTDATED_ZONE'), Text::_('COM_RSGALLERY2_OUTDATED_ZONE_DESC'), 'outdated', 'outdatedZone');

$outdated_ZoneButtons = [];

//--- developer zone -----------------------------

$developer_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_DEVELOPER_ZONE'), Text::_('COM_RSGALLERY2_DEVELOPER_ZONE_DESCRIPTION'), 'developer', 'developerZone');

$developer_ZoneButtons = [];

//--- ready for test zone -----------------------------

$ready4Test_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_TEST_ZONE'), Text::_('COM_RSGALLERY2_TEST_ZONE_DESCRIPTION'), 'test', 'testZone');

$ready4Test_ZoneButtons = [];

//---  -----------------------------

function DisplayButton($button)
{

    echo '<div class="rsg2-icon-button-container">';

    /** 01 */
    echo '<a href="' . $button->link . '" class="' . $button->classButton . '">';
    echo '    <figure class="rsg2-icon">';
            foreach ($button->classIcons as $Idx => $imageClass )
            {
    echo '            <span class="' . $imageClass . ' icoMoon icoMoon0' . $Idx . '" style="font-size:30px;"></span>'; // style="font-size:30px;"
            }
    echo '        <figcaption class="rsg2-text">';
    echo '            <div class="maint-title">' . $button->textTitle  . '</div>';
    echo '            <div class="maint-text">' . $button->textInfo  . '</div>';
    echo '        </figcaption>';
    echo '    </figure>';
    echo '</a>';
    /**/

    /** 02 *
    echo '    <div class="flex-buttons-table">';
    echo '        <li class="quickicon quickicon-single col mb-3">';
    echo '            <a href="' . $button->link . '">';
    echo '                <div class="quickicon-icon d-flex align-items-end big">';
            foreach ($button->classIcons as $Idx => $imageClass )
            {
    echo '            <span class="' . $imageClass . ' iconMoon0' . $Idx . '" ></span>'; // style="font-size:30px;"
            }
    echo '                </div>';
    echo '                <div class="quickicon-text d-flex align-items-center">';
    echo '                    <span class="j-links-link">' . $button->textTitle  . '</span>';
    echo '            <span class="maint-text">' . $button->textInfo  . '</span>';
    echo '                </div>';
    echo '            </a>';
    echo '        </li>';
    echo '    </div>';
    /**/

    echo '</div>'; // rsg2-icon-button-container
}



//---  -----------------------------

function DisplayZone($Zone, $Buttons) {
    echo '<div class="icons-panel ' . $Zone->classContainer . '">';

    echo zoneTitle ($Zone->textTitle, $Zone->classTitle);
    echo zoneInfo ($Zone->textInfo);

    echo '<div class="rsg2-icon-bar">';

    foreach ($Buttons as $Button) {

        DisplayButton($Button);
    }

	echo '</div>';
	echo '</div>';
}


?>


<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=maintenance'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">
    <div class="row">
        <?php if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="col-md-2">
                <?php echo $this->sidebar; ?>
            </div>
        <?php endif; ?>
        <div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
            <div id="j-main-container" class="j-main-container">

                <div class="flex-main-row">

                    <?php

                    //---  -----------------------------

                    DisplayZone($RSG2_Zone, $RSG2_ZoneButtons);
                    DisplayZone($rawDatabase_Zone, $rawDatabase_ZoneButtons);

                    // DisplayZone($outdated_Zone, $outdated_ZoneButtons);
                    /**/
                    DisplayZone($repair_Zone, $repair_ZoneButtons);
                    DisplayZone($danger_Zone, $danger_ZoneButtons);
                    DisplayZone($upgrade_Zone, $upgrade_ZoneButtons);

                    DisplayZone($ready4Test_Zone, $ready4Test_ZoneButtons);
                    DisplayZone($developer_Zone, $developer_ZoneButtons);
                    /**/

                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php





//---  -----------------------------

function zoneTitle ($title='Unknown title', $zoneClass='')
{
    $html[] = '<div class="icons-panel-title ' . $zoneClass . '">';
    //$html[] = '<h4>' . Text::_($title) . '</h4>';
    $html[] = '<header>' . Text::_($title) . '</header>';
    $html[] = '</div>';

    // implode($html);
    // implode(' ', $html);
    // implode('< /br>', $html);
    return implode($html);
    return $html;
}

//---  -----------------------------
function zoneInfo ($info='Unknown zone info')
{
    $html[] = '<div class="icons-panel-info ">';
    $html[] = '<strong>' . Text::_($info) . '</strong>';
    $html[] = '</div>';

    // implode($html);
    // implode(' ', $html);
    // implode('< /br>', $html);
    return implode($html);
    return $html;
}
