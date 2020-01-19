<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

namespace Joomla\Component\Rsgallery2\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Response\JsonResponse;
use Joomla\Input\Input;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;

use Joomla\Component\Rsgallery2\Administrator\Model\ImageModel;


/**
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the Log class.
//	jimport('joomla.log.log');

	// identify active file
	Log::add('==> ctrl.config.php ');
}
/**/

//class UploadController extends AdminController
class UploadController extends FormController
{
	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CMSApplication       $app      The JApplication for the dispatcher
	 * @param   \JInput              $input    Input
	 *
	 * @since   1.0
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);

	}

	/**
	 * Proxy for getModel.
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
	 */
    public function getModel($name = 'Upload', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
	/**/



	/**
	 * The database entry for the image will be created here
	 * It is called for each image for preserving the correct
	 * ordering before uploading the images
	 * Reason: The parallel uploaded images may appear unordered
	 *
	 * @since 4.3.0
	 * @throws Exception
	 */
	function uploadAjaxReserveDbImageId ()
	{
		global $rsgConfig, $Rsg2DebugActive;

		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$msg = 'uploadAjaxReserveImageInDB';

		$app = Factory::getApplication();

		// ToDo: security check

		try {
			if ($Rsg2DebugActive) {
				// identify active file
				Log::add('==> uploadAjaxInsertInDB');
			}

			// do check token
			if ( ! Session::checkToken()) {
				$errMsg = Text::_('JINVALID_TOKEN') . " (02)";
				$hasError = 1;
				echo new JsonResponse($msg, $errMsg, $hasError);
				$app->close();
			}

			$input = Factory::getApplication()->input;
			//$oFile = $input->files->get('upload_file', array(), 'raw');
			//$uploadFileName    = File::makeSafe($oFile['name']);

			/**
			 * Form data:
			 *
			data.append('upload_file', nextFile.file.name);
			data.append('upload_size', nextFile.file.size.toString());
			data.append('upload_type', nextFile.file.type);
			data.append(Token, '1');
			data.append('gallery_id', nextFile.galleryId);
			/**/

			$test = 0; // 0: normal, 1:error, 2: warning ....

			if ($test)
			{
				$result = "Resulting data (simulated)";
				switch ($test)
				{
					case 1:
						echo new JsonResponse($result, Text::_('COM_COMPONENT_MY_TASK_ERROR'), true);
						break;

					case 2:
						echo new JsonResponse($result, 'Main response message');
						break;

					case 3:
						$app->enqueueMessage('This part has error 1');
						$app->enqueueMessage('This part has error 2');
						$app->enqueueMessage("Enqueued notice 1", "notice");
						$app->enqueueMessage("Enqueued notice 2", "notice");
						$app->enqueueMessage('Here was a small warning 1', 'warning');
						$app->enqueueMessage('Here was a small warning 2', 'warning');
						$app->enqueueMessage('Here was a small error 1', 'error');
						$app->enqueueMessage('Here was a small error 2', 'error');
						echo new JsonResponse($result, Text::_('!!! Response message with error set !!!'), true);
						break;

					case 4:
						$app->enqueueMessage('This part was successful 1');
						$app->enqueueMessage('This part was successful 2');
						$app->enqueueMessage("Enqueued notice 1", "notice");
						$app->enqueueMessage("Enqueued notice 2", "notice");
						$app->enqueueMessage('Here was a small warning 1', 'warning');
						$app->enqueueMessage('Here was a small warning 2', 'warning');
						$app->enqueueMessage('Here was a small error 1', 'error');
						$app->enqueueMessage('Here was a small error 2', 'error');
						echo new JsonResponse($result, 'Response message with !!! no !!! error set');
						break;
					case 5:
						$app->enqueueMessage('This part was successful 1');
						$app->enqueueMessage('This part was successful 2');
						$app->enqueueMessage("Enqueued notice 1", "notice");
						$app->enqueueMessage("Enqueued notice 2", "notice");
						$app->enqueueMessage('Here was a small warning 1', 'warning');
						$app->enqueueMessage('Here was a small warning 2', 'warning');
						$app->enqueueMessage('Here was a small error 1', 'error');
						$app->enqueueMessage('Here was a small error 2', 'error');

						throw new \Exception('Attention: raised exception ');
//						throw new Notallowed(Text::_('Not allowed thrown'), 403);


						echo new JsonResponse($result, 'Response message with !!! no !!! error set');
						break;
				}

				$app->close();
			}


			//--- file name  --------------------------------------------

			$uploadFileName = $input->get('upload_file_name', '', 'string');
			$fileName = File::makeSafe($uploadFileName);

// ==>			joomla replace spaces in filenames
// ==>			'file_name' => str_replace(" ", "", $file_name);
			$baseName = basename($fileName);

			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('$uploadFileName: "' . $uploadFileName . '"');
				Log::add('$fileName: "' . $fileName . '"');
				Log::add('$baseName: "' . $baseName . '"');
			}

			// ToDo: Check session id
			// $session_id      = Factory::getSession();

			$ajaxImgDbObject['uploadFileName'] = $uploadFileName;
			// some dummy data for error messages
			$ajaxImgDbObject['imageId']  = -1;
			$ajaxImgDbObject['baseName'] = $baseName;
			$ajaxImgDbObject['dstFileName'] = $fileName;

			//--- gallery ID --------------------------------------------

			$galleryId = $input->get('gallery_id', 0, 'INT');
			// wrong id ? ToDo: test is number ...
			if ($galleryId < 1)
			{
				//$app->enqueueMessage(Text::_('COM_RSGALLERY2_INVALID_GALLERY_ID'), 'error');
				//echo new JsonResponse;
				$msg .= ': Invalid gallery ID at drag and drop upload';

				if ($Rsg2DebugActive)
				{
					Log::add($msg);
				}

				echo new JsonResponse($ajaxImgDbObject, $msg, true);

				$app->close();
				return;
			}

			//--- Check 4 allowed image type ---------------------------------

			// May be checked when opening file ...

			//----------------------------------------------------
			// Create image data in db
			//----------------------------------------------------

			/** start create ... */
			$modelDb = $this->getModel('Image');

			//--- Create Destination file name -----------------------

			// ToDo: use sub folder for each gallery and check within gallery
			// Each filename is only allowed once so create a new one if file already exist
			$useFileName = $modelDb->generateNewImageName($baseName, $galleryId);
			$ajaxImgDbObject['dstFileName'] = $useFileName;

			/**/
			//--- create image data in DB --------------------------------

			$title = $baseName;
			$description = '';

			$imageId = $modelDb->createImageDbItem($useFileName, '', $galleryId, $description);
			if (empty($imageId))
			{
				// actual give an error
				//$msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
				$msg     .= 'uploadAjaxReserveImageInDB: Create DB item for "' . $baseName . '"->"' . $useFileName . '" failed. Use maintenance -> Consolidate image database to check it ';

				if ($Rsg2DebugActive)
				{
					Log::add($msg);
				}

				// replace newlines with html line breaks.
				//str_replace('\n', '<br>', $msg);
				echo new JsonResponse($ajaxImgDbObject, $msg, true);

				$app->close();
				return;
			}

			if ($Rsg2DebugActive)
			{
				Log::add('<== uploadAjax: After createImageDbItem: ' . $imageId );
			}

			// $this->ajaxDummyAnswerOK (); return; // 05

			$ajaxImgDbObject['imageId']  = $imageId;
			$isCreated = $imageId > 0;

			//----------------------------------------------------
			// for debug purposes fetch image order
			//----------------------------------------------------

			$imageOrder = $this->imageOrderFromId ($imageId);
			$ajaxImgDbObject['order']  = $imageOrder;

			//----------------------------------------------------
			// return result
			//----------------------------------------------------

			if ($Rsg2DebugActive) {
				Log::add('    $ajaxImgDbObject: ' . json_encode($ajaxImgDbObject));
				Log::add('    $msg: "' . $msg . '"');
				Log::add('    !$isCreated (error):     ' . ((!$isCreated) ? 'true' : 'false'));
			}
			/**/

			/**/
			// simulate
			$isCreated = true;
			$imageId = time () % 3600;
			$ajaxImgDbObject['imageId']  = $imageId;
			/**/

			echo new JsonResponse($ajaxImgDbObject, $msg, !$isCreated, true);
			//echo new JsonResponse("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

			if ($Rsg2DebugActive) {
				Log::add('<== Exit uploadAjaxSingleFile');
			}

		} catch (Exception $e) {
			echo new JsonResponse($e);
		}

		$app->close();
	}


	/**
	 * The dropped file will be uploaded. The dependent files
	 * display and thumb will also be created
	 * The gallery id was created before and is read from the
	 * ajax parameters
	 *
	 * @since 4.3
	 */
	function uploadAjaxSingleFile()
	{
		global $rsgConfig, $Rsg2DebugActive;

		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$IsMoved = false;
		$msg = 'uploadAjaxSingleFile';

		$app = Factory::getApplication();

		try {
			if ($Rsg2DebugActive) {
				// identify active file
				Log::add('==> uploadAjaxSingleFile');
			}

			// do check token
			if ( ! Session::checkToken()) {
				$errMsg = Text::_('JINVALID_TOKEN') . " (01)";
				$hasError = 1;
				echo new JsonResponse($msg, $errMsg, $hasError);
				$app->close();
			}

			$input = Factory::getApplication()->input;
			$oFile = $input->files->get('upload_file', array(), 'raw');

			$uploadPathFileName = $oFile['tmp_name'];
			$fileType    = $oFile['type'];
			$fileError   = $oFile['error'];
			$fileSize    = $oFile['size'];

			// Changed name on existing file name
			$safeFileName = File::makeSafe($oFile['name']);
			$uploadFileName = $input->get('dstFileName', '', 'string');

			// for next upload tell where to start
			//$rsgConfig->setLastUpdateType('upload_drag_and_drop');
			// configDb::write2Config ('last_update_type', 'upload_drag_and_drop', $rsgConfig);

			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('$uploadPathFileName: "' . $uploadPathFileName . '"');
				Log::add('$safeFileName: "' . $safeFileName . '"');
				Log::add('$uploadFileName: "' . $uploadFileName . '"');
				Log::add('$fileType: "' . $fileType . '"');
				Log::add('$fileError: "' . $fileError . '"');
				Log::add('$fileSize: "' . $fileSize . '"');
			}

			// ToDo: Check session id
			// $session_id      = Factory::getSession();

			//--- check user ID --------------------------------------------

			$ajaxImgObject['file'] = $uploadFileName; // $dstFile;
			// some dummy data for error messages
			$ajaxImgObject['imageId']  = -1;
			$ajaxImgObject['fileUrl']  = '';
			$ajaxImgObject['safeFileName'] = $safeFileName;

			//--- gallery ID --------------------------------------------

			$galleryId = $input->get('gallery_id', 0, 'INT');
			// wrong id ?
			if ($galleryId < 1)
			{
				//$app->enqueueMessage(Text::_('COM_RSGALLERY2_INVALID_GALLERY_ID'), 'error');
				//echo new JsonResponse;
				echo new JsonResponse($ajaxImgObject, 'Invalid gallery ID at drag and drop upload', true);

				$app->close();
				return;
			}

			//--- image ID --------------------------------------------

			$imageId = $input->get('imageId', 0, 'INT');
			// wrong id ? ToDo: test is number ...
			if (!$imageId)
			{
				//$app->enqueueMessage(Text::_('COM_RSGALLERY2_INVALID_GALLERY_ID'), 'error');
				//echo new JsonResponse;
				echo new JsonResponse($ajaxImgObject, 'Invalid image ID at drag and drop upload', true);

				$app->close();
				return;
			}

			$ajaxImgObject['imageId']  = $imageId;

			$singleFileName = $uploadFileName;

			//----------------------------------------------------
			// for debug purposes fetch image order
			//----------------------------------------------------

//			$imageOrder = $this->imageOrderFromId ($imageId);
//			$ajaxImgObject['order']  = $imageOrder;

			//----------------------------------------------------
			// Move file and create display, thumbs and watermarked images
			//----------------------------------------------------

			/* simulate $modelFile->MoveImageAndCreateRSG2Images  d:\xampp\htdocs\joomla4x\images\rsgallery2\ */
			$dstFileName = JPATH_ROOT . '/images/rsgallery2'  . '/'  .  $singleFileName;
			$isCreated = File::move($uploadPathFileName, $dstFileName);
			$urlThumbFile = Uri::root() . 'images/rsgallery2'  . '/'  .  $singleFileName;
			$msg = $isCreated ? "Copied " : 'Not copied';
			/**/

			/**
			$modelFile = $this->getModel('imageFile');
			list($isCreated, $urlThumbFile, $msg) = $modelFile->MoveImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId);
			/**/
			if (!$isCreated)
			{
				// ToDo: remove $imageId fom image database
				if ($Rsg2DebugActive)
				{
					Log::add('MoveImageAndCreateRSG2Images failed: ' . $uploadFileName . ', ' . $singleFileName);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);
				$app->close();
				return;
			}

			if ($Rsg2DebugActive)
			{
				Log::add('<== uploadAjax: After MoveImageAndCreateRSG2Images isCreated: ' . $isCreated );
			}

			$ajaxImgObject['fileUrl'] = $urlThumbFile; // $dstFileUrl ???

			if ($Rsg2DebugActive) {
				Log::add('    $ajaxImgObject: ' . json_encode($ajaxImgObject));
				Log::add('    $msg: "' . $msg . '"');
				Log::add('    !$isCreated (error):     ' . ((!$isCreated) ? 'true' : 'false'));
			}

			echo new JsonResponse($ajaxImgObject, $msg, !$isCreated, true);
			//echo new JsonResponse("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

			if ($Rsg2DebugActive) {
				Log::add('<== Exit uploadAjaxSingleFile');
			}

		} catch (Exception $e) {
			if ($Rsg2DebugActive) {
				Log::add('    Exception: ' . $e->getMessage());
			}

			echo new JsonResponse($e);

		}

		$app->close();
	}




	/*
		$params = ComponentHelper::getParams('com_rsgallery2');

		if ($params->get('', '0'))
		{
			$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
			$options['text_file'] = 'indexer.php';
			Log::addLogger($options);
		}

		// Log the start
		try
		{
			Log::add('Starting the indexer', Log::INFO);
		}
		catch (\RuntimeException $exception)
		{
			// Informational log only
		}
	*/

	/**
     * Copies list of selected old configuration items to new configuration
     *
     * @since 5.0.0
	 */
	/**
	public function copySelectedOldItems2New ()
	{
		$msg     = "controller.createImageDbItems: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				$cfg3xModel = $this->getModel('Upload');

				$IsAllCreated = false;
//				$input     = Factory::getApplication()->input;
//				$GalleryId = $input->get('ParentGalleryId', 0, 'INT');
				$selected = $this->input->get('cfgId', array(), 'array');
				$allNames = $this->input->get('cfgName', array(), 'array');

				if (empty ($selected))
				{
					$msg     = $msg . Text::_('COM_RSGALLERY2_NO_ITEM_SELECTED');
					$msgType = 'warning';
				} 
				else 
				{
					// Collect config names to copy
					$configNames = [];
					
					foreach ($selected as $idx => $name)
					{
						$configNames[] = $allNames[(int)$idx];
					}
					
					$isOk = $cfg3xModel->copyOldItemsList2New ($configNames);

					if ($isOk)
					{
						$msg .= "Successful copied items:" . count ($selected);
					}
					else
					{
						$msg .= "Error at copyOldItemsList2New items. Expected: " . count ($selected);
						$msgType = 'warning';					
					}
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing saveOrdering: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		// Message to user ...

		// Create list of CIDS and append to link URL like in PropertiesView above
		// &ID[]=2&ID[]=3&ID[]=4&ID[]=12
		$cids = $this->input->get('cid', 0, 'int');
		$link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids));
		$this->setRedirect($link, $msg, $msgType);
		
//		$this->setRedirect(Route::_('index.php?option=com_content&view=featured', false), $message);
	} 
	/**/


	/**
     * Copies list of selected old configuration items to new configuration
     *
     * @since 5.0.0
	 */
	/**
	public function copyOldItems2New ()
	{
		$msg     = "controller.createImageDbItems: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				$cfg3xModel = $this->getModel('Upload');

				$isOk = $cfg3xModel->copyOldItems2New ();

				if ($isOk)
				{
					$msg .= "Successful copied old configuration items";
				}
				else
				{
					$msg .= "Error at copyOldItems2New items";
					$msgType = 'error';
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing copyOldItems2New: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		// ToDo: Message to user ...

		// Create list of CIDS and append to link URL like in PropertiesView above
		// &ID[]=2&ID[]=3&ID[]=4&ID[]=12
		$cids = $this->input->get('cid', 0, 'int');
		$link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids));
		$this->setRedirect($link, $msg, $msgType);
		
//		$this->setRedirect(Route::_('index.php?option=com_content&view=featured', false), $message);
	} 
	/**/









} // class

