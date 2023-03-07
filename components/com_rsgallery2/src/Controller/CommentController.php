<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

/**
 * Foo controller.
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class RSGallery2ControllerComment extends BaseController
{

	// saveComment Below / ToDO: delete comment


	public function addComment()
	{
		$msgType = 'notice';
    	$msg     = 'Add coment: ';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$input = JFactory::getApplication()->input;
		$imageId = $input->get('id', 0, 'INT');

		// http://127.0.0.1/Joomla3x/index.php?option=com_rsgallery2&view=gallery&gid=42&advancedSef=1&startShowSingleImage=1&Itemid=218
		//$link = 'index.php?option=com_rsgallery2'; // &startShowSingleImage=1&Itemid=218
		$link = 'index.php?option=com_rsgallery2&page=inline&id=' . $imageId.'&tab=comment';
		

		// Access check
		$galleryId = $input->get('gid', 0, 'INT');
		//$canComment = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
		$canComment = JFactory::getUser()->authorise('rsgallery2.comment', 'com_rsgallery2.gallery.' . $galleryId);
		// ToDO: remove
		//$canComment = true;

		if ( ! $canComment)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR') . " " . JText::_('COM_RSGALLERY2_COMMENTING_IS_DISABLED');
			$msgType = 'Warning: ';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{

			// Check user ID
			$user    = JFactory::getUser();
			$user_id = (int) $user->id;

//			??? if not / if needed ??
			if (empty($user_id))
			{
				// ToDo: Message Login to comment
				$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR') . " " . JText::_('COM_RSGALLERY2_YOU_MUST_LOGIN_TO_COMMENT' . ' (B)');
				$msgType = 'Warning: ';
				// replace newlines with html line breaks.
				$msg = nl2br ($msg);
			}
			else
			{
				try
				{
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


					$comment = new \stdClass;

					$comment->user_id   = $user_id;
					$comment->user_name = $commentUserName;
					$comment->user_ip   = $input->server->get('REMOTE_ADDR', '', '');
					//$comment->parent_id  = ;
					$comment->item_id    =  $imageId; //
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
					if ($isSaved)
					{
						$commentModel->SetUserHasCommented($imageId);
					}

					// limitstart=3 ....
					// http://127.0.0.1/joomla3x/index.php?option=com_rsgallery2&view=gallery&gid=2&advancedSef=1&startShowSingleImage=1&Itemid=145&XDEBUG_SESSION_START=12302&limitstart=3
					//$link = 'index.php?option=com_rsgallery2&view=gallery&gid=' . $galleryId . '&id=' . $imageId
					//	. '&startShowSingleImage=1' . '&rating=' . $userRating . '&limitstart=' . $limitStart;
				}
				catch
					(RuntimeException $e)
				{
					$OutTxt = '';
					$OutTxt .= 'Error executing addComment: "' . '<br>';
					$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

					$app = JFactory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');
				}
			} // user ID
		}

		$this->setRedirect($link, $msg, $msgType);
	}


	// After editing
	public function saveComment()
	{
		$msgType = 'notice';
		$msg     = 'Save coment: ';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Align like above

		// http://127.0.0.1/Joomla3x/index.php?option=com_rsgallery2&view=gallery&gid=42&advancedSef=1&startShowSingleImage=1&Itemid=218
		$link = 'index.php?option=com_rsgallery2'; // &startShowSingleImage=1&Itemid=218

		$input = JFactory::getApplication()->input;

		$galleryId = $input->get('gid', 0, 'INT');
		// ToDo: check for appearance
		$limitStart = $input->get('paginationImgIdx', 0, 'INT');
		$userRating = $input->get('rating', 0, 'INT');

		// Access check
		$canComment = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
		//$canComment = true;

		if ( ! $canComment)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			try
			{
				echo "<br><br><br>*CommentSingleImage<br><br><br>";

				$input = JFactory::getApplication()->input;

				$galleryId = $input->get('gid', 0, 'INT');
				$imageId = $input->get('id', 0, 'INT');

				/**
				$userRating = $input->get('rating', 0, 'INT');
				// Show same image -> pagination limitstart
				$limitStart = $input->get('paginationImgIdx', 0, 'INT');
				/**/

				$comment = '';

				$commentModel = $this->getModel('comments');
				$isSaved = $commentModel->saveComment ($imageId, $comment);
				// $limitStart = 4;

				// Set cookie
				if ($isSaved)
				{
					$commentModel->SetUserHasCommented($imageId);
				}

//				limitstart=3 ....
// http://127.0.0.1/joomla3x/index.php?option=com_rsgallery2&view=gallery&gid=2&advancedSef=1&startShowSingleImage=1&Itemid=145&XDEBUG_SESSION_START=12302&limitstart=3
				$link = 'index.php?option=com_rsgallery2&view=gallery&gid=' . $galleryId . '&id=' . $imageId
					. '&startShowSingleImage=1' . '&rating=' . $userRating . '&limitstart=' . $limitStart;
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing saveComment: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}
		}

		$this->setRedirect($link, $msg, $msgType);
	}


}
