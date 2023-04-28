<?php
/*
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright (c) 2023-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Field;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;

use Joomla\Filesystem\Path;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageExif;

/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "-- Select --" as first entry
 * Sorted by ordering (newest first)
 *
 * @since __BUMP_VERSION__
 */
class ExifListField extends ListField
{
    /**
     * The field type.
     *
     * @var string
     *
     * @since __BUMP_VERSION__
     */
	protected $type = 'ExifList';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  The field option objects.
     *
     * @since __BUMP_VERSION__
	 */
	protected function getOptions()
	{
        $options = [];

		try {
            $enabledTags = ImageExif::supportedExifTags();

            foreach ($enabledTags as $enabledTag) {

                $text = ImageExif::exifTranslationId($enabledTag);

                $option = new \stdClass();
                $option->value = $enabledTags;
                $option->text  = $text;
                $options[]     = $option;

            }

            //--- load additional language file --------------------------------------

            //$lang = JFactory::getLanguage();
            //$extension = 'com_helloworld';
            //$base_dir = JPATH_SITE;
            //$language_tag = 'en-GB';
            //$reload = true;
            //$lang->load($extension, $base_dir, $language_tag, $reload);

            $lang = Factory::getLanguage();
            $lang->load('com_rsg2_exif', Path::clean(JPATH_ADMINISTRATOR . '/components/' . 'com_rsgallery2'), null, false, true);

        }
		catch (\RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
	}

}

