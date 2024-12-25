<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Filesystem\Path;

//use Joomla\CMS\Session\Session;

$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.maintenance');

Text::script('COM_RSGALLERY2_CONFIRM_PURGE_TABLES_DEL_IMAGES', true);
Text::script('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE', true);
Text::script('COM_RSGALLERY2_CONFIRM_RESET_CONFIG_DEFAULT', true);
Text::script('COM_RSGALLERY2_CONFIRM_IMPORT_CONFIG_FILE', true);
Text::script('COM_RSGALLERY2_CONFIRM_REINIT_GALLERIES', true);
Text::script('COM_RSGALLERY2_CONFIRM_REINIT_IMAGES', true);
Text::script('', true);
Text::script('', true);

//$script = 'var Token = \'' . Session::getFormToken() . '\';';
//Factory::getApplication()->getDocument()->addScriptDeclaration(implode("\n", $script));

class zoneContainer
{
    public $textTitle;
    public $textInfo;
    public $classContainer;
    public $classTitle;

    public function __construct($textTitle = '?', $textInfo = '?', $classContainer = '?', $classTitle = '?')
    {
        $this->textTitle      = $textTitle;
        $this->textInfo       = $textInfo;
        $this->classContainer = $classContainer;
        $this->classTitle     = $classTitle;
    }

}

class zoneButtons
{
    public $isTask; // not a link
    public $link;
    public $task;
    public $textTitle;
    public $textInfo;
    public $classIcons;
    public $classButton;

    public function __construct(
		$task = '?',
        $link = '?',
        $textTitle = '?',
        $textInfo = '?',
        $classIcons = ['?', '?'],
        $classButton = '?',
    ) {
        $this->isTask      = strlen ($task) > 0;
        $this->task        = $task;
        $this->link        = $link;
        $this->textTitle   = $textTitle;
        $this->textInfo    = $textInfo;
        $this->classIcons  = $classIcons;
        $this->classButton = $classButton;
    }

}

////--- rsg2 zone -----------------------------
//
//$RSG2_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_RSGALLERY2_ZONE'), Text::_('COM_RSGALLERY2_RSGALLERY2_ZONE_DESC'), 'rsg2', 'rsg2Zone');
//
//$RSG2_ZoneButtons = [];
//    /**
//// maint. templates
//$RSG2_ZoneButtons[] = new zoneButtons(
//     '',
//    //Route::_('index.php?option=com_rsgallery2&view=maintTemplates'),
//    Route::_('index.php?option=com_rsgallery2&view=Maintenance&layout=Prepared&intended=TemplateConfiguration'),
//    '<del>' . Text::_('COM_RSGALLERY2_TEMPLATE_CONFIGURATION') . '</del>',
//    '<del>' . Text::_('COM_RSGALLERY2_TEMPLATES_CONFIGURATION_DESC') . '</del>',
//    array ('icon-equalizer', 'icon-out-3'),
//    'viewConfigTemplate'
//);
//
//// maint. slideshows
//$RSG2_ZoneButtons[] = new zoneButtons(
//     '',
//    //Route::_('index.php?option=com_rsgallery2&view=maintslideshows'),
//    Route::_('index.php?option=com_rsgallery2&view=Maintenance&layout=Prepared&intended=SlideshowConfiguration'),
//    '<del>' . Text::_('COM_RSGALLERY2_SLIDESHOW_CONFIGURATION') . '</del>',
//    '<del>' . Text::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION_DESC') . '</del>',
//    array ('icon-equalizer', 'icon-play'),
//    'viewConfigSlideshow'
//);
///**/
//
//--- Raw database zone -----------------------------

$rawDatabase_Zone = new zoneContainer(
    Text::_('COM_RSGALLERY2_RAW_DB_ZONE'),
    Text::_('COM_RSGALLERY2_RAW_DB_ZONE_DESCRIPTION'),
    'rawDb',
    'rawDbZone',
);

$rawDatabase_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=config&layout=RawView'),
    Text::_('COM_RSGALLERY2_CONFIGURATION_VARIABLES'),
    Text::_('COM_RSGALLERY2_CONFIG_MINUS_VIEW_TXT') . '                        ',
    ['icon-equalizer', 'icon-eye'],
    'viewConfigRaw',
);

