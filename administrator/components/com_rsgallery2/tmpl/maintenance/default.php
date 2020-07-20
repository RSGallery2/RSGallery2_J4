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

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;

JHtml::_('stylesheet', 'com_rsgallery2/maintenance.css', array('version' => 'auto', 'relative' => true));

// HTMLHelper::_('script', 'mod_quickicon/quickicon.min.js', ['version' => 'auto', 'relative' => true]);
HTMLHelper::_('script', 'com_rsgallery2/maintenance.js', ['version' => 'auto', 'relative' => true]);

Text::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST', true);


//$script = 'var Token = \'' . Session::getFormToken() . '\';';
//Factory::getDocument()->addScriptDeclaration(implode("\n", $script));

// ToDo: Use ROUTE for all and change com_rsgallery2&amp; -> com_rsgallery2&


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

$RSG2_ZoneButtons = [];
    /**
// maint. templates
$RSG2_ZoneButtons[] = new zoneButtons(
    //Route::_('index.php?option=com_rsgallery2&view=maintTemplates'),
    Route::_('index.php?option=com_rsgallery2&view=Maintenance&layout=Prepared&intended=TemplateConfiguration'),
    '<del>' . Text::_('COM_RSGALLERY2_TEMPLATE_CONFIGURATION') . '</del>',
    '<del>' . Text::_('COM_RSGALLERY2_TEMPLATES_CONFIGURATION_DESC') . '</del>',
    array ('icon-equalizer', 'icon-out-3'),
    'viewConfigTemplate'
);

// maint. slideshows
$RSG2_ZoneButtons[] = new zoneButtons(
    //Route::_('index.php?option=com_rsgallery2&view=maintslideshows'),
    Route::_('index.php?option=com_rsgallery2&view=Maintenance&layout=Prepared&intended=SlideshowConfiguration'),
    '<del>' . Text::_('COM_RSGALLERY2_SLIDESHOW_CONFIGURATION') . '</del>',
    '<del>' . Text::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION_DESC') . '</del>',
    array ('icon-equalizer', 'icon-play'),
    'viewConfigSlideshow'
);
/**/

//--- Raw database zone -----------------------------

$rawDatabase_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_RAW_DB_ZONE'), Text::_('COM_RSGALLERY2_RAW_DB_ZONE_DESCRIPTION'), 'rawDb', 'rawDbZone');

$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&view=config&layout=RawView'),
    Text::_('COM_RSGALLERY2_CONFIGURATION_VARIABLES'),
    Text::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT') . '                        ',
    array('icon-equalizer', 'icon-eye'),
    'viewConfigRaw'
);

//$link = Route::_('index.php?option=com_rsgallery2&view=images');
$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&view=images&layout=images_raw'),
    Text::_('COM_RSGALLERY2_IMAGES_LIST'),
    Text::_('COM_RSGALLERY2_RAW_IMAGES_TXT'),
    array('icon-image', 'icon-list-2'),
    'viewImagesRaw'
);

$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&view=galleries&layout=galleries_raw'),
    Text::_('COM_RSGALLERY2_GALLERIES_LIST'),
    Text::_('COM_RSGALLERY2_RAW_GALLERIES_TXT'),
    array('icon-images', 'icon-list-2'),
    'viewGalleriesRaw'
);

/**
$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&view=comments&layout=comments_raw'),
    Text::_('COM_RSGALLERY2_COMMENTS_LIST'),
    Text::_('COM_RSGALLERY2_RAW_COMMENTS_TXT'),
    array('icon-comment', 'icon-list-2'),
    'viewcommentsRaw'
);
/**/

/**
$rawDatabase_ZoneButtons[] =  new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&view=acl_items&layout=acls_raw'),
    Text::_('COM_RSGALLERY2_ACLS_LIST'),
    Text::_('COM_RSGALLERY2_RAW_ACLS_TXT'),
    array('icon-eye-close', 'icon-list-2'),
    'viewAclsRaw'
);
/**/

$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&view=galleries&layout=galleries_tree'),
    Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE'),
    Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_DESC'),
    array('icon-images', 'icon-tree-2'),
    'viewGalleriesRaw'
);




