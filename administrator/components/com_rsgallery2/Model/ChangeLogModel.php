<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Rsgallery2 Component changelog model
 * Reads a joomla changelog file and returns the json elements
 * The result can be converted to a array of html tables per version
 * these may be surrounded by a
 *
 * @since  5.0.0.4
 */
class ChangeLogModel
{
    // no on install (com_installer) public $changeLogFile = JPATH_COMPONENT_ADMINISTRATOR . '/changelog.xml';
    public $changeLogFile = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/changelog.xml';

    /**
     * ChangeLogModel constructor.
     * @param null $changeLogFile path to changelog file different from standard
     *
     * @throws Exception
     * @since version
     */
    public function __construct($changeLogFile = null)
    {
        if (!empty ($changeLogFile)) {
            $this->changeLogFile = $changeLogFile;
        }

        // standard joomla texts fopr title
        $app = Factory::getApplication();
        $app->getLanguage()->load('com_installer');
    }

    /**
     * Selects array of changelog sections
     * On given previous version all older are omitted
     *
     * @param string $previousVersion leave out this and older
     *
     * @return array associative array of changelog items per release version
     *
     * @since version
     */
    public function changeLogElements($previousVersion = '')
    {
        $jsonChangeLogs = [];

        if ( ! file_exists($this->changeLogFile))
        {
            $OutTxt = 'changeLogFile not found ' . $this->changeLogFile . '"';
            Factory::getApplication()->enqueueMessage($OutTxt, 'error');
        }
        else {

            //--- read xml to json ---------------------------------------------------

            $changelogs = simplexml_load_file($this->changeLogFile);

            if (!empty ($changelogs)) {
                //Encode the SimpleXMLElement object into a JSON string.
                $jsonString = json_encode($changelogs);
                //Convert it back into an associative array
                $jsonArray = json_decode($jsonString, true);

                //--- reduce to version items -------------------------------------------

                // standard : change log for each version are sub items
                if (array_key_exists('changelog', $jsonArray)) {

                    $testLogs = $jsonArray ['changelog'];

                    foreach ($testLogs as $changeLog) {

                        // all versions
                        if (empty ($previousVersion)) {
                            $jsonChangeLogs [] = $changeLog;
                        } else {
                            // selected last versions
                            $logVersion = $changeLog ['version'];
                            if (version_compare($logVersion, $previousVersion, '>')) {
                                $jsonChangeLogs [] = $changeLog;
                            }
                        }
                    }
                }
            }
        }

        return $jsonChangeLogs;
    }

    /**
     *
     * @param array $jsonChangeLogs associative array of changelog items per release version
     *
     * @return string [] array of html tables per release version
     *
     * @since version
     */
    public static function changeLogsData2Html($jsonChangeLogs)
    {
        $changeLogsHtml = [];

        foreach ($jsonChangeLogs as $changelog) {
            $changeLogsHtml [] = self::changeLogData2Html($changelog);
        }

        return $changeLogsHtml;
    }

    /**
     * Creates a striped table for this version
     *
     * @param array $versionChangeLog associative array of changelog items of one version
     *
     * @return string html: striped table with header from version and date.
     *                rows containing the elements column title and text
     *                titles get badge css from type of item
     *
     * @since version
     */
    public static function changeLogData2Html($versionChangeLog)
    {
        $html = [];

        // table header
        $html[] = '<table class="table table-striped table-light table_morecondensed change-log-table" caption-side="top">';
        $html[] = '    <caption caption-side="top" class="change-log-caption">';
        $html[] = '    <strong>';
        $html[] = '        Version: ' . $versionChangeLog ['version'] . '&nbsp;' . 'Date: ' . $versionChangeLog ['date'];
        $html[] = '    </strong>';
        $html[] = '    </caption>';


        foreach ($versionChangeLog as $key => $value) {

            // create badge title (May not exist)
            $sectionTitle = self::changeLogSectionTitle2Html($key);

            // valid item
            if (!empty ($sectionTitle)) {

                // item texts
                $sectionDtaList = self::changeLogSectionData2Html($value);
            }

            //--- create row ----------------------------------------------

            // valid item
            if (!empty ($sectionTitle)) {

                $html[] = '<tr>';

                // key
                $html[] = '    <td class="changelog_key">';
                $html[] = '        ' . $sectionTitle;
                $html[] = '    </td>';

                // values
                $html[] = '    <td class="changelog_values_area">';
                $html[] = '        ' . $sectionDtaList;
                $html[] = '    </td>';

                $html[] = '</tr>';
            }
        }

        $html[] = '</table>';

        return join('', $html);
    }


