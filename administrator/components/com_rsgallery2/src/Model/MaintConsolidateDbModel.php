<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (C) 2014-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageReferences;
use RuntimeException;

use function defined;

/**
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
 * @since       version
 */
class MaintConsolidateDbModel extends BaseDatabaseModel
{

    /**
     * Image artefacts as list
     * Each entry contains existing image objects (? where at least one is missing ?)
     *
     * @var ImageReferences
     *
     * @since 4.3.0
     */
    protected $ImageReferences;

    /**
     * Returns List of image "artefacts"
     *
     * @return ImageReferences
     *
     * @since 4.3.0
     */
    public function GetImageReferences()
    {
        if (empty($this->ImageReferences)) {
            $this->CreateDisplayImageData();
        }

        return $this->ImageReferences;
    }

    /**
     * Collects image artefacts as list
     * Each entry contains existing image objects where at least one is missing
     *
     * @return string operation messages
     *
     * @since 4.3.0
     */
    public function CreateDisplayImageData()
    {
        // ToDo: Instead of message return HasError;
        $msg = ''; //  ": " . '<br>';

        try {
            // Include watermarked files to search and check for missing
            //$ImageReferences->UseWatermarked = $this->IsWatermarkActive();
            // $ImageReferences->UseWatermarked = true; // ToDO: remove
            //$ImageReferences       = new ImageReferences ($this->IsWatermarkActive());
            $ImageReferences       = new ImageReferences (true);
            $this->ImageReferences = $ImageReferences;

            $ImageReferences->CollectImageReferences();
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing CollectImageReferences: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $msg;
    }

    /**
     * Tells if watermark is activated on user config
     *
     * @return bool true when set in config data
     *
     * @since 4.3.0
     */
    /** read config more direct global$rsgconfig ...
     * public function IsWatermarkActive()
     * {
     * if (empty($this->IsWatermarkActive))
     * {
     * $this->IsWatermarkActive = false;
     *
     * try
     * {
     * $db    = $this->getDatabase();
     * $query = $db->getQuery(true)
     * ->select($db->quoteName('value'))
     * ->from($db->quoteName('#__rsgallery2_config'))
     * ->where($db->quoteName('name') . " = " . $db->quote('watermark'));
     * $db->setQuery($query);
     *
     * $this->IsWatermarkActive = $db->loadResult();
     * }
     * catch (\RuntimeException $e)
     * {
     * $OutTxt = '';
     * $OutTxt .= 'Error executing query: "' . $query . '"' . '<br>';
     * $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
     *
     * $app = Factory::getApplication();
     * $app->enqueueMessage($OutTxt, 'error');
     *
     * }
     * }
     *
     * return $this->IsWatermarkActive;
     * }
     * /**/

}

