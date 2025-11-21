<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\site\Controller;

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
class CommentController extends BaseController
{
    protected $extension;

    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input                $input    Input
     *
     * @since   5.1.0
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        if (empty($this->extension)) {
            $this->extension = $this->input->get('extension', 'com_rsgallery2');
        }
    }

    // saveComment Below / ToDO: delete comment


    /**
     *
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function addComment()
    {
        $msgType = 'notice';
        $msg     = 'Add coment: ';

        // Check for request forgeries.
        $this->checkToken();

        $input   = Factory::getApplication()->getInput();
        $imageId = $input->get('id', 0, 'INT');

        // http://127.0.0.1/Joomla3x/index.php?option=com_rsgallery2&view=gallery&id=42&advancedSef=1&startShowSingleImage=1&Itemid=218
        //$link = 'index.php?option=com_rsgallery2'; // &startShowSingleImage=1&Itemid=218
        $link = 'index.php?option=com_rsgallery2&page=inline&id=' . $imageId . '&tab=comment';


        // Access check
        $galleryId = $input->get('id', 0, 'INT');
        //$canComment = $this->app->getIdentity()->authorise('core.admin', 'com_rsgallery2');
        $canComment = $this->app->getIdentity()->authorise('rsgallery2.comment', 'com_rsgallery2.gallery.' . $galleryId);
        // ToDO: remove
        //$canComment = true;

        if (!$canComment) {
            $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR') . " " . Text::_('COM_RSGALLERY2_COMMENTING_IS_DISABLED');
            $msgType = 'Warning: ';
            // replace newlines with html line breaks.
            $msg = nl2br($msg);
        } else {
            // Check user ID
            $user    = $this->app->getIdentity();
            $user_id = (int)$user->id;

//          ??? if not / if needed ??
            if (empty($user_id)) {
                // ToDo: Message Login to comment
                $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR') . " " . Text::_('COM_RSGALLERY2_YOU_MUST_LOGIN_TO_COMMENT' . ' (B)');
                $msgType = 'Warning: ';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
            } else {
                try {
                    echo "<br><br><br>*CommentSingleImage<br><br><br>";

                    // ToDo: check for appearance
                    $limitStart = $input->get('paginationImgIdx', 0, 'INT');

                    $imageId = $input->get('id', 0, 'INT');
                    $item_id = $input->get('item_id', 0, 'INT');

                    /**
                     * $userRating = $input->get('rating', 0, 'INT');
                     * // Show same image -> pagination limitstart
                     * $limitStart = $input->get('paginationImgIdx', 0, 'INT');
                     * /**/


                    $commentUserName = $input->get('commentUserName', 0, 'string');
                    $commentTitle    = $input->get('commentTitle', 0, 'string');
                    $commentText     = $input->get('commentText', 0, 'string');

                    $dateTime = date('Y-m-d H:i:s');


                    $comment = new \stdClass();

                    $comment->user_id   = $user_id;
                    $comment->user_name = $commentUserName;
                    $comment->user_ip   = $input->server->get('REMOTE_ADDR', '', '');
                    //$comment->parent_id  = ;
                    $comment->item_id    = $imageId; //
                    $comment->item_table = 'com_rsgallery2';
                    $comment->datetime   = $dateTime;
                    $comment->subject    = $commentTitle;
                    $comment->comment    = $commentText;
                    //$comment->published  = ;
                    //$comment->ordering   = ;
                    //$comment->params     = ;
                    //$comment->hits       = ;

// ToDo: captcha ? ...

// check cookie comment once

                    $commentModel = $this->getModel('comments');
                    $isSaved      = $commentModel->addComment($imageId, $comment);

                    // Set cookie
                    if ($isSaved) {
                        $commentModel->SetUserHasCommented($imageId);
                    }

                    // limitstart=3 ....
                    // http://127.0.0.1/joomla3x/index.php?option=com_rsgallery2&view=gallery&id=2&advancedSef=1&startShowSingleImage=1&Itemid=145&XDEBUG_SESSION_START=12302&limitstart=3
                    //$link = 'index.php?option=com_rsgallery2&view=gallery&id=' . $galleryId . '&id=' . $imageId
                    //  . '&startShowSingleImage=1' . '&rating=' . $userRating . '&limitstart=' . $limitStart;
                } catch (\RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error executing addComment: "' . '<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }
            } // user ID
        }

        $this->setRedirect($link, $msg, $msgType);
    }


    // After editing

    /**
     * @throws \Exception
     * @since  5.1.0
     */
    public function saveComment()
    {
        $msgType = 'notice';
        $msg     = 'Save coment: ';

        // Check for request forgeries.
        $this->checkToken();

        // Align like above

        // http://127.0.0.1/Joomla3x/index.php?option=com_rsgallery2&view=gallery&id=42&advancedSef=1&startShowSingleImage=1&Itemid=218
        $link = 'index.php?option=com_rsgallery2'; // &startShowSingleImage=1&Itemid=218

        $input = Factory::getApplication()->getInput();

        $galleryId = $input->get('id', 0, 'INT');
        // ToDo: check for appearance
        $limitStart = $input->get('paginationImgIdx', 0, 'INT');
        $userRating = $input->get('rating', 0, 'INT');

        // Access check
        $canComment = $this->app->getIdentity()->authorise('core.admin', 'com_rsgallery2');
        //$canComment = true;

        if (!$canComment) {
            $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br($msg);
        } else {
            try {
                echo "<br><br><br>*CommentSingleImage<br><br><br>";

                $input = Factory::getApplication()->getInput();

                $galleryId = $input->get('id', 0, 'INT');
                $imageId   = $input->get('id', 0, 'INT');

                /**
                $userRating = $input->get('rating', 0, 'INT');
                // Show same image -> pagination limitstart
                $limitStart = $input->get('paginationImgIdx', 0, 'INT');
                /**/

                $comment = '';

                $commentModel = $this->getModel('comments');
                $isSaved      = $commentModel->saveComment($imageId, $comment);
                // $limitStart = 4;

                // Set cookie
                if ($isSaved) {
                    $commentModel->SetUserHasCommented($imageId);
                }

//              limitstart=3 ....
// http://127.0.0.1/joomla3x/index.php?option=com_rsgallery2&view=gallery&id=2&advancedSef=1&startShowSingleImage=1&Itemid=145&XDEBUG_SESSION_START=12302&limitstart=3
                $link = 'index.php?option=com_rsgallery2&view=gallery&id=' . $galleryId . '&id=' . $imageId
                    . '&startShowSingleImage=1' . '&rating=' . $userRating . '&limitstart=' . $limitStart;
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing saveComment: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $this->setRedirect($link, $msg, $msgType);
    }
}
