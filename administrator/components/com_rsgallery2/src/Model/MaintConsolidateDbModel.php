<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2014-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageReferences;


/**
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
     * @since   5.1.0
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

        // Include watermarked files to search and check for missing
        //$ImageReferences->UseWatermarked = $this->IsWatermarkActive();
        // $ImageReferences->UseWatermarked = true; // ToDO: remove
        //$ImageReferences       = new ImageReferences ($this->IsWatermarkActive());
        $ImageReferences       = new ImageReferences (1);
        $this->ImageReferences = $ImageReferences;

        try {
            // toDo:

            $ImageReferences->CollectImageReferences();
        } catch (\RuntimeException $e) {
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
	public function IsWatermarkActive()
	{
		if (empty($this->IsWatermarkActive))
		{
			$this->IsWatermarkActive = false;

			try
			{
				$db    = $this->getDatabase();
				$query = $db->createQuery()
					->select($db->quoteName('value'))
					->from($db->quoteName('#__rsgallery2_config'))
					->where($db->quoteName('name') . " = " . $db->quote('watermark'));
				$db->setQuery($query);

				$this->IsWatermarkActive = $db->loadResult();
			}
			catch (\RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing query: "' . $query . '"' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');

			}
		}

		return $this->IsWatermarkActive;
	}
	/**/





}

