<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\RootgalleriesJ3x;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Rsgallery2\Component\Rsgallery2\Site\Model\ImagesModel;
use Rsgallery2\Component\Rsgallery2\Site\Model\GalleryJ3xModel;

//use Rsgallery2\Component\Rsgallery2\Site\Model\RootgalleriesJ3xModel;

/**
 * HTML Rsgallery2 View class for the Rsgallery2 component
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The model state
     *
     * @var    \JObject
     * @since  3.1
     */
    protected $state;

    /**
     * The list of tags
     *
     * @var    array|false
     * @since  3.1
     */
    protected $items;

    /**
     * The pagination object
     *
     * @var    \Joomla\CMS\Pagination\Pagination
     * @since  3.1
     */
    protected $pagination;

    /**
     * The page parameters
     *
     * @var    \Joomla\Registry\Registry|null
     * @since  __BUMP_VERSION__
     */
    protected $params = null;

    /**
     * The page class suffix
     *
     * @var    string
     * @since  4.0.0
     */
    protected $pageclass_sfx = '';

    /**
     * The logged in user
     *
     * @var    \JUser|null
     * @since  4.0.0
     */
    protected $user = null;

    protected $menuParams; // (object)[];
    protected $galleryId; // (object)[];

    /**
     * Execute and display a template script.
     *
     * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {

        //--- root galleries --------------------------------------------------

        $input = Factory::getApplication()->input;

        // ToDo: use for limit  $this->menuParams->galleries_count in
        $state =
        $this->state      = $this->get('State');
        // Galleries with parend ID = 0
        $this->items = $this->get('Items');

        $params =
        $this->params = $this->state->get('params');

        $this->pagination = $this->get('Pagination');
        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;
	    // ToDo: Why is this necessary ?
//		$this->pagination->setTotal (count($this->items));
        $this->user = Factory::getUser();

        $this->isDebugSite = boolval($this->params->get('isDebugSite', $input->getBool('isDebugSite')));
        $this->isDevelopSite = boolval($this->params->get('isDevelop', $input->getBool('isDevelop')));

        // Merge (overwrite) menu parameter with item/config parameter

        $menuParams =
        $this->menuParams = $this->get('Rsg2MenuParams');

        // overwrite with param items
        $menuParams->merge($this->params);
        $this->params = $menuParams;

        if (count($errors = $this->get('Errors')))
        {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $model = $this->getModel();

        //--- random images --------------------------------------------------

        //$this->randomImages = $model->randomImages($this->menuParams->random_count);
        $this->randomImages = $model->randomImages($menuParams->get('random_count'));
//        if (!empty($this->randomImages)) {
//            GalleryJ3xModel::AddLayoutData($this->randomImages);
//        }
//        /**/

        //--- latest images --------------------------------------------------

        $this->latestImages = $model->latestImages($menuParams->get('latest_count'));
//        /**/
//        if (!empty($this->latestImages)) {
//            GalleryJ3xModel::AddLayoutData($this->latestImages);
//        }
//        /**/


        return parent::display($tpl);
    }

}

