$rawDatabase_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=images&layout=images_raw'),
    Text::_('COM_RSGALLERY2_IMAGES_LIST'),
    Text::_('COM_RSGALLERY2_RAW_IMAGES_TXT'),
    ['icon-image', 'icon-list-2'],
    'viewImagesRaw',
);

$rawDatabase_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=galleries&layout=galleries_raw'),
    Text::_('COM_RSGALLERY2_GALLERIES_LIST'),
    Text::_('COM_RSGALLERY2_RAW_GALLERIES_TXT'),
    ['icon-images', 'icon-list-2'],
    'viewGalleriesRaw',
);

//$rawDatabase_ZoneButtons[] = new zoneButtons(
// '',
//Route::_('index.php?option=com_rsgallery2&view=comments&layout=comments_raw'),
//Text::_('COM_RSGALLERY2_COMMENTS_LIST'),
//Text::_('COM_RSGALLERY2_RAW_COMMENTS_TXT'),
//array('icon-comment', 'icon-list-2'),
//'viewcommentsRaw'
//);

//$rawDatabase_ZoneButtons[] =  new zoneButtons(
//    '',
//Route::_('index.php?option=com_rsgallery2&view=acl_items&layout=acls_raw'),
//Text::_('COM_RSGALLERY2_ACLS_LIST'),
//Text::_('COM_RSGALLERY2_RAW_ACLS_TXT'),
//array('icon-eye-close', 'icon-list-2'),
//'viewAclsRaw'
//);

$rawDatabase_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=galleries&layout=galleries_tree'),
    Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE'),
    Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_DESC'),
    ['icon-images', 'icon-tree-2'],
    'viewGalleriesRaw',
);

$rawDatabase_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=maintenance&layout=checkimageexif'),
    Text::_('COM_RSGALLERY2_CHECK_IMAGE_EXIF'),
    Text::_('COM_RSGALLERY2_CHECK_IMAGE_EXIF_DESC'),
    // array('icon-images', 'icon-crop', 'fas fa-archive'),
    ['icon-images', 'fas fa-camera-retro'],
    'viewGalleriesRaw',
);

//--- Repair zone -----------------------------

$repair_Zone = new zoneContainer(
    Text::_('COM_RSGALLERY2_REPAIR_ZONE'),
    Text::_('COM_RSGALLERY2_FUNCTIONS_MAY_CHANGE_DATA'),
    'repair',
    'repairZone',
);

$repair_ZoneButtons = [];

//    '<del>' . Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGES') . '<del>',
//    '<del>' . Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGES_TXT') . '<del>',

/**/
$repair_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=maintConsolidateDb'),
    Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGES'),
    Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGES_TXT'),
    ['icon-database', 'icon-images', 'icon-checkbox-checked', 'icon-notification-2'],
    'viewConsolidateDB',
);
/**/

/**/
$repair_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEdit'),
    Text::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'),
    Text::_('COM_RSGALLERY2_CONFIG_MINUS_RAW_EDIT_TXT'),
    ['icon-equalizer', 'icon-edit'],
    'viewEditConfigRaw',
);
/**/

/**/
$repair_ZoneButtons[] = new zoneButtons(
    'MaintenanceCleanUp.ResetConfigToDefault',
    '',
    Text::_('COM_RSGALLERY2_CONFIG_RESET_TO_DEFAULT') . '</del>',
    Text::_('COM_RSGALLERY2_CONFIG_RESET_TO_DEFAULT_DESC'),
    ['icon-equalizer', 'icon-undo'],
    'uninstallDataTables',
);
/**/

/**/
$repair_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop&layout=Rsg2GeneralInfo'),
    Text::_('COM_RSGALLERY2_COLLECT_RSG2_INFO'),
    Text::_('COM_RSGALLERY2_COLLECT_RSG2_INFO_DESC'),
    ['icon-eye-open', 'icon-briefcase'],
    'viewEditConfigRaw',
);
/**/

