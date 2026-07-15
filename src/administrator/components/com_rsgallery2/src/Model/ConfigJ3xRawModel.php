<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2023-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

use Joomla\CMS\Table\extension;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\Table\Table;

/**
 * Item Model for a Configuration items (options).
 *
     * @since      5.1.0
 */
class ConfigJ3xRawModel extends BaseModel
{
    /**
     * save raw to parameters ...
     *
     * @return string
     *
     * @since  5.1.0     */
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
     * @since  5.1.0     */
    public function saveItems($configurationItems): bool
    {
        $isSaved = false;

        // ToDo: Remove bad injected code (Read xml -> type / check xml ..

        // ToDo: Try ...

        //$row = $this->getTable();
        $Rsg2Id = ComponentHelper::getComponent('com_rsgallery2')->id;
        $db = Factory::getDbo();
        $table = new extension($db);
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
     * @since  5.1.0     */
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
            $secured = match ($key) {
                'advancedSef', 'isDebugBackend', 'isDebugSite', 'isDevelop', 'thumb_size', 'thumb_style', 'jpegQuality', 'keepOriginalImage', 'useJ3xOldPaths' => $filter->clean($value, 'int'),
                //'\/images\/rsgallery\/thumb',
                'ftp_path', 'imgPath_root', 'imgPath_original', 'imgPath_display', 'imgPath_thumb' => $filter->clean($value, 'STRING'),
                // ''
                'intro_text' => $filter->clean($value, 'html'),
                // '800,600,400',
                'image_size' => $filter->clean($value, 'STRING'),
                default => $filter->clean($value, 'STRING'),
            };

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
     * @since  5.1.0     */
    public static function writeConfigParam($param = '', $value = '')
    {
        // Load the current component params.
        $params = ComponentHelper::getParams('com_rsgallery2');
        // Set new value of param(s)
        $params->set($param, $value);

        // Save the parameters
        $componentid = ComponentHelper::getComponent('com_rsgallery2')->id;
        $db = Factory::getDbo();
        $table = new extension($db);
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
