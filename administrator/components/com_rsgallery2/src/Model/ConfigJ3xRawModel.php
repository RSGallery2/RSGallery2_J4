<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2023-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\Table\Table;

/**
 * Item Model for a Configuration items (options).
 *
 * @since __BUMP_VERSION__
 */
class ConfigJ3xRawModel extends BaseModel
{

    /**
     * save raw to parameters ...
     *
     * @return string
     *
     * @since __BUMP_VERSION__
     */
    public function saveFromForm()
    {
        // $msg = "Rsgallery2ModelConfigRaw: ";
        $isSaved = false;

        $input = Factory::getApplication()->input;
        $data  = $input->post->get('jform', [], 'array');

        $isSaved = $this->saveItems($data);

        return $isSaved;
    }

    /**
     * @param $configurationItems
     *
     * @return bool
     *
     * @throws \Exception
     * @since __BUMP_VERSION__
     */
    public function saveItems($configurationItems): bool
    {
        $isSaved = false;

        // ToDo: Remove bad injected code (Read xml -> type / check xml ..

        // ToDo: Try ...

        //$row = $this->getTable();
        $Rsg2Id = ComponentHelper::getComponent('com_rsgallery2')->id;
        $table  = Table::getInstance('extension');
        // Load the previous Data
        if (!$table->load($Rsg2Id)) {
            throw new \RuntimeException($table->getError());
        }

        // ToDo: Use result
        $SecuredItems = $this->SecureConfigurationItems($configurationItems);

        //$table->bind(array('params' => $configurationItems));
        $table->bind(['params' => $SecuredItems]);

        // check for error
        if (!$table->check()) {
			Factory::getApplication()->enqueueMessage(Text::_('ConfigJ3xRaw: Check for save failed ') . $table->getError(), 'error');
        } else {
            // Save to database
            if ($table->store()) {
                $isSaved = true;
            } else {
				Factory::getApplication()->enqueueMessage(Text::_('ConfigJ3xRaw: Store for save failed ') . $table->getError(), 'error');
            }
        }

        return $isSaved;
    }

    /**
     * @param $configurationItems
     *
     * @return array
     *
     * @since version
     */
    public function SecureConfigurationItems($configurationItems)
    {
        $securedItems = [];

        $filter = InputFilter::getInstance();
        //$filter         = FilterInput::getInstance();

// ToDo: JFilterInput::clean Check other types in joomla doc

        foreach ($configurationItems as $key => $value) {
            $secured = ''; // preset

            // ToDo: is secure needed,
            // ToDo: other vars ?

            // Test types in different way
            switch ($key) {
                case 'advancedSef':
                case 'isDebugBackend':
                case 'isDebugSite':
                case 'isDevelop':
                case 'thumb_size':
                case 'thumb_style':
                case 'jpegQuality':
                case 'keepOriginalImage':
                case 'useJ3xOldPaths':

                    $secured = $filter->clean($value, 'int');
                    break;

                case 'ftp_path': // '\'images\/rsgallery2\',',
                case 'imgPath_root': //'images\/rsgallery2',
                case 'imgPath_original': //'\/images\/rsgallery\/original',
                case 'imgPath_display': //'\/images\/rsgallery\/display',
                case 'imgPath_thumb': //'\/images\/rsgallery\/thumb',

                    $secured = $filter->clean($value, 'STRING');
                    break;

                case 'intro_text': // ''
                    $secured = $filter->clean($value, 'html');
                    break;

                case 'image_size': // '800,600,400',
                    $secured = $filter->clean($value, 'STRING');
                    break;

                case 'allowedFileTypes':// 'jpg,jpeg,gif,png',
                default:

                    $secured = $filter->clean($value, 'STRING');
                    break;
            }

            $inType = gettype($value);
            $outype = gettype($secured);

            $securedItems [$key] = strval($secured);
        }

        return $securedItems;
    }

    /**
     * Write single configuration parameter
     * Use seldom and with care ! (+ separate set ;-) )
     *
     * @param   string  $param
     * @param   string  $value
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public static function writeConfigParam($param = '', $value = '')
    {
        // Load the current component params.
        $params = ComponentHelper::getParams('com_rsgallery2');
        // Set new value of param(s)
        $params->set($param, $value);

        // Save the parameters
        $componentid = ComponentHelper::getComponent('com_rsgallery2')->id;
        $table       = Table::getInstance('extension');
        $table->load($componentid);
        $table->bind(['params' => $params->toString()]);

        // check for error
        if (!$table->check()) {
            throw new \RuntimeException($table->getError());
        }
        // Save to database
        if (!$table->store()) {
            throw new \RuntimeException($table->getError());
        }

        return true;
    }

}