/**/
$repair_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=config&format=json'),
    '?' . Text::_('COM_RSGALLERY2_CONFIG_SAVE_TO_FILE') . '?',
    Text::_('COM_RSGALLERY2_CONFIG_SAVE_TO_FILE_DESC'),
    ['icon-equalizer', 'icon-file', 'icon-download'],
    'viewEditConfigRaw',
);
/**/
/**/
$repair_ZoneButtons[] = new zoneButtons(
//    'config.config',
    'config.importConfigFile',
    '',
//    Route::_('index.php?option=com_rsgallery2&view=config'),
//    'javascript:alert(\'Hello\');',
//    '#',
    '?' .Text::_('COM_RSGALLERY2_CONFIG_READ_FROM_FILE') . '?',
    Text::_('COM_RSGALLERY2_CONFIG_READ_FROM_FILE_DESC'),
    ['icon-equalizer', 'icon-file', 'icon-upload'],
    'ConfigRawReadFromFile',
);
/**/

$repair_ZoneButtons[] = new zoneButtons(
    'Galleries.reinitNestedGalleryTable',
    '',
    Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET'),
    Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET_DESC'),
    ['icon-images', 'icon-database', 'icon-undo'],
    'viewGalleriesRaw',
);

$repair_ZoneButtons[] = new zoneButtons(
    'Galleries.rebuild',
    '',
    Text::_('COM_RSGALLERY2_REBUILD_GALLERY_ORDER'),
    Text::_('COM_RSGALLERY2_REBUILD_GALLERY_ORDER_DESC'),
    ['icon-images', 'icon-database', 'icon-tree'],
    'viewGalleriesRaw',
);

$repair_ZoneButtons[] = new zoneButtons(
    'Images.reinitImagesTable',
    '',
    Text::_('COM_RSGALLERY2_IMAGES_TABLE_RESET'),
    Text::_('COM_RSGALLERY2_IMAGES_TABLE_RESET_DESC'),
    ['icon-image', 'icon-database', 'icon-undo'],
    'viewImagesRaw',
);

/**/
$repair_ZoneButtons[] = new zoneButtons(
    'Maintenance.CheckImagePaths',
    '',
    Text::_('COM_RSGALLERY2_CHECK_IMAGE_PATHS'),
    Text::_('COM_RSGALLERY2_CHECK_IMAGE_PATHS_DESC'),
    ['icon-search', 'icon-folder', 'icon-image'],
    'viewImagePaths',
);
/**/

/**/
$repair_ZoneButtons[] = new zoneButtons(
    'Maintenance.RepairImagePaths',
    '',
    Text::_('COM_RSGALLERY2_REPAIR_IMAGE_PATHS'),
    Text::_('COM_RSGALLERY2_REPAIR_IMAGE_PATHS_DESC'),
    ['icon-undo', 'icon-folder', 'icon-image'],
    'viewImagePaths',
);
/**/

/**/

// ToDo: As  view ? timeout (old: regenerateThumbs)
$repair_ZoneButtons[] = new zoneButtons(
    'Maintenance.regenerateImages',
    '', // -> view
    '?' . Text::_('COM_RSGALLERY2_REGENERATE_IMAGES') . '?',
    Text::_('COM_RSGALLERY2_REGENERATE_IMAGES_DESC'),
    ['icon-redo', 'icon-image'],
    'regenerateImages',
);
/**/

// ToDo: As  view ? timeout (old: regenerateThumbs)
$repair_ZoneButtons[] = new zoneButtons(
    'Maintenance.optimizeDB',
    '', // -> view
    '?' . Text::_('COM_RSGALLERY2_OPTIMIZE_DB') . '?',
    Text::_('COM_RSGALLERY2_OPTIMIZE_DB_DESC'),
    ['icon-redo', 'icon-database'],
    'optimizeDB',
);
/**/

/**
// ToDo: As  view ? timeout (old: regenerateThumbs)
$repair_ZoneButtons[] = new zoneButtons(
    'Maintenance.yyy',
    '', // -> view
    Text::_('COM_RSGALLERY2_OPTIMIZE_DB'),
    Text::_('COM_RSGALLERY2_OPTIMIZE_DB_DESC'),
    ['icon-redo', 'icon-db'],
    'optimizeDB',
);
/**/

