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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Response\JsonResponse;

// use Joomla\Component\Rsgallery2\Administrator\Model\ImageModel;

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

		/* ToDo: // Access check
		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$errMsg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}
		/**/

		// for debug ajax response errors / notice
		$errorType = 0; //  1: error, 2: notice, 3: enqueueMessage types error, 4: enqueue. warning 5: exception
		if ($errorType) { issueError  ($errorType);}

		try
		{
			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('==> uploadAjaxInsertInDB');
			}

			$input = Factory::getApplication()->input;

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

			//--- create image data in DB --------------------------------

			$title       = $baseName;
			$description = '';

			$imageId = $modelDb->createImageDbItem($useFileName, '', $galleryId, $description);
			if (empty($imageId))
			{
				// actual give an error
				//$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
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
		catch (\Exception $e)
		{
			echo new JsonResponse($e);
		}

		$app->close();
	}

/**
in:
interface IDroppedFile
{
	file: File;
	galleryId: string;
	...
}
interface ITransferFile extends IDroppedFile {
	imageId: string;
	fileName: string;
	dstFileName: string;
}

out:
	interface IResponseTransfer {
	fileName: string;
	imageId: string; //number
	fileUrl: string;
	safeFileName: string;
}

/**/

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

		/* ToDo: // Access check
		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$errMsg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}
		/**/

		// for debug ajax response errors / notice
		$errorType = 0; //  1: error, 2: notice, 3: enqueueMessage types error, 4: enqueue. warning 5: exception
		if ($errorType) { issueError  ($errorType);}

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

			//--- preset return value --------------------------------------------

			$rsgConfig = ComponentHelper::getParams('com_rsgallery2');
			$thumbSize = $rsgConfig->get('thumb_size');


			$ajaxImgObject['fileName'] = $targetFileName;
			// some dummy data for error messages
			$ajaxImgObject['imageId']      = -1;
			$ajaxImgObject['fileUrl']      = '';
			$ajaxImgObject['safeFileName'] = $safeFileName;
			$ajaxImgObject['thumbSize']      = $thumbSize;

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
			// Move file and create display, thumbs and watermarked images
			//----------------------------------------------------

			try
			{
				/**/
				$modelFile = $this->getModel('imageFile');
				list($isCreated, $urlThumbFile, $msg) = $modelFile->MoveImageAndCreateRSG2Images(
					$srcTempPathFileName, $targetFileName, $galleryId, 'uploadFile');
				/**/
			}
			catch (\RuntimeException $e)
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
		catch (\Exception $e)
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

		/* ToDo: // Access check
		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$errMsg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}
		/**/

		// for debug ajax response errors / notice
		$errorType = 0; //  1: error, 2: notice, 3: enqueueMessage types error, 4: enqueue. warning 5: exception
		if ($errorType) { issueError  ($errorType);}

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


			//--- Check zip file name -------------------

			// Clean up filename to get rid of strange characters like spaces etc
			//$uploadZipName = JFile::makeSafe($zip_file['name']);
//			$uploadZipName = File::makeSafe($zip_file['name']);
//			$safeFileName   = File::makeSafe($oFile['name']);






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
		catch (\Exception $e)
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


		/* ToDo: // Access check
		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$errMsg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}
		/**/

		// for debug ajax response errors / notice
		$errorType = 0; //  1: error, 2: notice, 3: enqueueMessage types error, 4: enqueue. warning 5: exception
		if ($errorType) { issueError  ($errorType);}

		try
		{
			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('==> uploadAjaxFilesInFolderReserveDbImageId');
			}

			$input = Factory::getApplication()->input;

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

			//--- ftp path --------------------------------------------

			$ftpPath = $ftp_upload_directory = $input->get('folderPath', null, 'RAW');

			// toDo: last_used_ftp_path on success or when previous path is empty
			// configDb::write2Config ('last_used_ftp_path', $ftpPath, $rsgConfig);

			// folder name does not exist
			if(!$ftpPath)
			{
				$ajaxImgObject = [];
				$app->enqueueMessage(Text::_('COM_RSGALLERY2_SERVER_DIR_EMPTY'));
				if ($Rsg2DebugActive)
				{
					Log::add($msg);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);

				$app->close();
				return;
			}

			// path with joomla root
			$ftpPathRoot = $this->path_join (JPATH_ROOT, $ftpPath);
			$ftpPathServer = $ftpPath;

			$ftpPath = is_dir($ftpPathRoot) ? $ftpPathRoot : $ftpPathServer;

			// folder does not exist
			if (!is_dir($ftpPath))
			{
				$ajaxImgObject = [];

				$app->enqueueMessage(Text::_('COM_RSGALLERY2_SERVER_DIR_NOT_EXIST') . '<br>'
					. '$Ftp path Joomla! root based: ' . $ftpPathRoot . '<br>'
					. '$Ftp path server root based: ' . $ftpPathServer // . '<br>'
				);
				if ($Rsg2DebugActive)
				{
					Log::add($msg);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);

				$app->close();
				return;
			}

			//--- select valid file names from ftp folder -------------------------------
			if ($Rsg2DebugActive)
			{
				JLog::add('Valid folder:' . strval($ftpPath));
			}

			$modelFile = $this->getModel('imageFile');
			list($filesFound, $ignored) = $modelFile->SelectImagesFromFolder ($ftpPath);

			if ($Rsg2DebugActive)
			{
				JLog::add('Select Images:' . count($filesFound));
				JLog::add('Ignored Images:' . count($ignored));
			}

			$modelDb = $this->getModel('image');
			$files = [];
			$ajaxImgDbObject = [];

			foreach ($filesFound as $filePathName)
			{
				//--- Create Destination file name -----------------------

				$filePathName = realpath ($filePathName);
				$baseName = basename($filePathName);

				// ToDo: use sub folder for each gallery and check within gallery
				// Each filename is only allowed once so create a new one if file already exist
				$useFileName = $modelDb->generateNewImageName($baseName, $galleryId);

				//----------------------------------------------------
				// Create image data in db
				//----------------------------------------------------

				$title = $baseName;
				$description = '';

				$imageId = $modelDb->createImageDbItem($useFileName, '', $galleryId, $description);
				if (empty($imageId))
				{
					// actual give an error
					//$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
					$msg .= 'Create DB item for "' . $baseName . '"->"' . $useFileName . '" failed. Use maintenance -> Consolidate image database to check it ';

					if ($Rsg2DebugActive)
					{
						Log::add($msg);
					}

					/**
					// replace newlines with html line breaks.
					//str_replace('\n', '<br>', $msg);
					echo new JsonResponse($ajaxImgDbObject, $msg, true);

					$app->close();

					return;
					/**/
					continue;
				}

				$nextFile = [];
				$nextFile ['fileName']    = $filePathName;
				$nextFile ['imageId']     = $imageId;
				$nextFile ['baseName']    = $baseName;
				$nextFile ['dstFileName'] = $useFileName;
				$nextFile ['size']   = 4500; // toDO: get size if necessary
				$files [] = $nextFile;
			}

			// Images exist
			if (!$files)
			{
				$ajaxImgObject = [];

				$app->enqueueMessage(Text::_('COM_RSGALLERY2_SERVER_FILES_DO_NOT_EXIST') . '<br>'
					. '$Ftp path Joomla! root based: ' . $ftpPathRoot . '<br>'
					. '$Ftp path server root based: ' . $ftpPathServer // . '<br>'
				);
				if ($Rsg2DebugActive)
				{
					Log::add($msg);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);

				$app->close();
				return;
			}

			$ajaxImgObject ['files'] = $files;

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

			if ($Rsg2DebugActive)
			{
				Log::add('<== Exit uploadAjaxSingleFile');
			}

		}
		catch (\Exception $e)
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
in:
interface IRequestTransferFolderFile {
	fileName: string;
	imageId: string; //number
	baseName: string;
	dstFileName: string;
	size: number;

