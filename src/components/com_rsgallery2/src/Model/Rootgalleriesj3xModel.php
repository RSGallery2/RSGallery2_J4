<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Registry\Registry;
use RuntimeException;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
     * @since      5.1.0
 */
class Rootgalleriesj3xModel extends Galleriesj3xModel
{
    /**
     * @param $gallery
     *
     *
     * @since 4.5.0.0
     *
    public function assignGalleryUrl($gallery)
    {
        try {

            parent::assignGalleryUrl($gallery);

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Rootgalleriesj3xModel: AssignUrl_AsInline: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }
    /**/

    /**
     * returns list of random images data enriched with image paths and urls
     *
     * @param $random_count
     *
     * @return array
     *
     * @since  5.1.0
     */
    public function randomImages($random_count)
    {
        $randomImages = [];

        // ToDo: try catch ...

        if ($random_count > 0) {
            // toDo: create imagesJ3x model which does set the url to j3x
            $imagesModel  = $this->getInstance('Images', 'RSGallery2Model');
            $randomImages = $imagesModel->randomImages($random_count);

            $galleryJ3xModel = $this->getInstance('Galleryj3x', 'RSGallery2Model');
            $galleryJ3xModel->AddLayoutData($randomImages);
        }

        return $randomImages;
    }

    /**
     * List of laterst images with layout data
     * @param $latest_count
     *
     * @return array
     */
    public function latestImages($latest_count)
    {
        $latestImages = [];

        // ToDo: try catch ...

        if ($latest_count > 0) {
            // toDo: create imagesJ3x model which does set the url to j3x
            $imagesModel  = $this->getInstance('Images', 'RSGallery2Model');
            $latestImages = $imagesModel->latestImages($latest_count);

            $galleryJ3xModel = $this->getInstance('Galleryj3x', 'RSGallery2Model');
            $galleryJ3xModel->AddLayoutData($latestImages);
        }

        return $latestImages;
    }

} // class