//--- danger zone  -----------------------------

$danger_Zone = new zoneContainer(
    Text::_('COM_RSGALLERY2_DANGER_ZONE'),
    Text::_('COM_RSGALLERY2_DANGER_ZONE_DESCRIPTION'),
    'danger',
    'dangerZone',
);

$danger_ZoneButtons = [];

/**/
$danger_ZoneButtons[] = new zoneButtons(
    'MaintenanceCleanUp.purgeImagesAndData',
    '',
    Text::_('COM_RSGALLERY2_PURGE_DATA_AND_IMAGES'),
    Text::_('COM_RSGALLERY2_PURGE_DATA_AND_IMAGES_DESC'),
    ['icon-database', 'icon-purge', 'icon-notification-2'],
    'purgeImagesAndData',
);
/**/

/**
 * $danger_ZoneButtons[] = new zoneButtons(
 * );
 * /**/

/**/
$danger_ZoneButtons[] = new zoneButtons(
    'MaintenanceCleanUp.prepareRemoveTables',
    '',
    Text::_('COM_RSGALLERY2_PREPARE_REMOVE_RSGALLERY2'),
    Text::_('COM_RSGALLERY2_PREPARE_REMOVE_RSGALLERY2_DESC'),
    ['icon-database', 'icon-delete'],
    'uninstallDataTables',
);
/**/

/**/
$danger_ZoneButtons[] = new zoneButtons(
    'MaintenanceCleanUp.undoPrepareRemoveTables',
    '',
    Text::_('COM_RSGALLERY2_UNDO_PREPARE_REMOVE_RSGALLERY2'),
    Text::_('COM_RSGALLERY2_UNDO_PREPARE_REMOVE_RSGALLERY2_DESC'),
//    array('icon-database', 'icon-delete', 'icon-arrow-left'),
    ['icon-database', 'icon-delete', 'icon-undo'],
    'uninstallDataTables',
);
/**/

//--- J3x upgrade zone -----------------------------

if ($this->isJ3xRsg2DataExisting) {
    //--- load additional language file --------------------------------

    $lang = Factory::getApplication()->getLanguage();
    $lang->load(
        'com_rsg2_j3x',
        Path::clean(JPATH_ADMINISTRATOR . '/components/' . 'com_rsgallery2'),
        null,
        false,
        true,
    );

    $upgrade_ZoneInfo = Text::_('COM_RSGALLERY2_UPGRADE_ZONE_DESCRIPTION');
} else {
    $upgrade_ZoneInfo = Text::_('COM_RSGALLERY2_J3X_RSG2_TABLES_NOT_EXISTING');
}
$upgrade_Zone = new zoneContainer(Text::_('COM_RSGALLERY2_UPGRADE_ZONE'), $upgrade_ZoneInfo, 'upgrade', 'upgradeZone');

$upgrade_ZoneButtons = [];

