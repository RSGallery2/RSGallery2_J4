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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;


// $language = Factory::getLanguage();
$app = Factory::getApplication();
$isLoaded = $app->getLanguage()->load('com_installer');


/**
 * Rsgallery2 Component changelog Model
 *
 * @since  5.0.0.4
 */
class ChangeLogModel extends BaseModel
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
     * @since  5.0.0.4
	 */
	protected $text_prefix = 'COM_RSGALLERY2';

	/**
	 * The type alias for this content type. Used for content version history.
	 *
	 * @var    string
	 * @since  5.0.0.4
	 */
	public $typeAlias = 'com_rsgallery2.changelog';


	public static function changeLogElements ($lastVersion = '') // $minVersion = '0.0.0.0' $minVersion = ''
	{
	    //--- read xml to json ---------------------------------------------------

		$changelogUrl = Route::_(Uri::root() . '/administrator/components/com_rsgallery2/changelog.xml');
		$changelogs = simplexml_load_file($changelogUrl);

		//Encode the SimpleXMLElement object into a JSON string.
		$jsonString = json_encode($changelogs);
		//Convert it back into an associative array
		$jsonArray = json_decode($jsonString, true);

		//--- reduce to version items -------------------------------------------

		$jsonChangeLogs = [];

		// standard : change log for each version are sub items
		if (array_key_exists ('changelog', $jsonArray)) {

			$testLogs = $jsonArray ['changelog'];

			foreach ($testLogs as $changeLog) {

				// All versions
				if ( empty ($lastVersion))
				{
					$jsonChangeLogs [] = $changeLog;;
				} else {
					// selected last versions
					$logVersion = $changeLog ['version'];
					if (version_compare($logVersion, $lastVersion, '>')) {
						$jsonChangeLogs [] = $changeLog;
					}
				}
			}
		}

		return $jsonChangeLogs;
	}

    /**
	 * @param $jsonChangeLogs json formed changelog xml file
	 *
	 * @return array
	 *
	 * @since version
	 */
	public static function changeLogsData2Html ($jsonChangeLogs)
	{
		$changeLogsHtml = [];

		foreach ($jsonChangeLogs as $changelog)
		{
			$changeLogsHtml [] = self::changeLogData2Html ($changelog);
		}

		return $changeLogsHtml;
	}

    /**
    COM_INSTALLER_CHANGELOG="Changelog"
    COM_INSTALLER_CHANGELOG_ADDITION="New Features"
    COM_INSTALLER_CHANGELOG_CHANGE="Changes"
    COM_INSTALLER_CHANGELOG_FIX="Bug Fixes"
    COM_INSTALLER_CHANGELOG_LANGUAGE="Language"
    COM_INSTALLER_CHANGELOG_NOTE="Notes"
    COM_INSTALLER_CHANGELOG_REMOVE="Removed Features"
    COM_INSTALLER_CHANGELOG_SECURITY="Security Fixes"
    COM_INSTALLER_CHANGELOG_TITLE="Changelog - %s - %s"
    /**/

    public static function changeLogSectionTitle2Html ($key)
    {
        $html = '';

        $keyTranslation = '';
        $class = '';

        /**/
        switch ($key) {
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

    public static function changeLogSectionData2Html ($values) {
        $html =  [];
        $itemId = 'item';

        //--- extract list of items --------------------

        if (array_key_exists($itemId, $values)) {
            $items =  $values [$itemId];
        } else {

            $items = [];

            foreach ($values as $value) {
                $subItems = $value [$itemId];
                if ( ! is_array ($subItems)){
                    $subItems = array($subItems);
                }
                foreach ($subItems as $item) {
                    $items [] = $item;
                }
            }
        }

        if ( ! is_array ($items)){
            $items = array($items);
        }

        //--- collect item html --------------------

        $html [] = '<ul>';

        foreach ($items as $item)
        {
            if (!empty ($item))
            {
                $html [] = '    <li>';
                $html [] = '        <div class="changelog_value">' . $item . '</div>';
                $html [] = '    </li>';
            }
        }

        $html [] = '</ul>';

        return join ('', $html);
    }

    public static function changeLogData2Html ($jsonChangeLog)
	{
		$html = [];

//		$html = [] <version>5.0.0.4</version>
//		<date>2020.03.24 14:28</date>

        //$html[] = '<table class="table table-striped table-light w-auto table_morecondensed">';
        $html[] = '<table class="table table-striped table-light table_morecondensed change-log-table" caption-side="top">';
        $html[] = '    <caption caption-side="top" class="change-log-caption">';
        $html[] = '    <strong>';
//        $html[] = '        <div>Version: ' . $jsonChangeLog ['version'] . '</div>';
//        $html[] = '        <div>Date: ' . $jsonChangeLog ['date'] . '</div>';
        $html[] = '        Version: ' . $jsonChangeLog ['version'] . '&nbsp;' . 'Date: ' . $jsonChangeLog ['date'];
        $html[] = '    </strong>';
        $html[] = '    </caption>';


		foreach ($jsonChangeLog as $key => $value)
		{
		    $sectionTitle = self::changeLogSectionTitle2Html ($key);
            if ( ! empty ($sectionTitle)) {

                $sectionDtaList = self::changeLogSectionData2Html ($value);

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

		return join ('', $html);
	}

	//--- general collapse function ------------------------

    /**
     * @param string $title
     * @param string [] $changelogTables
     * @param string $id
     * @param bool $isCollapsed
     *
     * @return string
     *
     * @since version
     */
    public static function collapseContent($title, $changelogTables, $id, $isCollapsed=true)
    {

        //--- table html(s) to one string --------------

        foreach ($changelogTables as $htmlEle) {
            $html[] = '            ' . $htmlEle;
        }
        $changelogsHtml = implode('</br>', $html);

        //--- collapsable frame around content ---------------------------------------------

        $show = $isCollapsed ? '' : 'show';
        $collapsed = $isCollapsed ? 'collapsed' : 'collapsed';
        $ariaExpanded = $isCollapsed ? 'false' : 'true';

        $collapsed = <<<EOT
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



        return $collapsed;
    }
}

?>

