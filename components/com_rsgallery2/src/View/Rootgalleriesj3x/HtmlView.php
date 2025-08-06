<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\Rootgalleriesj3x;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\Registry\Registry;
use \Joomla\CMS\User\User;

//use Rsgallery2\Component\Rsgallery2\Site\Model\Rootgalleriesj3xModel;

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
     * @var    \stdClass
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
     * @var    Pagination
     * @since  3.1
     */
    protected $pagination;

    /**
     * The page parameters
     *
     * @var    Registry|null
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
     * @var    User|null
     * @since  4.0.0
     */
    protected $user = null;

    //protected $menuParams; // (object)[];
    protected $galleryId; // (object)[];

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null) : void
    {
        //--- root galleries (j3x standard --------------------------------------------------

        $app = Factory::getApplication();

        $input = $app->input;

        $this->galleryId = $input->get('id', 0, 'INT');

        // ToDo: use for limit  $this->menuParams->galleries_count in
        $state            =
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->user       = // $user = Factory::getContainer()->get(UserFactoryInterface::class);
        $user = $app->getIdentity();

        $test   = $app->getParams();
        $params =
        $this->params = $this->state->get('params');

//	    // ToDo: may not be necessary but display
//        $menuParams =
//        $this->menuParams = $this->get('Rsg2MenuParams');
//
//	    // Merge (overwrite) config parameter with menu parameter
//	    // wrong: $this->params = $menuParams->merge($this->params);
//	    $params = $this->params->merge($menuParams);

        //$this->isDebugSite   = boolval($this->params->get('isDebugSite', $input->getBool('isDebugSite')));
        $this->isDebugSite   = $this->params->get('isDebugSite') || $input->getBool('isDebugSite');
        //$this->isDevelopSite = boolval($this->params->get('isDevelop', $input->getBool('isDevelop')));
        $this->isDevelopSite = $this->params->get('isDevelop') || $input->getBool('isDevelop');

        // J3x old parameter for limit
        $limit = $input->get('max_thumbs_in_root_galleries_view_j3x', 5, 'INT');
        $state->set('list.limit', $limit);

        // Galleries with parent ID = 0 / ? gallery id ?
        $this->items = $this->get('Items');

        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;


        $model = $this->getModel();

        //--- random images --------------------------------------------------

        //$this->randomImages = $model->randomImages($this->menuParams->random_count);
        $this->randomImages = $model->randomImages($params->get('random_count'));
//        if (!empty($this->randomImages)) {
//            Galleryj3xModel::AddLayoutData($this->randomImages);
//        }
//        /**/

        //--- latest images --------------------------------------------------

        $this->latestImages = $model->latestImages($params->get('latest_count'));
//        /**/
//        if (!empty($this->latestImages)) {
//            Galleryj3xModel::AddLayoutData($this->latestImages);
//        }
//        /**/

// on develop show open tasks if existing
        if (!empty ($this->isDevelopSite)) {
            echo '<span style="color:red">'
                . 'Tasks: rootgalleriesJ3x view<br>'
                . '* !!! Menu intro text -> Save gets following lines double !!!<br>'
                . '* !!! Root gallery shows gallery list as text a) needed ? b) stop after 20 !!!<br>'
                . '* !!! latest images URLS wrong -> shows wrong image ? should show slide image of gallery ? !!!<br>'
                . '* !!! root images URLS wrong -> shows wrong image ? should show slide image of gallery ? !!!<br>'
                . '* User limit selection box -> layout ? Nbr of galleries  -> yes no ?  <br>'
                . '* Format of date is already in database -> improve ... <br>'
                . '* Events in general<br>'
                . '* User count of galleries displayed not working: 0, 1,2,3<br>'
                //	. '* <br>'
                //	. '* <br>'
                //	. '* <br>'
                //	. '* <br>'
                //	. '* <br>'
                . '</span><br><br>';
        }

        parent::display($tpl);
    }

}

