if ($this->isJ3xRsg2DataExisting) {
//
//    $upgrade_ZoneButtons[] = new zoneButtons(
//    'MaintenanceJ3x.applyExistingJ3xData',
//     '',
//    Text::_('COM_RSGALLERY2_APPLY_EXISTING_J3X_DATA'),
//    Text::_('COM_RSGALLERY2_APPLY_EXISTING_J3X_DATA_DESC'),
//    array('icon-new-tab', 'icon-new-tab', 'icon-new-tab'),
//    'viewApplyExistingJ3xData'
//    );
//    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbcopyj3xconfig'),
        '(1) ' . Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG'),
        Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG_DESC'),
        ['icon-new-tab', 'icon-database', 'icon-equalizer'],
        'viewDbCopyJ3xConfig',
    );
    /**/

    // Comand to  yes/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3xgalleriesuser'),
        '(2) ' . Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_ALL'),
        Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_ALL_DESC'),
        ['icon-new-tab', 'icon-database', 'icon-images'],
        'viewdbtransferj3xgalleries',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3xgalleries'),
        '(2b) ' . Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_SINGLE'),
        Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_SINGLE_DESC'),
        ['icon-new-tab', 'icon-database', 'icon-images'],
        'viewdbtransferj3xgalleries',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximagesuser'),
        '(3) ' . Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_ALL'),
        Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_ALL_DESC'),
        ['icon-new-tab', 'icon-database', 'icon-image'],
        'viewdbtransferj3ximages',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximages'),
        '(3b) ' . Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_SINGLE'),
        Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_SINGLE_DESC'),
        ['icon-new-tab', 'icon-database', 'icon-image'],
        'viewdbtransferj3ximages',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=changeJ3xMenuLinks'),
        '(4) ' . Text::_('COM_RSGALLERY2_INCREASE_MENU_GID'),
        Text::_('COM_RSGALLERY2_INCREASE_MENU_GID_DESC'),
        // array('icon-new-tab', 'icon-tree-2', 'icon-folders', 'icon-code'),
        ['icon-new-tab', 'icon-menu', 'icon-tree-2'],
        'viewchangej3xmenulinks',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=lowerJ4xMenuLinks'),
        '(4b) ' . Text::_('COM_RSGALLERY2_LOWER_MENU_LINKS'),
        Text::_('COM_RSGALLERY2_LOWER_MENU_LINKS_DESC'),
        // array('icon-new-tab', 'icon-tree-2', 'icon-folders', 'icon-code'),
        ['icon-new-tab', 'icon-menu', 'icon-link'],
        'viewLowerJ4xMenuLinks',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=movej3ximages'),
        '(5) ' . Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES'),
        Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_DESC'),
        ['icon-new-tab', 'icon-copy', 'icon-image', 'icon-notification-2'],
        'viewdbtransferj3ximages',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        'MaintenanceJ3x.CheckImagePathsJ3x',
        '',
        Text::_('COM_RSGALLERY2_CHECK_IMAGE_PATHS_J3X'),
        Text::_('COM_RSGALLERY2_CHECK_IMAGE_PATHS_J3X_DESC'),
        ['icon-search', 'icon-folder', 'icon-image'],
        'viewImagePaths',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        'MaintenanceJ3x.RepairImagePathsJ3x',
        '',
        Text::_('COM_RSGALLERY2_REPAIR_IMAGE_PATHS_J3X'),
        Text::_('COM_RSGALLERY2_REPAIR_IMAGE_PATHS_J3X_DESC'),
        ['icon-undo', 'icon-folder', 'icon-image'],
        'viewImagePaths',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=configJ3x&layout=RawView'),
        // Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=ConfigRawView'),
        Text::_('COM_RSGALLERY2_CONFIG_J3X_VARIABLES'),
        Text::_('COM_RSGALLERY2_CONFIG_J3X_VARIABLES_DESC') . '                        ',
        ['icon-equalizer', 'icon-eye'],
        'viewConfigJ3xRaw',
    );
    /**/

    /**/
    $upgrade_ZoneButtons[] = new zoneButtons(
        '',
        Route::_('index.php?option=com_rsgallery2&view=configJ3x&layout=RawEdit'),
        // Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=ConfigRawEdit'),
        Text::_('COM_RSGALLERY2_CONFIG_J3X_RAW_EDIT'),
        Text::_('COM_RSGALLERY2_CONFIG_J3X_RAW_EDIT_DESC'),
        ['icon-equalizer', 'icon-edit'],
        'viewEditConfigJ3xRaw',
    );
    /**/
}

//--- outdated zone -----------------------------

$outdated_Zone = new zoneContainer(
    Text::_('COM_RSGALLERY2_OUTDATED_ZONE'),
    Text::_('COM_RSGALLERY2_OUTDATED_ZONE_DESC'),
    'outdated',
    'outdatedZone',
);

$outdated_ZoneButtons = [];

//--- developer zone -----------------------------

$developer_Zone = new zoneContainer(
    Text::_('COM_RSGALLERY2_DEVELOPER_ZONE'),
    Text::_('COM_RSGALLERY2_DEVELOPER_ZONE_DESCRIPTION'),
    'developer',
    'developerZone',
);

$developer_ZoneButtons = [];