    /**
     * Returns html string of a bootstrap badge depending of the given type.
     * The title text is also depending on given type. standard joomla translations are used
     *
     * @param string $type defines the type of badge.
     *
     * @return string html of a bootstrap badge.
     *                If type is like version then an empty string will be returned
     *
     * @since version
     */
    public static function changeLogSectionTitle2Html($type)
    {
        /**
         * Standard joomla text enabled in constructor
         * COM_INSTALLER_CHANGELOG="Changelog"
         * COM_INSTALLER_CHANGELOG_ADDITION="New Features"
         * COM_INSTALLER_CHANGELOG_CHANGE="Changes"
         * COM_INSTALLER_CHANGELOG_FIX="Bug Fixes"
         * COM_INSTALLER_CHANGELOG_LANGUAGE="Language"
         * COM_INSTALLER_CHANGELOG_NOTE="Notes"
         * COM_INSTALLER_CHANGELOG_REMOVE="Removed Features"
         * COM_INSTALLER_CHANGELOG_SECURITY="Security Fixes"
         * COM_INSTALLER_CHANGELOG_TITLE="Changelog - %s - %s"
         * /**/

        $html = '';

        $keyTranslation = '';
        $class = '';

        /**/
        switch ($type) {
            case ("security"):
                $keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_SECURITY');
                $class = 'badge-danger';
                break;
            case ("fix"):
                $keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_FIX');
                $class = 'badge-dark';
                break;
            case ("language"):
                $keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_LANGUAGE');
                $class = 'badge-light';
                break;
            case ("addition"):
                $keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_ADDITION');
                $class = 'badge-success';
                break;
            case ("change"):
                $keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_CHANGE');
                $class = 'badge-danger';
                break;
            case ("remove"):
                $keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_REMOVE');
                $class = 'badge-info';
                break;
            case ("note"):
                $keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_NOTE');
                $class = 'badge-info';
                break;
        }
        /**/

        //	<span class="badge badge-pill badge-primary">Primary</span>
        if (!empty ($keyTranslation)) {
            $html .= '    <div class="badge badge-pill ' . $class . '">' . $keyTranslation . '</div>';
        }

        return $html;
    }

    /**
     * A nested list of items is used for a html unsigned list
     * The hierarchy is ignored
     *
     * @param string [[]] $values array of string variables, nested depth is two
     *
     * @return string html: unsigned list of changelog texts
     *
     * @since version
     */
    public static function changeLogSectionData2Html($values)
    {
        $html = [];
        $itemId = 'item';

        //--- extract list of items --------------------

        $items = [];

        // item in first column of array
        if (array_key_exists($itemId, $values)) {
            $items [] = $values [$itemId];
        } else {

            // items in nested column
            foreach ($values as $value) {
                // still nested
                $subItems = $value [$itemId];
                if (!is_array($subItems)) {
                    $subItems = array($subItems);
                }

                foreach ($subItems as $item) {
                    $items [] = $item;
                }
            }
        }

        //--- collect item html --------------------

        $html [] = '<ul>';

        foreach ($items as $item) {
            if (!empty ($item)) {
                $html [] = '    <li>';
                $html [] = '        <div class="changelog_value">' . $item . '</div>';
                $html [] = '    </li>';
            }
        }

        $html [] = '</ul>';

        return join('', $html);
    }

    //--- general collapse function ------------------------

    /**
     * surround given html in collapse html string
     *
     * @param string [] $changelogHtmlTables array of html tables per release version
     * @param string $id added to aria and div ids
     * @param bool $isCollapsed start state
     *
     * @return string html: collapsible element
     *
     * @since version
     */
    public static function collapseContent($changelogHtmlTables, $id, $isCollapsed = true)
    {

        $changelogCss = self::changelogCss();

        //--- table html(s) to one string --------------

        $html = [];
        foreach ($changelogHtmlTables as $htmlTable) {
            $html[] = '            ' . $htmlTable;
        }
        $changelogsHtml = implode('</br>', $html);

        //--- collapsable frame around content ------------------------------------------

        $title = Text::_('COM_INSTALLER_CHANGELOG');
        $show = $isCollapsed ? '' : 'show';
        $collapsed = $isCollapsed ? 'collapsed' : '';
        $ariaExpanded = $isCollapsed ? 'false' : 'true';

        $collapsedHtml = <<<EOT
        <row>
            <div class="card forCollapse">
                <h5 class="card-header">
                    <button class="btn $collapsed" type="button" data-toggle="collapse" data-target="#collapse-collapsed-$id" 
                        aria-expanded="$ariaExpanded" aria-controls="collapse-collapsed-$id" id="heading-collapsed-$id">
                        <i class="fa fa-chevron-down pull-right"></i>
                        $title
                    </button>
                </h5>
                <div id="collapse-collapsed-$id" class="collapse $show" aria-labelledby="heading-collapsed-$id">
                    <div class="card-body">
                        $changelogsHtml
                    </div>
                </div>
            </div>
        </row>
EOT;

        $collapsedContent = $changelogCss . $collapsedHtml;
        return $collapsedContent;
    }

    /**
     * css style for collapsed changelogs
     *
     * @return string html style element
     *
     * @since version
     */
    private static function changelogCss()
    {

        $html = <<<EOT
            <style>
                /* ToDo: More specific add dictionaries with class= gallery/images ... */
                .table caption {
                    caption-side: top;
                  white-space: nowrap;
                }
                
                .changelog_area {
                            display: flex;
                            flex-direction: row;
                  justify-content: flex-start;
                }
                
                .changelog_key {
                            min-width: 100px;
                  border-right: 2px solid red;
                }
                
                .changelog_value_area {
                            display: flex;
                            flex-direction: column;
                  flex-wrap: wrap;
                }
                
                .change-log-caption {
                            color: black;
                        }
                
                .change-log-table {
                            border-bottom: 2px solid black;
                }
                
                .card-header .fa {
                            transition: 0.3s transform ease-in-out;
                }
                
                .card-header .collapsed .fa {
                            transform: rotate(-90deg);
                }
            </style>
EOT;

        return $html;
    }

} // class