//--- Repair zone -----------------------------

$repair_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_REPAIR_ZONE'), Text::_('COM_RSGALLERY2_FUNCTIONS_MAY_CHANGE_DATA'), 'repair', 'repairZone');

$repair_ZoneButtons = [];

/**/
$repair_ZoneButtons[] =  new zoneButtons(
	//Route::_('index.php?option=com_rsgallery2&view=maintConsolidateImages'),
	Route::_('index.php?option=com_rsgallery2&view=Maintenance&layout=Prepared&intended=ConsolidateImages'),
	Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGES'),
	Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGES_TXT'),
	array('icon-database', 'icon-images', 'icon-checkbox-checked', 'icon-notification-2'),
	'viewConsolidateDB'
);
/**/

/**/
$repair_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEdit'),
	Text::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'),
	Text::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'),
	array('icon-equalizer', 'icon-edit'),
	'viewEditConfigRaw'
);
/**/

/**/
$repair_ZoneButtons[] =  new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&task=MaintenanceCleanUp.ResetConfigToDefault'),
    Text::_('COM_RSGALLERY2_CONFIG_RESET_TO_DEFAULT') . '</del>',
    Text::_('COM_RSGALLERY2_CONFIG_RESET_TO_DEFAULT_DESC'),
    array('icon-equalizer', 'icon-undo'),
    'uninstallDataTables'
);
/**/

/**/
$repair_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&view=develop&layout=Rsg2GeneralInfo'),
	Text::_('COM_RSGALLERY2_COLLECT_RSG2_INFO'),
	Text::_('COM_RSGALLERY2_COLLECT_RSG2_INFO_DESC'),
	array('icon-eye-open', 'icon-briefcase'),
	'viewEditConfigRaw'
);
/**/

/**/
$repair_ZoneButtons[] =  new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&view=config&format=json'),
    Text::_('COM_RSGALLERY2_CONFIG_SAVE_TO_FILE'),
    Text::_('COM_RSGALLERY2_CONFIG_SAVE_TO_FILE_DESC'),
    array('icon-equalizer', 'icon-file', 'icon-download'),
    'viewEditConfigRaw'
);
/**/
/**/
$repair_ZoneButtons[] =  new zoneButtons(
//    Route::_('index.php?option=com_rsgallery2&task=config.config'),
    Route::_('index.php?option=com_rsgallery2&task=config.importConfigFile'),
//    Route::_('index.php?option=com_rsgallery2&view=config'),
//    'javascript:alert(\'Hello\');',
//    '#',
    Text::_('COM_RSGALLERY2_CONFIG_READ_FROM_FILE'),
    Text::_('COM_RSGALLERY2_CONFIG_READ_FROM_FILE_DESC'),
    array('icon-equalizer', 'icon-file', 'icon-upload'),
    'ConfigRawReadFromFile'
);
/**/

$repair_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&task=Galleries.reinitNestedGalleryTable'),
    Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET'),
    Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET_DESC'),
    array('icon-images', 'icon-database', 'icon-undo'),
    'viewGalleriesRaw'
);

$repair_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&task=Galleries.rebuild'),
    Text::_('COM_RSGALLERY2_REBUILD_GALLERY_ORDER'),
    Text::_('COM_RSGALLERY2_REBUILD_GALLERY_ORDER_DESC'),
    array('icon-images', 'icon-database', 'icon-tree'),
    'viewGalleriesRaw'
);

$repair_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&task=Images.reinitImagesTable'),
    Text::_('COM_RSGALLERY2_IMAGES_TABLE_RESET'),
    Text::_('COM_RSGALLERY2_IMAGES_TABLE_RESET_DESC'),
    array('icon-image', 'icon-database', 'icon-undo'),
    'viewImagesRaw'
);


//--- danger zone  -----------------------------

$danger_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_DANGER_ZONE'), Text::_('COM_RSGALLERY2_DANGER_ZONE_DESCRIPTION'), 'danger', 'dangerZone');

$danger_ZoneButtons = [];