/**/
$developer_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop&layout=InstallMessage'),
    Text::_('Test Install/Update message'),
    Text::_('Check the output result of the install finish and upgrade finish result view part'),
    ['icon-eye-open', 'icon-expand'],
    'view___',
);
/**/


//$developer_ZoneButtons[] =  new zoneButtons(
// '',
////    Route::_('index.php?option=com_rsgallery2&view=develop&layout=InstallMessage'),
// 'Develop.autoUpgradeJ3xDbs',
//    '',
//Text::_('Test Install/Update message'),
//Text::_('Test auto upgrade J3x DBs '),
//array('icon-eye-open', 'icon-expand'),
//'view___'
//);
/**/

/**/
$developer_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop&layout=ManifestInfo'),
    Text::_('COM_RSGALLERY2_MANIFEST_INFO'),
    Text::_('COM_RSGALLERY2_MANIFEST_INFO_DESC'),
    ['icon-eye-open', 'icon-briefcase'],
    'view___',
);
/**/

/**/
$developer_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop'),
    Text::_('COM_RSGALLERY2_DEVELOP_VIEW'),
    Text::_('COM_RSGALLERY2_DEVELOP_VIEW_DESC'),
    ['icon-enter', 'icon-compass'],
    'view___',
);
/**/
/**/
$developer_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop&layout=createGalleries'),
    Text::_('COM_RSGALLERY2_DEVELOP_CREATE_GALLERIES'),
    Text::_('COM_RSGALLERY2_DEVELOP_CREATE_GALLERIES_DESC'),
    ['icon-copy', 'icon-images', 'icon-notification-2'],
    'view___',
);
/**/

/**/
$developer_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop&layout=createImages'),
    Text::_('COM_RSGALLERY2_DEVELOP_CREATE_IMAGES'),
    Text::_('COM_RSGALLERY2_DEVELOP_CREATE_IMAGES_DESC'),
    ['icon-copy', 'icon-image', 'icon-notification-2'],
    'view___',
);
/**/

/**/
$developer_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop&layout=defaultParams'),
    Text::_('COM_RSGALLERY2_DEVELOP_DEFAULT_PARAMS'),
    Text::_('COM_RSGALLERY2_DEVELOP_DEFAULT_PARAMS_DESC'),
    ['icon-copy', 'icon-image', 'icon-notification-2'],
    'view___',
);
/**/

//--- developer test zone -----------------------------

$developer4Test_Zone = new zoneContainer(
    Text::_('COM_RSGALLERY2_DEVELOP_TEST_ZONE'),
    Text::_('COM_RSGALLERY2_DEVELOP_TEST_ZONE_DESCRIPTION'),
    'devTest',
    'devTestZone',
);

$developer4Test_ZoneButtons = [];

/**/
// $repair_ZoneButtons[] = new zoneButtons(
$developer4Test_ZoneButtons[] = new zoneButtons(
    'Maintenance.CheckImagePaths',
    '',
    Text::_('COM_RSGALLERY2_CHECK_IMAGE_PATHS'),
    Text::_('COM_RSGALLERY2_CHECK_IMAGE_PATHS_DESC'),
    ['icon-search', 'icon-folder', 'icon-image'],
    'viewImagePaths',
);
/**/
//$developer4Test_ZoneButtons[] = new zoneButtons(
//    '',
//    Route::_('index.php?option=com_rsgallery2&view=galleries&layout=galleries_tree'),
//    Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE'),
//    Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_DESC'),
//    array('icon-images', 'icon-tree-2', 'icon-notification-2'),
//    'viewGalleriesRaw'
//);
//
//$developer4Test_ZoneButtons[] = new zoneButtons(
//    'Galleries.reinitNestedGalleryTable',
//    '',
//    Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET'),
//    Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET_DESC'),
//    array('icon-images', 'icon-database', 'icon-undo', 'icon-notification-2'),
//    'viewGalleriesRaw'
//);
//

