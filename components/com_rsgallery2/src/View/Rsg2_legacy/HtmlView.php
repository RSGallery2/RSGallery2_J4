<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\Rsg2_legacy;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Rsgallery2\Component\Rsgallery2\Site\Model\Rsg2_legacyModel;

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

    /**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{

        /**
         *
         *      folders should be named galleries root J3x
         *         -> Rsg2_legacy is wrong
         *
        */

        //--- root galleries --------------------------------------------------

        $input  = Factory::getApplication()->input;
        $this->galleryId = $input->get('gid', 0, 'INT');

        $state = $this->state = $this->get('State');
        $params = $this->params = $state->get('params');

        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->user       = Factory::getUser();

        $this->isDebugSite = $params->get('isDebugSite');
        $this->isDevelopSite = $params->get('isDevelop');

//        if (count($errors = $this->get('Errors')))
//        {
//            throw new GenericDataException(implode("\n", $errors), 500);
//        }

        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;

		//--- random images --------------------------------------------------

		// ToDo: seperate limits for ...
		$limit = 4;


		// $this->randomImages = $this->get('RandomImages', $limit);
		$this->randomImages = Rsg2_legacyModel::RandomImages($limit);

		//--- latest images --------------------------------------------------

		// ToDo: seperate limits for ...
		$limit = 4;

		// $this->latestImages = $this->get('LatestImages', $limit);
		$this->latestImages = Rsg2_legacyModel::latestImages($limit);





		return parent::display($tpl);
	}
}
