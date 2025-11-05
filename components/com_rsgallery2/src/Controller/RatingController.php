<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2022-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Session\Session;
use Joomla\Input\Input;
use Joomla\Registry\Registry;

/**
 * Foo controller.
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class RatingController extends BaseController
{
    /**
     * The extension for which the galleries apply.
     *
     * @var    string
     * @since  5.1.0     */
    protected $extension;

    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input               $input    Input
     *
     * @since   5.1.0     * @see    JControllerLegacy
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        if (empty($this->extension)) {
            $this->extension = $this->input->get('extension', 'com_rsgallery2');
        }
    }


	/**
	 *
	 *
	 * @throws Exception
	 * @since  5.1.0
	 */
    public function rateSingleImage()
    {
        $msgType = 'notice';
        $msg     = 'Rate Single Image: ';

        // Check for request forgeries.
        $this->checkToken();

        $input     = Factory::getApplication()->getInput();
        $imageId   = $input->get('iid', 0, 'INT');
        $galleryId = $input->get('iid', 0, 'INT');

        // http://127.0.0.1/Joomla3x/index.php?option=com_rsgallery2&view=gallery&id=42&advancedSef=1&startShowSingleImage=1&Itemid=218
        //$link = 'index.php?option=com_rsgallery2'; // &startShowSingleImage=1&Itemid=218
        $link = 'index.php?option=com_rsgallery2&view=slidepagej3x&id=' . $galleryId . '&img_id=' . $imageId . '&tab=vote';

        // Access check
        $canVote = $this->app->getIdentity()->authorise('core.admin', 'com_rsgallery2');
        if (!$canVote) {
            $msg = $msg . Text::_('JERROR_ALERTNOAUTHOR') . " " . Text::_('COM_RSGALLERY2_VOTING_IS_DISABLED');

            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br($msg);
        } else {
            try {
                echo "<br><br><br>*RateSingleImage<br><br><br>";

                //if ($vote->alreadyVoted((int) $id))
				//{
				//}

                $galleryId  = $input->get('id', 0, 'INT');
                $userRating = $input->get('rating', 0, 'INT');
                // Show same image -> pagination limitstart
                $limitStart = $input->get('paginationImgIdx', 0, 'INT');


                $ratingModel = $this->getModel('rating');
                $isRated     = $ratingModel->doRating($imageId, $userRating);
                // $limitStart = 4;

                // Set cookie
                if (!$isRated) {
                    $ratingModel->SetUserHasRated($imageId, $userRating);
                }

                //	limitstart=3 ....
                // http://127.0.0.1/joomla3x/index.php?option=com_rsgallery2&view=gallery&id=2&advancedSef=1&startShowSingleImage=1&Itemid=145&XDEBUG_SESSION_START=12302&limitstart=3
                // $link = 'index.php?option=com_rsgallery2&view=gallery&id=' . $galleryId . '&id=' . $imageId
                //	. '&startShowSingleImage=1' . '&rating=' . $userRating . '&limitstart=' . $limitStart;
                // $link = 'index.php?option=com_rsgallery2&page=inline'; // &id=' . $imageId) .'" id="rsgVoteForm">';
                $link = $link . '&id=' . $imageId;
                //	. '&startShowSingleImage=1' . '&rating=' . $userRating . '&limitstart=' . $limitStart;
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing rateSingleImage: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $this->setRedirect($link, $msg, $msgType);
    }


}