	galleryId: string;
	origin: string; // ftp/server

	statusBar: createStatusBar | null;
	errorZone: HTMLElement | null;
}

out:
interface IResponseTransfer {
	fileName: string;
	imageId: string; //number
	fileUrl: string;
	safeFileName: string;
}

/**/

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

		/* ToDo: // Access check
		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$errMsg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
			$hasError = 1;
			echo new JsonResponse($msg, $errMsg, $hasError);
			$app->close();
		}
		/**/

		// for debug ajax response errors / notice
		$errorType = 0; //  1: error, 2: notice, 3: enqueueMessage types error, 4: enqueue. warning 5: exception
		if ($errorType) { issueError  ($errorType);}

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
			$origin = $input->get('origin', '', 'string'); //zip/server
			// toDo: rename dstFileName to safe file name ... so it matches function uploadAjaxSingleFile()
			$targetFileName = $input->get('dstFileName', '', 'string');
/**
			fileName: string;
			imageId: string; //number
			baseName: string;
			dstFileName: string;
			size: number;

			galleryId: string;
			origin: string; // ftp/server
/**/

			if ($Rsg2DebugActive)
			{
				// identify active file
				Log::add('$fileName: "' . $fileName . '"');
				Log::add('$targetFileName: "' . $targetFileName . '"');
				Log::add('$imageId: "' . $imageId . '"');
				Log::add('$baseName: "' . $baseName . '"');
			}

			$ajaxImgObject['fileName'] = $targetFileName;
			// some dummy data for error messages
			$ajaxImgObject['imageId']      = $imageId;
			$ajaxImgObject['fileUrl']      = '';
			$ajaxImgObject['safeFileName'] = $fileName;

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

			//----------------------------------------------------
			// Move/copy file and create display, thumbs and watermarked images
			//----------------------------------------------------

			try
			{
				/**/
				$modelFile = $this->getModel('imageFile');
				// toDo check origin and config for copy / or move file call below
				list($isCreated, $urlThumbFile, $msg) = $modelFile->MoveImageAndCreateRSG2Images(
					$fileName, $targetFileName, $galleryId, $origin);
				/**/
			}
			catch (\RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'moveFile2OrignalDir: "' . $fileName . '" -> "' . $targetFileName . '"<br>';
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
					Log::add('MoveImageAndCreateRSG2Images failed: ' . $fileName . '" -> "' . $targetFileName);
				}

				echo new JsonResponse($ajaxImgObject, $msg, true);

				$app->close();
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
				Log::add('<== Exit uploadAjaxTransferFolderFile');
			}

		}
		catch (\Exception $e)
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
	 * ajax response error tests
	 * function may be included in all ajax calls for tests of errors
	 *
	 * @param $errorType integer
	 *     1: error
	 *     2: notice
	 *     3: enqueueMessage types with error set
	 *     4: enqueueMessage types with NO error set
	 *     5: enqueueMessage types with thrown exception
	 *
	 * @throws \Exception
	 * @since version
	 */
	private function issueError  ($errorType)
	{
		$app = Factory::getApplication();

		//  0: nothing, 1:error, 2:notice, .... see above
		if ($errorType)
		{
			$result = "Resulting data (simulated)";
			switch ($errorType)
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

					echo new JsonResponse($result, 'Response message with !!! no !!! error set');
					break;
			}

			$app->close();
		}
		/**/
	}

	function path_join () {

		$paths = array();

		foreach (func_get_args() as $arg) {
			if ($arg !== '') { $paths[] = $arg; }
		}

		return preg_replace('#/+#','/',join('/', $paths));
	}

} // class