/**/
$danger_ZoneButtons[] = new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&task=MaintenanceCleanUp.purgeImagesAndData'),
	Text::_('COM_RSGALLERY2_PURGE_DATA_AND_IMAGES'),
	Text::_('COM_RSGALLERY2_PURGE_DATA_AND_IMAGES_DESC'),
	array('icon-database', 'icon-purge', 'icon-notification-2'),
	'purgeImagesAndData'
);
/**/

/**
$danger_ZoneButtons[] = new zoneButtons(
);
/**/

/**/
$danger_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&task=MaintenanceCleanUp.prepareRemoveTables'),
    Text::_('COM_RSGALLERY2_PREPARE_REMOVE_RSGALLERY2'),
    Text::_('COM_RSGALLERY2_PREPARE_REMOVE_RSGALLERY2_DESC'),
    array('icon-database', 'icon-delete'),
    'uninstallDataTables'
);
/**/

/**/
$danger_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_rsgallery2&task=MaintenanceCleanUp.undoPrepareRemoveTables'),
    Text::_('COM_RSGALLERY2_UNDO_PREPARE_REMOVE_RSGALLERY2'),
    Text::_('COM_RSGALLERY2_UNDO_PREPARE_REMOVE_RSGALLERY2_DESC'),
//    array('icon-database', 'icon-delete', 'icon-arrow-left'),
    array('icon-database', 'icon-delete', 'icon-undo'),
    'uninstallDataTables'
);
/**/


//--- upgrade zone -----------------------------

if ($this->isJ3xRsg2DataExisting)
{
	$upgrade_ZoneInfo = Text::_('COM_RSGALLERY2_UPGRADE_ZONE_DESCRIPTION');
} else {
	$upgrade_ZoneInfo = Text::_('COM_RSGALLERY2_J3X_RSG2_TABLES_NOT_EXISTING');
}
$upgrade_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_UPGRADE_ZONE'), $upgrade_ZoneInfo, 'upgrade', 'upgradeZone');

$upgrade_ZoneButtons = [];

if ($this->isJ3xRsg2DataExisting)
{

	/**/
	$upgrade_ZoneButtons[] = new zoneButtons(
		Route::_('index.php?option=com_rsgallery2&task=MaintenanceJ3x.applyExistingJ3xData'),
		Text::_('COM_RSGALLERY2_APPLY_EXISTING_J3X_DATA'),
		Text::_('COM_RSGALLERY2_APPLY_EXISTING_J3X_DATA_DESC'),
		array('icon-new-tab', 'icon-new-tab', 'icon-new-tab'),
		'viewApplyExistingJ3xData'
	);
	/**/

	/**/
	$upgrade_ZoneButtons[] = new zoneButtons(
		Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbCopyJ3xConfig'),
		Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG'),
		Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG_DESC'),
		array('icon-new-tab', 'icon-database', 'icon-equalizer', 'icon-notification-2'),
		'viewDbCopyJ3xConfig'
	);
	/**/

	/**/
	$upgrade_ZoneButtons[] = new zoneButtons(
		Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DBTransferJ3xGalleries'),
		Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES'),
		Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_DESC'),
		array('icon-new-tab', 'icon-images', 'icon-notification-2'),
		'viewDBTransferJ3xGalleries'
	);
	/**/

	/**/
	$upgrade_ZoneButtons[] = new zoneButtons(
		Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbTransferJ3xImages'),
		Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES'),
		Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_DESC'),
		array('icon-new-tab', 'icon-database', 'icon-image', 'icon-notification-2'),
		'viewDbTransferJ3xImages'
	);
	/**/

	/**/
	$upgrade_ZoneButtons[] = new zoneButtons(
		Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=TransferJ3xImages'),
		Text::_('COM_RSGALLERY2_COPY_J3X_IMAGES'),
		Text::_('COM_RSGALLERY2_COPY_J3X_IMAGES_DESC'),
		array('icon-new-tab', 'icon-copy', 'icon-image', 'icon-notification-2'),
		'viewDbTransferJ3xImages'
	);
	/**/
}


//--- outdated zone -----------------------------

$outdated_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_OUTDATED_ZONE'), Text::_('COM_RSGALLERY2_OUTDATED_ZONE_DESC'), 'outdated', 'outdatedZone');

$outdated_ZoneButtons = [];

