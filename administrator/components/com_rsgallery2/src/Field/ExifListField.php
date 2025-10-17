<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2023-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Field;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;
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
     * @since 5.1.0     */
    protected $type = 'ExifList';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  The field option objects.
     *
     * @since   5.1.0     */
    protected function getOptions()
    {
        $options = [];

        try {
            //--- load additional language file --------------------------------

            $lang = Factory::getApplication()->getLanguage();
            $lang->load('com_rsg2_exif',
                Path::clean(JPATH_ADMINISTRATOR . '/components/' . 'com_rsgallery2'), null, false, true);

            //--- exif tags ---------------------------------------------------------

            $enabledTags = ImageExif::supportedExifTags();

            foreach ($enabledTags as $enabledTag) {
                //--- type, name and  translation text --------------------------------

                [$type, $name] = ImageExif::tag2TypeAndName($enabledTag);
                $translationId = ImageExif::exifTranslationId($name);
                $translationText = Text::_($translationId);

                //--- create option element ----------------------------------------------

                $option = new \stdClass();
                $option->value = $enabledTag;
                //$option->text  = $translationText;
                $option->text = $type . ':' . $translationText;

                $options[] = $option;
            }
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}

