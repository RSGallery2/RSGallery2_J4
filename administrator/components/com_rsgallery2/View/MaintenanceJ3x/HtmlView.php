<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\View\MaintenanceJ3x;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
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
	protected $configVars;

	protected $configVarsOld;

	protected $form;

	protected $j3x_galleriesHtml;


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
		$Layout = Factory::getApplication()->input->get('layout');
		//echo '$Layout: ' . $Layout . '<br>';

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDevelop = $rsgConfig->get('isDevelop');

		$this->configVars = $rsgConfig;
		$this->configVarsOld = array ();

//		$this->form = $this->get('Form');

		//---  --------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=config&layout=RawView');
		/**/
		$Layout = Factory::getApplication()->input->get('layout');

		// ToDo: move to controller / model ...

		switch ($Layout)
		{
			case 'DbCopyOldJ3xConfig':

				try
				{
					$j3xModel      = $this->getModel();
					$this->configVarsOld = $j3xModel->OldConfigItems();


					// iterate over all values
					$this->configVarsMerged = $j3xModel->MergeJ3xConfiguration($this->configVarsOld, $this->configVars);

				}
				catch (RuntimeException $e)
				{
					$OutTxt = '';
					$OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
					$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

					$app = Factory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');
				}

				break;

            case 'DBTransferOldJ3xGalleries':
                try
                {
                    $j3xModel      = $this->getModel();

                    // gallery list
                    $this->j3x_galleries = $j3xModel->j3x_galleriesList();
                    $this->j4x_galleries = $j3xModel->j4x_galleriesList();

                    // html
                    $this->j3x_galleriesHtmlHtml = $j3xModel->GalleriesListAsHTML($this->j3x_galleries);
                    $j4x_galleries = $j3xModel->j4_GalleriesToJ3Form($this->j4x_galleries);
                    $this->j4x_galleriesHtml = $j3xModel->GalleriesListAsHTML($j4x_galleries);

                    // gallery list
                    $this->j3x_galleries = $j3xModel->j3x_galleriesList();
                    $this->j4x_galleries = $j3xModel->j4x_galleriesList();

                    // gallery list
                    $this->j3x_galleriesSorted = $j3xModel->j3x_galleriesListSorted();

                }
                catch (RuntimeException $e)
                {
                    $OutTxt = '';
                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                break;

            case 'DbTransferOldJ3xImages':
                try
                {
                    $j3xModel      = $this->getModel();
                    $this->j3x_images = $j3xModel->j3x_imagesList();
                    $this->j4x_images = $j3xModel->j4x_imagesList();

                    // ToDo: order by gallery id
                    //$this->j3x_images_parent = $j3xModel->j3x_imagesList_parent();
                    //$this->j4x_images_parent = $j3xModel->j4x_imagesList_parent();

                }
                catch (RuntimeException $e)
                {
                    $OutTxt = '';
                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                break;

        }


		Rsgallery2Helper::addSubmenu('maintenance');
		$this->sidebar = \JHtmlSidebar::render();

		$this->addToolbar($Layout);
		/**/

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function addToolbar($Layout)
	{
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		switch ($Layout)
		{
			case 'DbCopyOldJ3xConfig':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
                        . '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_COPY_OLD_J3X_CONFIG'), 'screwdriver');
				ToolBarHelper::custom ('MaintenanceJ3x.copyOldIJ3xConfig2J4xOptions','copy','','COM_RSGALLERY2_COPY_COMPLETE_OLD_J3X_CONFIGURATION', false);
				ToolBarHelper::custom ('MaintenanceJ3x.copySelectedOldJ3xConfig2J4xOptions','copy','','COM_RSGALLERY2_COPY_SELECTED_OLD_J3X_CONFIGURATION', true);
				//ToolBarHelper::custom ('copyoldconfig.recompare','upload','','COM_RSGALLERY2_OLD_CONFIGURATION_RECOMPARE', true);

				ToolBarHelper::cancel('config.cancel_rawView');
				break;

			case 'DBTransferOldJ3xGalleries':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
                        . '*  Separate code for galleries raw view -> import into views<br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
                        . '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_TRANSFER_J3X_GALLERIES'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView');


				if(count ($this->j4x_galleries) > 1) {
                    ToolBarHelper::custom ('Galleries.resetNestedGalleryTable','copy','','COM_RSGALLERY2_GALLERIES_TABLE_RESET', false);
                }

                ToolBarHelper::custom ('MaintenanceJ3x.copyOldIJ3xGalleries2J4x','copy','','COM_RSGALLERY2_COPY_COMPLETE_OLD_J3X_GALLERIES', false);
                //ToolBarHelper::custom ('MaintenanceJ3x.copySelectedOldJ3xGalleries2J4x','undo','','COM_RSGALLERY2_COPY_SELECTED_OLD_J3X_GALLERIES', true);

				break;

			case 'DbTransferOldJ3xImages':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
				. '*  Separate code for images raw view -> import into views<br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
                        . '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_TRANSFER_J3X_IMAGES'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView');

                if(count ($this->j4x_images) > 0) {
                    ToolBarHelper::custom ('MaintenanceJ3x.resetImagesTable','copy','','COM_RSGALLERY2_IMAGES_TABLE_RESET', false);
                }

                ToolBarHelper::custom ('MaintenanceJ3x.copyOldJ3xImages2J4x','copy','','COM_RSGALLERY2_COPY_COMPLETE_OLD_J3X_IMAGES', false);
                //ToolBarHelper::custom ('MaintenanceJ3x.copySelectedOldJ3xImages2J4x','undo','','COM_RSGALLERY2_COPY_SELECTED_OLD_J3X_IMAGES', false);
				break;

			default:
				ToolBarHelper::cancel('config.cancel');
				break;
		}

		// direct to config
		$toolbar->preferences('com_rsgallery2');
	}

}

