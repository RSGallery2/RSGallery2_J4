<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\View\Upload;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\FilesystemHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Joomla\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;

/**
 * View class for a list of rsgallery2.
 *
 * @since  1.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;
	protected $form;

	protected $isDebugBackend;
	protected $isDevelop;

	protected $UploadLimit;
	protected $PostMaxSize;
	protected $MemoryLimit;
	protected $MaxSize;

	protected $FtpUploadPath;
	// protected $LastUsedUploadZip;
	protected $is1GalleryExisting;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise an \Exception object.
	 *
	 * @since   1.0
	 */
	public function display($tpl = null)
	{

		//--- config --------------------------------------------------------------------

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		//$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDebugBackend = $rsgConfig->get('isDebugBackend');
		$this->isDevelop = $rsgConfig->get('isDevelop');

		//--- Form --------------------------------------------------------------------

		$xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/forms/upload.xml';
		$form = Form::getInstance('upload', $xmlFile);

		// Check for errors.
		/* Must load form before */
		if ($errors = $this->get('Errors'))
		{
			if (count($errors))
			{
				throw new GenericDataException(implode("\n", $errors), 500);
			}
		}
		/**/

		//--- Limits --------------------------------------------------------------------

		// Instantiate the media helper
		$mediaHelper = new MediaHelper;

		// Maximum allowed size in MB
		$this->UploadLimit = round($mediaHelper->toBytes(ini_get('upload_max_filesize')) / (1024 * 1024));
		$this->PostMaxSize = round($mediaHelper->toBytes(ini_get('post_max_size')) / (1024 * 1024));
		$this->MemoryLimit = round($mediaHelper->toBytes(ini_get('memory_limit')) / (1024 * 1024));
		$this->MaxSize = FilesystemHelper::fileUploadMaxSize();

		//--- FtpUploadPath ------------------------

		// Retrieve path from config
		$FtpUploadPath = $rsgConfig->get('ftp_path');
		// On empty use last successful
		if (empty ($FtpUploadPath)) {
			$FtpUploadPath = $rsgConfig->get('last_used_ftp_path');
		}
		$this->FtpUploadPath = $FtpUploadPath;

		//--- LastUsedUploadZip ------------------------

		// Not possible to set input variable in HTML so it is not collected
		// $this->LastUploadedZip = $app->getUserState('com_rsgallery2.last_used_uploaded_zip');
		// $LastUsedUploadZip->getLastUsedUploadZip();

		// register 'upload_drag_and_drop', 'upload_zip_pc', 'upload_folder_server'
		//$this->ActiveSelection = $rsgConfig->getLastUpdateType();
		$this->ActiveSelection = $rsgConfig->get('last_update_type');
		if (empty ($this->ActiveSelection)) {
			$this->ActiveSelection = 'upload_drag_and_drop';
		}

		// 0: default, 1: enable, 2: disable
		$isUseOneGalleryNameForAllImages = $rsgConfig->get('isUseOneGalleryNameForAllImages');
		if (empty ($isUseOneGalleryNameForAllImages)) {
			$isUseOneGalleryNameForAllImages = '1';
		}
		if ($isUseOneGalleryNameForAllImages == '2') {
			$isUseOneGalleryNameForAllImages = '0';
		}

		//--- Pre select latest gallery ?  ------------------------

		$IdGallerySelect = -1; //No selection

		$input = Factory::getApplication()->input;

		// coming from gallery edit -> new id
		$Id = $input->get('id', 0, 'INT');
		if (!empty ($Id)) {
			$IdGallerySelect = $Id;
		}

		$isPreSelectLatestGallery = $rsgConfig->get('IsPreSelectLatestGallery');
		if ($isPreSelectLatestGallery) {
			$IdGallerySelect = $this->getIdLatestGallery();
		}

		$this->is1GalleryExisting = $this->is1GalleryExisting();

		// upload_zip, upload_folder
		$formParam = array(
			'all_img_in_step1_01' => $isUseOneGalleryNameForAllImages,
			'all_img_in_step1_02' => $isUseOneGalleryNameForAllImages,
			'SelectGalleries01_01' => $IdGallerySelect,
			'SelectGalleries02_02' => $IdGallerySelect
		);

		$form->bind($formParam);

		$this->form = $form;

		/**
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new RuntimeException(implode('<br />', $errors), 500);
		}
		/**/

		// Assign the Data
		// $this->form = $form;
		// $this->item = $item;




		//---  --------------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=Upload');
		Rsgallery2Helper::addSubmenu('upload');
		$this->sidebar = \JHtmlSidebar::render();

		//$Layout = Factory::getApplication()->input->get('layout');
		$this->addToolbar();

		return parent::display($tpl);
	}


	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function addToolbar()
	{
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// on develop show open tasks if existing
		if (!empty ($this->isDevelop))
		{
			echo '<span style="color:red">'
				. 'Tasks: <br>'
				. '* Test ...<br>'
				. '* check image db for not set items <br>'
				. '* Redesign upload list: flex<br>'
				. '* typescript: redesing uplod list filling -> use php html prepared blocks and clone them<br>'
				. '* 3 seconds finished OK then close bar<br>'
				. '* status bar -> bootstrap ? + aria ...<br>'
				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
				. '</span><br><br>';
		}

		// Set the title
		ToolBarHelper::title(Text::_('COM_RSGALLERY2_DO_UPLOAD'), 'upload');

		$toolbar->preferences('com_rsgallery2');
	}

	/**
	 * Check if at least one gallery exists
	 * Regards the nested structure (ID=1 is only root of tree and no gallery)
	 *
	 * @return true on galleries found
	 *
	 * @since 4.3.0
	 */
	public function is1GalleryExisting()
	{
		$is1GalleryExisting = false;

		try
		{
			$db    = Factory::getDbo();
			$query = $db->getQuery(true);

			// count gallery items
			$query->select('COUNT(*)')
				// ignore root item  where id is "1"
				->where($db->quoteName('id') . ' != 1')
				->from('#__rsg2_galleries');

			$db->setQuery($query, 0, 1);
			$IdGallery          = $db->loadResult();

			// > 0 galleries exist
			$is1GalleryExisting = !empty ($IdGallery);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error count for galleries in "__rsg2_galleries" table' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $is1GalleryExisting;
	}
}

