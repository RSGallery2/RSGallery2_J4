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


	public static function changeLogElements ()
	{
		$changelogUrl = Route::_(Uri::root() . '/administrator/components/com_rsgallery2/changelog.xml');
		$changelogs = simplexml_load_file($changelogUrl);

//		$jsonChangeLogs = json_encode(, true);

		//Encode the SimpleXMLElement object into a JSON string.
		$jsonString = json_encode($changelogs);
		//Convert it back into an associative array
		$jsonArray = json_decode($jsonString, true);

		$jsonChangeLogs = $jsonArray;

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

		foreach ($jsonChangeLogs['changelog'] as $changelog)
		{
			$changeLogsHtml [] = self::changeLogData2Html ($changelog);
		}

		return $changeLogsHtml;
	}

	public static function changeLogData2Html ($jsonChangeLog)
	{
		$html = [];

//		$html = [] <version>5.0.0.4</version>
//		<date>2020.03.24 14:28</date>

		$html[] = '<div>Version: ' . $jsonChangeLog ['version'] . '</div>';
		$html[] = '<div>Date: ' . $jsonChangeLog ['date'] . '</div>';


		foreach ($jsonChangeLog as $key => $value)
		{

//			echo "<pre/>";print_r($item);
			$section = self::changeLogSection2Html ($key, $value);
			if ( ! empty ($section))
			{
				$html[] = $section;
			}
		}

		return $html;
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
	
	public static function changeLogSection2Html ($key, $values)
	{
		$html = '';

		$keyTranslation = '';
		$class = '';

		/**/
		switch ($key)
		{
			case ("security"):
				$keyTranslation = 'Security Fixes';
				$keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_SECURITY');
				$class = 'badge-danger'
				;
				break;
			case ("fix"):
				$keyTranslation = 'Bug Fixes';
				$keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_FIX');
				$class = 'badge-dark'
				;
				break;
			case ("language"):
				$keyTranslation = 'Language';
				$keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_LANGUAGE');
				$class = 'badge-light'
				;
				break;
			case ("addition"):
				$keyTranslation = 'New Features';
				$keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_ADDITION');
				$class = 'badge-success'
				;
				break;
			case ("change"):
				$keyTranslation = 'Changes';
				$keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_CHANGE');
				$class = 'badge-danger'
				;
				break;
			case ("remove"):
				$keyTranslation = 'Removed Features';
				$keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_REMOVE');
				$class = 'badge-info'
				;
				break;
			case ("note"):
				$keyTranslation = 'Notes';
				$keyTranslation = Text::_('COM_INSTALLER_CHANGELOG_NOTE');
				$class = 'badge-info'
				;
				break;
		}
		/**/

		//	<span class="badge badge-pill badge-primary">Primary</span>
		if (!empty ($keyTranslation))
		{
			$html .= '<div class="changelog_area">';

			$html .= '    <div class="changelog_key badge badge-pill ' . $class . '">' . $keyTranslation . '</div>';

			$html .= '    <div class="changelog_value_area">';

			//$items = $values->item;
			$items = $values ['item'];

			foreach ($items as $item)
			{
				if (!empty ($item))
				{
					$html .= '        <div class="changelog_value">' . $item . '</div>'; // .  '' .  '';
				}
			}

			$html .= '</div>'; // changelog_value_area

			$html .= '</div>'; // changelog_area
		}

		return $html;
	}
}
