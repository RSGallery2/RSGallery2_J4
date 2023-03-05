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

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class SlidePageJ3XModel extends ImagesJ3xModel
{

    // for pagination override
    protected function populateState($ordering = 'ordering', $direction = 'ASC')
    {
//        $input   = Factory::getApplication()->input;
//        $imageId = $input->get('img_id', 0, 'INT');

        parent::populateState($ordering, $direction);

//        if ($imageId > 0) {
////            $imageIdx = $this->imageIdxInList ($imageId, $this->items);
////            $this->state->set('list.limitstart', 9);
//            $this->setState('id', $imageId);
//
//        }


//        $input  = Factory::getApplication()->input;
//        $galleryId = $input->get('gid', 0, 'INT');
//
//        // If gallery ID is given
//        if ($galleryId) {
//            // ToDo:         if ($gallery_id = ) instead of below
//            // wrong: $this->setState('rsgallery2.id', $app->input->getInt('id'));
//            $this->setState('filter.gallery_id', $galleryId);
//            //filter.gallery_id
//        }

    }


    /**
     * Method to get a database query to list images.
     *
     * @return  \DatabaseQuery object.
     *
     * @since __BUMP_VERSION__
     */
    protected function getListQuery()
    {

        $query = parent::getListQuery();

        $input  = Factory::getApplication()->input;
        $galleryId = $input->get('gid', 0, 'INT');

        // If gallery ID is given
        if ($galleryId) {
            $query->where('a.gallery_id = ' . (int)$galleryId);
        }

        return $query;
    }
}

