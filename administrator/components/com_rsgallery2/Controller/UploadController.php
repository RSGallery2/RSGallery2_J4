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
	 * @param array               $config  An optional associative array of configuration settings.
	 *                                     Recognized key values include 'name', 'default_task', 'model_path', and
	 *                                     'view_path' (this list is not meant to be comprehensive).
	 * @param MVCFactoryInterface $factory The factory.
	 * @param CMSApplication      $app     The JApplication for the dispatcher
	 * @param \JInput             $input   Input
	 *
	 * @since   1.0
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);

	}

	/**
	 * Proxy for getModel.
	 *
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
	 * @throws Exception
	 * @since 4.3.0
	 */
	function uploadAjaxReserveDbImageId()
	{
		// Todo: Check Authorisation, Jupload , check mime type ...

		global $rsgConfig, $Rsg2DebugActive;

		$msg = 'uploadAjaxReserveImageInDB::';
		$app = Factory::getApplication();

		// do check token
		// Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		if (!Session::checkToken())
		{
			$errMsg   = Text::_('JINVALID_TOKEN') . " (02)";
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
			return; // ToDo Check on all pre exits
		}

		try
		{
			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('==> uploadAjaxInsertInDB');
			}

			$input = Factory::getApplication()->input;
			//$oFile = $input->files->get('upload_file', array(), 'raw');
			//$TargetFileName    = File::makeSafe($oFile['name']);

			/**
			 * Form data:
			 *
			 * data.append('upload_file', nextFile.file.name);
			 * data.append('upload_size', nextFile.file.size.toString());
			 * data.append('upload_type', nextFile.file.type);
			 * data.append(Token, '1');
			 * data.append('gallery_id', nextFile.galleryId);
			 * /**/

			//--- ajax tests --------------------------------------------
			// ToDo: use as  function to be included i all ajex calls fot tests of errors
			// ToDo: Remove -> put into own component
			/**
			 * $test = 1; // 0: normal, 1:error, 2: warning ....
			 *
			 * if ($test)
			 * {
			 * $result = "Resulting data (simulated)";
			 * switch ($test)
			 * {
			 * case 1:
			 * echo new JsonResponse($result, Text::_('COM_COMPONENT_MY_TASK_ERROR'), true);
			 * break;
			 *
			 * case 2:
			 * echo new JsonResponse($result, 'Main response message');
			 * break;
			 *
			 * case 3:
			 * $app->enqueueMessage('This part has error 1');
			 * $app->enqueueMessage('This part has error 2');
			 * $app->enqueueMessage("Enqueued notice 1", "notice");
			 * $app->enqueueMessage("Enqueued notice 2", "notice");
			 * $app->enqueueMessage('Here was a small warning 1', 'warning');
			 * $app->enqueueMessage('Here was a small warning 2', 'warning');
			 * $app->enqueueMessage('Here was a small error 1', 'error');
			 * $app->enqueueMessage('Here was a small error 2', 'error');
			 * echo new JsonResponse($result, Text::_('!!! Response message with error set !!!'), true);
			 * break;
			 *
			 * case 4:
			 * $app->enqueueMessage('This part was successful 1');
			 * $app->enqueueMessage('This part was successful 2');
			 * $app->enqueueMessage("Enqueued notice 1", "notice");
			 * $app->enqueueMessage("Enqueued notice 2", "notice");
			 * $app->enqueueMessage('Here was a small warning 1', 'warning');
			 * $app->enqueueMessage('Here was a small warning 2', 'warning');
			 * $app->enqueueMessage('Here was a small error 1', 'error');
			 * $app->enqueueMessage('Here was a small error 2', 'error');
			 * echo new JsonResponse($result, 'Response message with !!! no !!! error set');
			 * break;
			 * case 5:
			 * $app->enqueueMessage('This part was successful 1');
			 * $app->enqueueMessage('This part was successful 2');
			 * $app->enqueueMessage("Enqueued notice 1", "notice");
			 * $app->enqueueMessage("Enqueued notice 2", "notice");
			 * $app->enqueueMessage('Here was a small warning 1', 'warning');
			 * $app->enqueueMessage('Here was a small warning 2', 'warning');
			 * $app->enqueueMessage('Here was a small error 1', 'error');
			 * $app->enqueueMessage('Here was a small error 2', 'error');
			 *
			 * throw new \Exception('Attention: raised exception ');
			 * //                        throw new Notallowed(Text::_('Not allowed thrown'), 403);
			 *
			 *
			 * echo new JsonResponse($result, 'Response message with !!! no !!! error set');
			 * break;
			 * }
			 *
			 * $app->close();
			 * }
			 * /**/

			//--- file name  --------------------------------------------

			$TargetFileName = $input->get('upload_file_name', '', 'string');
			$fileName       = File::makeSafe($TargetFileName);

// ==>			joomla replace spaces in filenames
// ==>			'file_name' => str_replace(" ", "", $file_name);
			$baseName = basename($fileName);

			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('$TargetFileName: "' . $TargetFileName . '"');
				Log::add('$fileName: "' . $fileName . '"');
				Log::add('$baseName: "' . $baseName . '"');
			}

			$ajaxImgDbObject['uploadFileName'] = $TargetFileName;
			// some dummy data for error messages
			$ajaxImgDbObject['imageId']     = -1;
			$ajaxImgDbObject['baseName']    = $baseName;
			$ajaxImgDbObject['dstFileName'] = $fileName;

			//--- gallery ID --------------------------------------------

			$galleryId = $input->get('gallery_id', 0, 'INT');
			// wrong id ? ToDo: test is number ...
			if ($galleryId < 1)
			{
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
			$useFileName                    = $modelDb->generateNewImageName($baseName, $galleryId);
			$ajaxImgDbObject['dstFileName'] = $useFileName;

			/**/
			//--- create image data in DB --------------------------------

			$title       = $baseName;
			$description = '';

			$imageId = $modelDb->createImageDbItem($useFileName, '', $galleryId, $description);
			if (empty($imageId))
			{
				// actual give an error
				//$msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
				$msg .= 'Create DB item for "' . $baseName . '"->"' . $useFileName . '" failed. Use maintenance -> Consolidate image database to check it ';

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
				Log::add('<== uploadAjax: After createImageDbItem: ' . $imageId);
			}

			// $this->ajaxDummyAnswerOK (); return; // 05

			$ajaxImgDbObject['imageId'] = $imageId;
			$isCreated                  = $imageId > 0;

			//----------------------------------------------------
			// for debug purposes fetch image order
			//----------------------------------------------------

			$imageOrder               = $this->imageOrderFromId($imageId);
			$ajaxImgDbObject['order'] = $imageOrder;

			//----------------------------------------------------
			// return result
			//----------------------------------------------------

			if ($Rsg2DebugActive)
			{
				Log::add('    $ajaxImgDbObject: ' . json_encode($ajaxImgDbObject));
				Log::add('    $msg: "' . $msg . '"');
				Log::add('    !$isCreated (error):     ' . ((!$isCreated) ? 'true' : 'false'));
			}

			// No message as otherwise it would be displayed in form
			echo new JsonResponse($ajaxImgDbObject, "", !$isCreated, true);

			if ($Rsg2DebugActive)
			{
				Log::add('<== Exit uploadAjaxSingleFile');
			}

		}
		catch (Exception $e)
		{
			echo new JsonResponse($e);
		}

		$app->close();
	}


	/**
	 * The dropped file will be uploaded. The dependent files
	 * display and thumb will also be created
	 * The image id was created before and is read from the
	 * ajax parameters
	 *
	 * @since 4.3
	 */
	function uploadAjaxSingleFile()
	{

		// Todo: Check Authorisation, Jupload , check mime type ...

		global $rsgConfig, $Rsg2DebugActive;

		// $IsMoved = false;
		$msg = 'uploadAjaxSingleFile';
		$app = Factory::getApplication();

		// do check token
		// Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		if (!Session::checkToken())
		{
			$errMsg   = Text::_('JINVALID_TOKEN') . " (01)";
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}

		try
		{
			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('==> uploadAjaxSingleFile');
			}

			$input = Factory::getApplication()->input;
			$oFile = $input->files->get('upload_file', array(), 'raw');

			$srcTempPathFileName = $oFile['tmp_name'];
			$fileType            = $oFile['type'];
			$fileError           = $oFile['error'];
			$fileSize            = $oFile['size'];

			// Changed name of existing file name
			$safeFileName   = File::makeSafe($oFile['name']);
			$targetFileName = $input->get('dstFileName', '', 'string');

			// for next upload tell where to start
			//$rsgConfig->setLastUpdateType('upload_drag_and_drop');
			// configDb::write2Config ('last_update_type', 'upload_drag_and_drop', $rsgConfig);

			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('$srcTempPathFileName: "' . $srcTempPathFileName . '"');
				Log::add('$safeFileName: "' . $safeFileName . '"');
				Log::add('$targetFileName: "' . $targetFileName . '"');
				Log::add('$fileType: "' . $fileType . '"');
				Log::add('$fileError: "' . $fileError . '"');
				Log::add('$fileSize: "' . $fileSize . '"');
			}

			//--- check user ID --------------------------------------------

			$ajaxImgObject['fileName'] = $targetFileName;
			// some dummy data for error messages
			$ajaxImgObject['imageId']      = -1;
			$ajaxImgObject['fileUrl']      = '';
			$ajaxImgObject['safeFileName'] = $safeFileName;

			//--- gallery ID --------------------------------------------

			$galleryId = $input->get('gallery_id', 0, 'INT');
			// wrong id ?
			if ($galleryId < 1)
			{
				$msg .= ': Invalid gallery ID at drag and drop upload';

				if ($Rsg2DebugActive)
				{
					Log::add($msg);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);

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

			$ajaxImgObject['imageId'] = $imageId;

			//----------------------------------------------------
			// for debug purposes fetch image order
			//----------------------------------------------------

//			$imageOrder = $this->imageOrderFromId ($imageId);
//			$ajaxImgObject['order']  = $imageOrder;

			//----------------------------------------------------
			// Move file and create display, thumbs and watermarked images
			//----------------------------------------------------

			try
			{
				/**/
				$modelFile = $this->getModel('imageFile');
				list($isCreated, $urlThumbFile, $msg) = $modelFile->MoveImageAndCreateRSG2Images(
					$srcTempPathFileName, $targetFileName, $galleryId);
				/**/
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'moveFile2OrignalDir: "' . $srcTempPathFileName . '" -> "' . $targetFileName . '"<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');

				if ($Rsg2DebugActive)
				{
					Log::add($OutTxt);
				}
			}

			if (!$isCreated)
			{
				// ToDo: remove $imageId fom image database
				if ($Rsg2DebugActive)
				{
					Log::add('MoveImageAndCreateRSG2Images failed: ' . $srcTempPathFileName . '" -> "' . $targetFileName);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);
				$app->close();
// ToDo ? app close in these cases
				return;
			}

			if ($Rsg2DebugActive)
			{
				Log::add('<== uploadAjax: After MoveImageAndCreateRSG2Images isCreated: ' . $isCreated);
			}

			$ajaxImgObject['fileUrl'] = $urlThumbFile; // $dstFileUrl ???

			if ($Rsg2DebugActive)
			{
				Log::add('    $ajaxImgObject: ' . json_encode($ajaxImgObject));
				Log::add('    $msg: "' . $msg . '"');
				Log::add('    !$isCreated (error):     ' . ((!$isCreated) ? 'true' : 'false'));
			}

			echo new JsonResponse($ajaxImgObject, $msg, !$isCreated, true);
			//echo new JsonResponse("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

			if ($Rsg2DebugActive)
			{
				Log::add('<== Exit uploadAjaxSingleFile');
			}

		}
		catch (Exception $e)
		{
			if ($Rsg2DebugActive)
			{
				Log::add('    Exception: ' . $e->getMessage());
			}

			echo new JsonResponse($e);

		}

		$app->close();
	}


	/**
	 * Extracts uploaded zip and creates database entries for each file
	 *
	 * The database entry for the image will be created here
	 * It is called for each image for preserving the correct
	 * ordering before uploading the images
	 * Reason: The parallel uploaded images may appear unordered
	 *
	 * @throws Exception
	 * @since 4.3.0
	 */
	function uploadAjaxZipExtractReserveDbImageId()
	{
		// Todo: Check Authorisation, Jupload , check mime type ...


		global $rsgConfig, $Rsg2DebugActive;

		$msg = 'uploadAjaxZipExtractReserveDbImageId::';
		$app = Factory::getApplication();

		// do check token
		// Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		if (!Session::checkToken())
		{
			$errMsg   = Text::_('JINVALID_TOKEN') . " (02)";
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}

		try
		{
			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('==> uploadAjaxZipExtractReserveDbImageId');
			}

			$input = Factory::getApplication()->input;
			$oFile = $input->files->get('upload_zip_name', array(), 'raw');

			$srcTempPathFileName = $oFile['tmp_name'];
			$fileType            = $oFile['type'];
			$fileError           = $oFile['error'];
			$fileSize            = $oFile['size'];

			//--- gallery ID --------------------------------------------

			$galleryId = $input->get('gallery_id', 0, 'INT');
			// wrong id ?
			if ($galleryId < 1)
			{
				$ajaxImgObject = [];
				$msg .= ': Invalid gallery ID at drag and drop upload';

				if ($Rsg2DebugActive)
				{
					Log::add($msg);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);

				$app->close();
				return;
			}

			// Database IDs of created images
			$cids = array();

			// Prepare variables needed /created inside brackets {} for phpstorm code check
			$isHasError      = false;
			$zipPathFileName = '';


			/**
			 * interface IResponseServerFile {
			 * fileName: string;
			 * imageId: string; //number
			 * baseName: string;
			 * dstFileName: string;
			 * }
			 *
			 * interface IResponseServerFiles {
			 * // Path ?
			 * files: IResponseServerFile [];
			 * }
			 * /**/

			$file1                 = [];
			$file1 ['fileName']    = 'file1_name';
			$file1 ['imageId']     = '1001';
			$file1 ['baseName']    = 'base1_name';
			$file1 ['dstFileName'] = 'dest1_name';
			$file1 ['galleryId'] = $galleryId;


			$file2                 = [];
			$file2 ['fileName']    = 'file2_name';
			$file2 ['imageId']     = '1002';
			$file2 ['baseName']    = 'base2_name';
			$file2 ['dstFileName'] = 'dest2_name';
			$file2 ['galleryId'] = $galleryId;

			$file3                 = [];
			$file3 ['fileName']    = 'file3_name';
			$file3 ['imageId']     = '1003';
			$file3 ['baseName']    = 'base3_name';
			$file3 ['dstFileName'] = 'dest3_name';
			$file3 ['galleryId'] = $galleryId;

			$ajaxImgObject ['files'] = [$file1, $file2, $file2];

			$isCreated = true;

			/**
			 * // for next upload tell where to start
			 * //$rsgConfig->setLastUpdateType('upload_drag_and_drop');
			 * // configDb::write2Config ('last_update_type', 'upload_drag_and_drop', $rsgConfig);
			 *
			 * if ($Rsg2DebugActive)
			 * {
			 * // identify active file
			 * Log::add('$srcTempPathFileName: "' . $srcTempPathFileName . '"');
			 * Log::add('$safeFileName: "' . $safeFileName . '"');
			 * Log::add('$targetFileName: "' . $targetFileName . '"');
			 * Log::add('$fileType: "' . $fileType . '"');
			 * Log::add('$fileError: "' . $fileError . '"');
			 * Log::add('$fileSize: "' . $fileSize . '"');
			 * }
			 *
			 * //--- check user ID --------------------------------------------
			 *
			 * $ajaxImgObject['fileName'] = $targetFileName;
			 * // some dummy data for error messages
			 * $ajaxImgObject['imageId']  = -1;
			 * $ajaxImgObject['fileUrl']  = '';
			 * $ajaxImgObject['safeFileName'] = $safeFileName;
			 * /**/










			echo new JsonResponse($ajaxImgObject, "", !$isCreated, true);
			//echo new JsonResponse("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

			if ($Rsg2DebugActive)
			{
				Log::add('<== Exit uploadAjaxSingleFile');
			}

		}
		catch (Exception $e)
		{
			if ($Rsg2DebugActive)
			{
				Log::add('    Exception: ' . $e->getMessage());
			}

			echo new JsonResponse($e);

		}

		$app->close();
	}



	/**
	 * Extracts uploaded zip and creates database entries for each file
	 *
	 * The database entry for the image will be created here
	 * It is called for each image for preserving the correct
	 * ordering before uploading the images
	 * Reason: The parallel uploaded images may appear unordered
	 *
	 * @throws Exception
	 * @since 4.3.0
	 */
	function uploadAjaxFilesInFolderReserveDbImageId()
	{
		// Todo: Check Authorisation, Jupload , check mime type ...

		global $rsgConfig, $Rsg2DebugActive;

		$msg = 'uploadAjaxFilesInFolderReserveDbImageId::';
		$app = Factory::getApplication();

		// do check token
		// Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		if (!Session::checkToken())
		{
			$errMsg   = Text::_('JINVALID_TOKEN') . " (02)";
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}

		try
		{
			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('==> uploadAjaxFilesInFolderReserveDbImageId');
			}

			$input = Factory::getApplication()->input;
			$oFile = $input->files->get('upload_zip_name', array(), 'raw');

			$srcTempPathFileName = $oFile['tmp_name'];
			$fileType            = $oFile['type'];
			$fileError           = $oFile['error'];
			$fileSize            = $oFile['size'];

			//--- gallery ID --------------------------------------------

			$galleryId = $input->get('gallery_id', 0, 'INT');
			// wrong id ?
			if ($galleryId < 1)
			{
				$ajaxImgObject = [];
				$msg .= ': Invalid gallery ID at drag and drop upload';

				if ($Rsg2DebugActive)
				{
					Log::add($msg);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);

				$app->close();
				return;
			}


			// Database IDs of created images
			$cids = array();

			// Prepare variables needed /created inside brackets {} for phpstorm code check
			$isHasError      = false;
			$zipPathFileName = '';


			/**
			 * interface IResponseServerFile {
			 * fileName: string;
			 * imageId: string; //number
			 * baseName: string;
			 * dstFileName: string;
			 * }
			 *
			 * interface IResponseServerFiles {
			 * // Path ?
			 * files: IResponseServerFile [];
			 * }
			 * /**/

			$file1                 = [];
			$file1 ['fileName']    = 'file1_name';
			$file1 ['imageId']     = '1001';
			$file1 ['baseName']    = 'base1_name';
			$file1 ['dstFileName'] = 'dest1_name';
			$file1 ['galleryId'] = $galleryId;

			$file2                 = [];
			$file2 ['fileName']    = 'file2_name';
			$file2 ['imageId']     = '1002';
			$file2 ['baseName']    = 'base2_name';
			$file2 ['dstFileName'] = 'dest2_name';
			$file2 ['galleryId'] = $galleryId;

			$file3                 = [];
			$file3 ['fileName']    = 'file3_name';
			$file3 ['imageId']     = '1003';
			$file3 ['baseName']    = 'base3_name';
			$file3 ['dstFileName'] = 'dest3_name';
			$file3 ['galleryId'] = $galleryId;

			$ajaxImgObject ['files'] = [$file1, $file2, $file2];

			$isCreated = true;

			/**
			 * // for next upload tell where to start
			 * //$rsgConfig->setLastUpdateType('upload_drag_and_drop');
			 * // configDb::write2Config ('last_update_type', 'upload_drag_and_drop', $rsgConfig);
			 *
			 * if ($Rsg2DebugActive)
			 * {
			 * // identify active file
			 * Log::add('$srcTempPathFileName: "' . $srcTempPathFileName . '"');
			 * Log::add('$safeFileName: "' . $safeFileName . '"');
			 * Log::add('$targetFileName: "' . $targetFileName . '"');
			 * Log::add('$fileType: "' . $fileType . '"');
			 * Log::add('$fileError: "' . $fileError . '"');
			 * Log::add('$fileSize: "' . $fileSize . '"');
			 * }
			 *
			 * //--- check user ID --------------------------------------------
			 *
			 * $ajaxImgObject['fileName'] = $targetFileName;
			 * // some dummy data for error messages
			 * $ajaxImgObject['imageId']  = -1;
			 * $ajaxImgObject['fileUrl']  = '';
			 * $ajaxImgObject['safeFileName'] = $safeFileName;
			 * /**/










			echo new JsonResponse($ajaxImgObject, "", !$isCreated, true);
			//echo new JsonResponse("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

			if ($Rsg2DebugActive)
			{
				Log::add('<== Exit uploadAjaxSingleFile');
			}

		}
		catch (Exception $e)
		{
			if ($Rsg2DebugActive)
			{
				Log::add('    Exception: ' . $e->getMessage());
			}

			echo new JsonResponse($e);

		}

		$app->close();
	}




	/**
	 * The already uploaded file will be copied,
	 * display and thumb files created
	 *
	 * @throws Exception
	 * @since 4.3.0
	 */
	function uploadAjaxTransferFolderFile()
	{
		// Todo: Check Authorisation, Jupload , check mime type ...

		global $rsgConfig, $Rsg2DebugActive;

		$msg = 'uploadAjaxTransferFolderFile::';
		$app = Factory::getApplication();

		// do check token
		// Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		if (!Session::checkToken())
		{
			$errMsg   = Text::_('JINVALID_TOKEN') . " (02)";
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}

		try
		{
			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('==> uploadAjaxTransferFolderFile');
			}

			$input = Factory::getApplication()->input;

			$fileName = $input->get('fileName', '', 'string');
			$imageId = $input->get('imageId', '', 'string');
			$baseName = $input->get('baseName', '', 'string');
			// toDo: rename dstFileName to safe file name ... sio it matches 	function uploadAjaxSingleFile()
			$safeFileName = $input->get('dstFileName', '', 'string');
			
			$ajaxImgDbObject['fileName'] = $fileName;
			$ajaxImgDbObject['imageId'] = $imageId;
//			$ajaxImgDbObject['baseName'] = $baseName;
//			$ajaxImgDbObject['dstFileName'] = $dstFileName;

			$ajaxImgObject['fileUrl']      = '';
			$ajaxImgObject['safeFileName'] = $safeFileName;


			// Prepare variables needed /created inside brackets {} for phpstorm code check
			$isHasError      = false;
			$zipPathFileName = '';


			$ajaxImgObject ['isTransferred'] = true;



			$isCreated = true;












			echo new JsonResponse($ajaxImgObject, "", !$isCreated, true);
			//echo new JsonResponse("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

			if ($Rsg2DebugActive)
			{
				Log::add('<== Exit uploadAjaxTransferFolderFile');
			}

		}
		catch (Exception $e)
		{
			if ($Rsg2DebugActive)
			{
				Log::add('    Exception: ' . $e->getMessage());
			}

			echo new JsonResponse($e);

		}

		$app->close();
	}



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


	/**
	 * @param $imageId
	 *
	 * @return int|mixed
	 *
	 * @throws \Exception
	 * @since version
	 */
	private function imageOrderFromId ($imageId)
	{
		$imageOrder = -1;

		try
		{
			$db    = Factory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('ordering'))
				->from($db->quoteName('#__rsg2_images'))
				->where($db->quoteName('id') . ' = ' . $db->quote($imageId));
			$db->setQuery($query);
			$imageOrder = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageOrderFromId for $imageId: "' . $imageId . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $imageOrder;
	}

} // class

