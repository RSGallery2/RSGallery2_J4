<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\Galleriesj3x;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;
use Rsgallery2\Component\Rsgallery2\Site\Model\Galleriesj3xModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * HTML Rsgallery2 View class for the Rsgallery2 component
 *
     * @since      5.1.0
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
     * @since  5.1.0
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
     * @var mixed|null
     * @since version
     */
    protected mixed $parentGallery;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null): void
    {
        //--- root galleries view (j3x standard) --------------------------------------------------

        $app = Factory::getApplication();

        $input = $app->getInput();

        $this->galleryId = $input->get('id', 0, 'INT');

        /** @var Galleriesj3xModel $model */
        $model = $this->getModel();

        $state =
        $this->state = $model->getState();

        $params =
        $this->params = $this->state->get('params');

        // Limit number of galleries shown by menu parameter
        $limit = $params->get('max_thumbs_in_root_galleries_view_j3x', 5, 'INT');
        $state->set('list.limit', $limit);

        //$this->pagination = $model->getPagination();
        $this->pagination = null;

        $user =
        $this->user = $app->getIdentity();

        // Activate isDebugSite/isDevelopSite manual by URL
        $this->isDebugSite   = $this->params->get('isDebugSite') || $input->getBool('isDebugSite');
        $this->isDevelopSite = $this->params->get('isDevelop') || $input->getBool('isDevelop');

        // parent + sub galleries ?
        $this->items = $model->getItems();

        // parent gallery
//        $this->parentGallery = $model->getParentGallery();
        $this->parentGallery = null;

//      // Merge (overwrite) config parameter with menu parameter
//      $menuParams = $this->get('Rsg2MenuParams');
//      // wrong: $this->params = $menuParams->merge($this->params);
//      $this->params->merge($menuParams);

        if (count($errors = $model->getErrors())) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

// on develop show open tasks if existing
        if (!empty($this->isDevelopSite)) {
            echo '<span style="color:red">'
                . 'Tasks: galleriesJ3x view<br>'
                //  . '* <br>'
                //  . '* <br>'
                //  . '* <br>'
                //  . '* <br>'
                //  . '* <br>'
                . '</span><br><br>';
        }



        parent::display($tpl);
    }
}