//
//$developer4Test_ZoneButtons[] = new zoneButtons(
//    '',
//Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3xgalleries'),
//Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES'),
//Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_DESC'),
//array('icon-new-tab', 'icon-images'),
//'viewdbtransferj3xgalleries'
//);
///**/
//
//
//$developer4Test_ZoneButtons[] = new zoneButtons(
//    '',
//Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximages'),
//Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES'),
//Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_DESC'),
//array('icon-new-tab', 'icon-database', 'icon-image'),
//'viewdbtransferj3ximages'
//);
///**/
//
//
//$developer4Test_ZoneButtons[] = new zoneButtons(
//    '',
//Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=movej3ximages'),
//Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES'),
//Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_DESC'),
//array('icon-new-tab', 'icon-copy', 'icon-image', 'icon-notification-2'),
//'viewdbtransferj3ximages'
//);
///**/

/**/
$developer4Test_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=movej3ximages'),
    Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES'),
    Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_DESC'),
    ['icon-new-tab', 'icon-copy', 'icon-image', 'icon-notification-2'],
    'viewdbtransferj3ximages',
);
/**/

/**/
$developer4Test_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop&layout=createGalleries'),
    Text::_('COM_RSGALLERY2_DEVELOP_CREATE_GALLERIES'),
    Text::_('COM_RSGALLERY2_DEVELOP_CREATE_GALLERIES_DESC'),
    ['icon-copy', 'icon-images', 'icon-notification-2'],
    'view___',
);
/**/

/**/
$developer4Test_ZoneButtons[] = new zoneButtons(
    '',
    Route::_('index.php?option=com_rsgallery2&view=develop&layout=createImage'),
    Text::_('COM_RSGALLERY2_DEVELOP_CREATE_IMAGES'),
    Text::_('COM_RSGALLERY2_DEVELOP_CREATE_IMAGES_DESC'),
    ['icon-copy', 'icon-image', 'icon-notification-2'],
    'view___',
);
/**/

//---  -----------------------------

function DisplayButton($button)
{
	if ($button->isTask) {

        /** button */

        //                                               onclick="Joomla.submitbutton('Maintenance.CheckImagePaths'); return false;"
		// <button type="submit" class="btn btn-success" onclick="Joomla.submitbutton('contact.batch');return false;">
        // <button type="submit" class="btn btn-success" onclick="Joomla.submitbutton('banner.batch');return false;">
        //    <?php echo Text::_('JGLOBAL_BATCH_PROCESS');
        //</button>

		$submitTask = "'" . $button->task . "'";
		$onclick = "Joomla.submitbutton(" . $submitTask . "); return false;";

        echo '<div class="rsg2-icon-button-container">';
        echo '<button type="submit"
			class="rsg2-icon-button-button"
			onclick="' . $onclick . '"'
			. '>';

        echo '    <figure class="rsg2-icon">';
        echo '        <div class="rsg2-icon-icon">';
        foreach ($button->classIcons as $Idx => $imageClass) {
            echo '              <span class="' . $imageClass . ' icoMoon icoMoon0' . $Idx . '" style="font-size:30px;"></span>'; // style="font-size:30px;"
        }
        echo '            </div>';
        echo '        <figcaption class="rsg2-text">';
        echo '            <div class="maint-title">' . $button->textTitle . '</div>';
        echo '            <div class="maint-text">' . $button->textInfo . '</div>';
        echo '        </figcaption>';
        echo '    </figure>';
        echo '</button>';

        echo '</div>'; // rsg2-icon-button-container
        /**/

    } else {

        /* call link */

        echo '<div class="rsg2-icon-button-container">';

        echo '<a href="' . $button->link . '" class="' . $button->classButton . '">';
        echo '        <div class="rsg2-icon-icon">';
        echo '    <figure class="rsg2-icon">';
        foreach ($button->classIcons as $Idx => $imageClass) {
            echo '            <span class="rsg2-icon-button-link ' . $imageClass . ' icoMoon icoMoon0' . $Idx . '" style="font-size:30px;"></span>'; // style="font-size:30px;"
        }
        echo '            </div>';
        echo '        <figcaption class="rsg2-text">';
        echo '            <div class="maint-title">' . $button->textTitle . '</div>';
        echo '            <div class="maint-text">' . $button->textInfo . '</div>';
        echo '        </figcaption>';
        echo '    </figure>';
        echo '</a>';

        echo '</div>'; // rsg2-icon-button-container
        /**/
    }

}

