<?php
/*
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

// used in upload

namespace Rsgallery2\Component\Rsgallery2\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use RuntimeException;

use function defined;

/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "-- Select --" as first entry
 * Sorted by ordering (newest first)
 *
 * @since __BUMP_VERSION__
 */
class LayoutSlideshowField extends ListField
{
    /**
     * Cached array of the category items.
     *
     * @var    array
     * @since __BUMP_VERSION__
     */
//	protected static $options = [];

    /**
     * The field type.
     *
     * @var string
     *
     * @since __BUMP_VERSION__
     */
    //protected $type = 'LayoutGallerySelect';
    /**
     * @var string
     * @since version
     */
    protected $type = 'LayoutSlideshow';

    /**
     * Method to get the field input markup for a generic list.
     * Use the multiple attribute to enable multiselect.
     *
     * @return  string  The field input markup.
     *
     * @since __BUMP_VERSION__
     *
     * protected function getInput()
     * {
     * return $this->getOptions() ? parent::getInput() : '';
     * }
     * /**/

    /**
     * Method to get a list of options for a list input.
     *
     * @return   array The field option objects.
     *
     * @since __BUMP_VERSION__
     */
    protected function getOptions()
    {
        $current_slideshows = [];

        try {
//			// $user = Factory::getApplication()->getIdentity(); // Todo: Restrict to accessible galleries
//			$db    = Factory::getContainer()->get(DatabaseInterface::class);
//
//			$query = $db->getQuery(true)
//                ->select('id AS value, name AS text, level')
//                ->from($db->quoteName('#__rsg2_galleries'))
//				->where($db->quoteName('id') . ' != 1' )
////				->where($db->quoteName('published') . ' = 1')
//				// ToDo: Use option in XML to select ASC/DESC
//				->order('lft ASC');
//
//			// Get the options.
//			$galleries = $db->setQuery($query)->loadObjectList();

            /**
             * Detect available slideshows
             * Search in source folders
             */

            // Format values for slideshow dropdownbox
            $layoutFolder = JPATH_SITE . '/components/com_rsgallery2/layouts';

            $folders = Folder::folders($layoutFolder);
            foreach ($folders as $folder) {
                if (str_starts_with(strtolower($folder), "slideshow")) {
                    $subLayouts = $this->subLayouts($layoutFolder . '/' . $folder);

                    $subCount = count($subLayouts);

                    foreach ($subLayouts as $subLayout) {
                        $layout = $folder . '.' . $subLayout;

                        // only show default if multiple layouts exist
                        if ($subCount == 1) {
                            $userName = $folder;
                        } else {
                            $userName = $layout;
                        }

                        $current_slideshows[$layout] = $userName;
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        $options = $current_slideshows;

//        // Pad the option text with spaces using depth level as a multiplier.
//        for ($i = 0, $n = count($options); $i < $n; $i++) {
//            $options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . $options[$i]->text;
//        }

//        // Put "Select an option" on the top of the list.
//		array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_RSGALLERY2_SELECT_LAYOUT')));
        // Put "default" option" on the top of the list.
        array_unshift($options, HTMLHelper::_('select.option', 'default', Text::_('JDEFAULT')));

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    private function subLayouts(string $layoutFolder)
    {
        $subLayouts = [];

        $files = Folder::files($layoutFolder);
        foreach ($files as $file) {
            $validFile = true;

            $parts     = explode('.', $file);
            $extension = array_pop($parts);

            if ($extension != 'php') {
                $validFile = false;
            }

            if (str_contains($file, '.tmp')) {
                $validFile = false;
            }

            //
            $firstChar = $file[0];

            if ($firstChar == '.' && $firstChar == '_') {
                $validFile = false;
            }

            if ($validFile) {
                $basename      = implode($parts);
                $subLayouts [] = File::stripExt($basename);
            }
        }

        return $subLayouts;
    }
}

