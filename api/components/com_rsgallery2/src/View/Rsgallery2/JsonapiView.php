<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */
 
namespace Rsgallery\Component\Rsgallery\Api\View\Rsgallery2;

use Rsgallery\Component\Rsgallery\Api\Helper\Rsgallery2Helper;
use Rsgallery\Component\Rsgallery\Api\Serializer\Rsgallery2Serializer;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

// , $fieldsToRenderItem and $fieldsToRenderList. to set  the fields returned by your Model's getItem and getItems methods, r


/**
 * The rsgallery2 view
 *
 * @since  4.0.0
 */
class JsonapiView extends BaseApiView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function displayItem($tpl = null)
	{
		$testRsgallery2Text = "testRsgallery2Text";

		// Serializing the output
		//$result = json_encode($this->_output);
		$result = json_encode($testRsgallery2Text);

		// Pushing output to the document
		$this->getDocument()->setBuffer($result);

		return $this->getDocument()->render();
	}

	public function display($tpl = null)
	{
		$testRsgallery2Text = "testRsgallery2Text";

		// zzzz();

		// Serializing the output
		//$result = json_encode($this->_output);
		$result = json_encode($testRsgallery2Text);

		// Pushing output to the document
		$this->getDocument()->setBuffer($result);

		return $this->getDocument()->render();
	}

// ToDo: Later The hidden gem of the API view is another string array property, $relationship. In that view you list all the field names returned by your model which refer to related data.


}
