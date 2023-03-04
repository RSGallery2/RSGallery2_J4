<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Registry\Registry;

//use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;
use Rsgallery2\Component\Rsgallery2\Site\Model\ImagePathsData;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class ImagesJ3xModel extends ImagesModel
{

    public function AssignSlideshowUrl($gallery)
    {

        try {

            $gallery->UrlGallery = ''; // fall back

            $gallery->UrlSlideshow = Route::_('index.php?option=com_rsgallery2'
                . '&view=slideshowJ3x&gid=' . $gallery->id
                ,true,0,true);

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GallerysModel: AssignSlideshowUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }



}