//---  -----------------------------

function DisplayZone($Zone, $Buttons)
{
    echo '<div class="icons-panel ' . $Zone->classContainer . '">';

    echo zoneTitle($Zone->textTitle, $Zone->classTitle);
    echo zoneInfo($Zone->textInfo);

    echo '<div class="rsg2-icon-bar">';

    foreach ($Buttons as $Button) {
        DisplayButton($Button);
    }

    echo '</div>';
    echo '</div>';
}

//---  -----------------------------

function zoneTitle($title = 'Unknown title', $zoneClass = '')
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
function zoneInfo($info = 'Unknown zone info')
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

	<form action="<?php
    echo Route::_('index.php?option=com_rsgallery2&view=maintenance'); ?>"
	      method="post" name="adminForm" id="adminForm" class="form-validate"
	      enctype="multipart/form-data"
	>
		<div class="d-flex flex-row">
            <?php
            if (!empty($this->sidebar)) : ?>
				<div id="j-sidebar-container" class="">
                    <?php
                    echo $this->sidebar; ?>
				</div>
            <?php
            endif; ?>
			<!--div class="<?php
            echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
			<div class="flex-fill">
				<div id="j-main-container" class="j-main-container">

					<div class="flex-main-row">

                        <?php

                        //--- All zones -----------------------------

                        // RSG 2 standard
                        // DisplayZone($RSG2_Zone, $RSG2_ZoneButtons);

                        if ($this->isDevelop) {
                            // Developer support latest tries
                            DisplayZone($developer4Test_Zone, $developer4Test_ZoneButtons);
                        }

                        // raw database
                        DisplayZone($rawDatabase_Zone, $rawDatabase_ZoneButtons);

                        // outdated ???
                        // DisplayZone($outdated_Zone, $outdated_ZoneButtons);

                        // repair
                        DisplayZone($repair_Zone, $repair_ZoneButtons);

                        // danger
                        DisplayZone($danger_Zone, $danger_ZoneButtons);

                        if ($this->isJ3xRsg2DataExisting) {
                            // upgrade j3x
                            DisplayZone($upgrade_Zone, $upgrade_ZoneButtons);
                        }

                        if ($this->isDevelop) {
                            // developer
                            DisplayZone($developer_Zone, $developer_ZoneButtons);
                        }
                        /**/

                        ?>
					</div>
				</div>
			</div>
		</div>

		<!--        <div id="hidden-input-buttons" style="display: none;">-->
		<!--            <div class="control-group">-->
		<!--                <label for="config_file" class="control-label">--><?php
        //echo Text::_('RSG2 import configuration from file'); ?><!--</label>-->
		<!--                <div class="controls">-->
		<!--                    <input class="form-control-file" id="config_file" name="config_file" type="file" >-->
		<!--                </div>-->
		<!--            </div>-->
		<!--        </div>-->

<!--		<div class="rsg2-icon-button-container">-->
<!--			<a href="/Joomla4x/administrator/index.php?option=com_rsgallery2&amp;task=Maintenance.CheckImagePaths&--><?php //echo Session::getFormToken().'=1'; ?><!--"-->
<!--					class="viewImagePaths">-->
<!--				<figure class="rsg2-icon">-->
<!--					<span class="icon-search icoMoon icoMoon00" style="font-size:30px;"></span>-->
<!--					<span class="icon-folder icoMoon icoMoon01" style="font-size:30px;"></span>-->
<!--					<span class="icon-image icoMoon icoMoon02" style="font-size:30px;"></span>-->
<!--					<figcaption class="rsg2-text">-->
<!--						<div class="maint-title">Check image paths</div>-->
<!--						<div class="maint-text">Checks image paths for existence (by gallery ids and sizes in config)</div>-->
<!--					</figcaption>-->
<!--				</figure>-->
<!--			</a>-->
<!--		</div>-->


		<input type="hidden" name="task" value=""/>
		<!--input id="token" type="hidden" name="' . \Joomla\CMS\Session\Session::getFormToken() . '" value="1" /-->';
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>

<?php

