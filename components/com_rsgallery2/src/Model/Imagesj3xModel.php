<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Registry\Registry;

//use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class imagesj3xModel extends ImagesModel
{

    public function assignSlideshowUrl($gallery)
    {
        try {
            // $gallery->UrlSlideshow = ''; // fall back

            $gallery->UrlSlideshow = Route::_('index.php?option=com_rsgallery2'
                . '&view=slideshowj3x&id=' . $gallery->id
                ,true,0,true);

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GallerysModel: assignSlideshowUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }


}