//--- developer zone -----------------------------

$developer_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_DEVELOPER_ZONE'), Text::_('COM_RSGALLERY2_DEVELOPER_ZONE_DESCRIPTION'), 'developer', 'developerZone');

$developer_ZoneButtons = [];

/**/
$developer_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&view=develop&layout=InstallMessage'),
	Text::_('Test Install/Update message'),
	Text::_('Check the output result of the install finish and upgrade finish result view part'),
	array('icon-eye-open', 'icon-expand'),
	'view___'
);
/**/

/**/
$developer_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&view=develop&layout=ManifestInfo'),
	Text::_('COM_RSGALLERY2_MANIFEST_INFO'),
	Text::_('COM_RSGALLERY2_MANIFEST_INFO_DESC'),
	array('icon-eye-open', 'icon-briefcase'),
	'view___'
);
/**/

/**/
$developer_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&view=develop'),
	Text::_('COM_RSGALLERY2_DEVELOP_VIEW'),
	Text::_('COM_RSGALLERY2_DEVELOP_VIEW_DESC'),
	array('icon-enter', 'icon-compass'),
	'view___'
);
/**/


//--- developer test zone -----------------------------

$developer4Test_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_DEVELOP_TEST_ZONE'), Text::_('COM_RSGALLERY2_DEVELOP_TEST_ZONE_DESCRIPTION'), 'devTest', 'devTestZone');

$developer4Test_ZoneButtons = [];

//$developer4Test_ZoneButtons[] = new zoneButtons(
//    Route::_('index.php?option=com_rsgallery2&view=galleries&layout=galleries_tree'),
//    Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE'),
//    Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_DESC'),
//    array('icon-images', 'icon-tree-2', 'icon-notification-2'),
//    'viewGalleriesRaw'
//);
//
//$developer4Test_ZoneButtons[] = new zoneButtons(
//    Route::_('index.php?option=com_rsgallery2&task=Galleries.reinitNestedGalleryTable'),
//    Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET'),
//    Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET_DESC'),
//    array('icon-images', 'icon-database', 'icon-undo', 'icon-notification-2'),
//    'viewGalleriesRaw'
//);
//

/**/
$developer4Test_ZoneButtons[] = new zoneButtons(
	Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DBTransferJ3xGalleries'),
	Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES'),
	Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_DESC'),
	array('icon-new-tab', 'icon-images', 'icon-notification-2'),
	'viewDBTransferJ3xGalleries'
);
/**/

/**/
$developer4Test_ZoneButtons[] = new zoneButtons(
		Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=TransferJ3xImages'),
    Text::_('COM_RSGALLERY2_COPY_J3X_IMAGES'),
    Text::_('COM_RSGALLERY2_COPY_J3X_IMAGES_DESC'),
    array('icon-new-tab', 'icon-copy', 'icon-image', 'icon-notification-2'),
    'viewDbTransferJ3xImages'
);
/**/


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
}
?>

    <form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=maintenance'); ?>"
          method="post" name="adminForm" id="adminForm" class="form-validate"
          enctype="multipart/form-data">
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

                        if ($this->isDevelop) {
                            DisplayZone($developer4Test_Zone, $developer4Test_ZoneButtons);
                        }

                        DisplayZone($rawDatabase_Zone, $rawDatabase_ZoneButtons);

                        // DisplayZone($outdated_Zone, $outdated_ZoneButtons);
                        /**/
                        DisplayZone($repair_Zone, $repair_ZoneButtons);
                        DisplayZone($danger_Zone, $danger_ZoneButtons);

                        if ($this->isJ3xRsg2DataExisting)
                        {
	                        DisplayZone($upgrade_Zone, $upgrade_ZoneButtons);
                        }

                        if ($this->isDevelop) {
                            DisplayZone($developer_Zone, $developer_ZoneButtons);
                        }
                        /**/

                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="hidden-input-buttons" style="display: none;">
            <div class="control-group">
                <label for="config_file" class="control-label"><?php echo Text::_('RSG2 import configuration from file'); ?></label>
                <div class="controls">
                    <input class="form-control-file" id="config_file" name="config_file" type="file" >
                </div>
            </div>
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>

<?php

