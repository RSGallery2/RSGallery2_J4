<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\MaintenanceJ3x;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel;

/**
 * View class for a list of rsgallery2.
 *
 * @since __BUMP_VERSION__
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
	 * @since __BUMP_VERSION__
	 */
	public function display($tpl = null)
	{
		$Layout = Factory::getApplication()->input->get('layout');
		//echo '$Layout: ' . $Layout . '<br>';

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDevelop = $rsgConfig->get('isDevelop');

		//---  --------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=config&layout=RawView');
		/**/

        $j3xModel      = $this->getModel();

		$Layout = Factory::getApplication()->input->get('layout');
		switch ($Layout)
		{
			case 'DbCopyJ3xConfig':

				try
				{
					$this->j3xConfigItems = $j3xModel->j3xConfigItems();
					$this->j4xConfigItems = $rsgConfig->toArray();

					// Configuration test lists: untouchedRsg2Config, untouchedJ3xConfig, 1:1 merged, assisted merges
                    list(
                    $this->assistedJ3xItems,
                        $this->assistedJ4xItems,
                        $this->mergedItems,
                        $this->untouchedJ3xItems,
                        $this->untouchedJ4xItems
                        ) = $j3xModel->MergeJ3xConfigTestLists($this->j3xConfigItems, $this->j4xConfigItems );
				}
				catch (\RuntimeException $e)
				{
					$OutTxt = '';
					$OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
					$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

					$app = Factory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');
				}

				break;

            case 'DBTransferJ3xGalleries':
                try
                {
                    // gallery list
                    $this->j3x_galleries = $j3xModel->j3x_galleriesList();
                    $this->j4x_galleries = $j3xModel->j4x_galleriesList();

                    $this->j3x_galleryIdsMerged = $j3xModel->MergedJ3xIdsDbGalleries ($this->j3x_galleries, $this->j4x_galleries);

                        // html
                    $this->j3x_galleriesHtml = $j3xModel->GalleriesListAsHTML($this->j3x_galleries);
                    $j4x_galleries = $j3xModel->j4_GalleriesToJ3Form($this->j4x_galleries);
                    $this->j4x_galleriesHtml = $j3xModel->GalleriesListAsHTML($j4x_galleries);

                    // gallery list
                    $this->j3x_galleries = $j3xModel->j3x_galleriesList();
                    $this->j4x_galleries = $j3xModel->j4x_galleriesList();

                    // gallery list
                    $this->j3x_galleriesSorted = $j3xModel->j3x_galleriesListSorted();

                }
                catch (\RuntimeException $e)
                {
                    $OutTxt = '';
                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                break;

            case 'DbTransferJ3xImages':
                try
                {
                    $this->j3x_images = $j3xModel->j3x_imagesList();
                    $this->j4x_images = $j3xModel->j4x_imagesList();

                    $this->j3x_imageIdsMerged = $j3xModel->MergedJ3xIdsDbImages ($this->j3x_images, $this->j4x_images);

                    // ToDo: order by gallery id
                    //$this->j3x_images_parent = $j3xModel->j3x_imagesList_parent();
                    //$this->j4x_images_parent = $j3xModel->j4x_imagesList_parent();

                }
                catch (\RuntimeException $e)
                {
                    $OutTxt = '';
                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                break;

            case 'MoveJ3xImages':
                try
                {
                    // state ?
                    $this->isMissingJ3xImages = ! $rsgConfig->get('j3x_images_copied');

                    // J3x images exist
                    if ($this->isMissingJ3xImages) {
                        $this->j3x_galleries = $j3xModel->j3x_galleriesList();
                        $this->j4x_galleries = $j3xModel->j4x_galleriesList();

                        $this->galleryIdsJ3xAsJ4x = $j3xModel->j3x_transformGalleryIdsTo_j4x($this->j3x_galleries);
                        $this->galleryIds4ImgsToBeMoved = $j3xModel->j3x_galleries4ImageMove($this->galleryIdsJ3xAsJ4x);

                        // finished by last call (move) ?  ToDo: call ajax check on empty list after move
                        if(count($this->galleryIds4ImgsToBeMoved) == 0) {

                            $this->isMissingJ3xImages = false;
                            $rsgConfig->set('j3x_images_copied', true);
                            ConfigRawModel::writeConfigParam ('j3x_images_copied', true);
                        }

                        $this->j3xGallerysData = $j3xModel->j3x_galleriesData($this->galleryIdsJ3xAsJ4x);
                    }

                    //--- Form --------------------------------------------------------------------

                    $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/forms/moveJ3xImages.xml';
                    $form = Form::getInstance('moveJ3xImages', $xmlFile);

                    // Check for errors.
                    /* Must load form before */
                    if ($errors = $this->get('Errors'))
                    {
                        if (count($errors))
                        {
                            throw new GenericDataException(implode("\n", $errors), 500);
                        }
                    }

                    $this->form = $form;

                }
                catch (\RuntimeException $e)
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
	 * @since __BUMP_VERSION__
	 */
	protected function addToolbar($Layout)
	{
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		switch ($Layout)
		{
			case 'DbCopyJ3xConfig':
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. 'Tasks: <br>'
//      				. '* <br>'
//		        		. '* <br>'
//				        . '* <br>'
//      				. '* <br>'
						. '</span><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG'), 'screwdriver');
				ToolBarHelper::custom('MaintenanceJ3x.copyJ3xConfig2J4xOptions', 'copy', '', 'COM_RSGALLERY2_COPY_COMPLETE_J3X_CONFIGURATION', false);
				//  ToolBarHelper::custom ('MaintenanceJ3x.copySelectedJ3xConfig2J4xOptions','copy','','COM_RSGALLERY2_COPY_SELECTED_J3X_CONFIGURATION', true);
				//ToolBarHelper::custom ('copyoldconfig.recompare','upload','','COM_RSGALLERY2_OLD_CONFIGURATION_RECOMPARE', true);

				ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');
				break;

			case 'DBTransferJ3xGalleries':
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. 'Tasks: <br>'
                        . '* use "name/alias" in J3x galliers overview <br>'
                        . '* user should only see what is necessary: use debug / develop for others<br>'
                        . '* Fix: Copy selected images / galleries -> greyed button, Ids in controller'
						. '* Remove double code parts: See also galleries raw view -> import into views<br>'
						. '* check table : if empty .. -> use isset ??? <br>'
						. '* !!! asset id !!! <br>'
						. '* db variable "access". how to use ???<br>'
//				        . '* !!! Test resume of partly copied galleries !!! <br>'
//     	    			. '* <br>'
//				        . '* <br>'
//     	    			. '* <br>'
						. '</span><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

				ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xGalleries2J4x', 'copy', '', 'COM_RSGALLERY2_COPY_COMPLETE_J3X_GALLERIES', false);
				//ToolBarHelper::custom ('MaintenanceJ3x.copySelectedJ3xGalleries2J4x','undo','','COM_RSGALLERY2_COPY_SELECTED_J3X_GALLERIES', true);

				break;

			case 'DbTransferJ3xImages':
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. 'Tasks: <br>'
						. '* Add copy selected J3x Images <br>'
                        . '* user should only see what is necessary: use debug / develop for others<br>'
						. '* Remove double code parts: See also images raw view -> import into views<br>'
        				. '* ? pagination <br>'
//				        . '* <br>'
//      				. '* <br>'
//		        		. '* <br>'
//				        . '* <br>'
//      				. '* <br>'
						. '</span><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

				// ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xImages2J4x', 'copy', '', 'COM_RSGALLERY2_DB_COPY_SELECTED_J3X_IMAGES', false);
				ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xImages2J4x', 'copy', '', 'COM_RSGALLERY2_DB_COPY_ALL_J3X_IMAGES', false);
				break;

			case 'MoveJ3xImages':
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. 'Tasks: <br>'
		        		. '* <br>'
//				        . '* <br>'
//      				. '* <br>'
//		        		. '* <br>'
//				        . '* <br>'
//      				. '* <br>'
//		        		. '* <br>'
						. '</span><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

//				ToolBarHelper::custom('MaintenanceJ3x.moveSelectedJ3xImages2J4x', 'copy', '', 'COM_RSGALLERY2_MOVE_SELECTED_J3X_IMAGES', false);
				//ToolBarHelper::custom('MaintenanceJ3x.moveJ3xImages2J4x', 'copy', '', 'COM_RSGALLERY2_MOVE_ALL_J3X_IMAGES', false);
				ToolBarHelper::custom('MaintenanceJ3x.updateMovedJ3xImages2J4x', 'copy', '', 'COM_RSGALLERY2_CHECK_MOVED_J3X_IMAGES', false);
				//ToolBarHelper::custom ('MaintenanceJ3x.copySelectedJ3xImages2J4x','undo','','COM_RSGALLERY2_COPY_SELECTED_J3X_IMAGES', false);
				break;

			default:
				ToolBarHelper::cancel('config.cancel', 'JTOOLBAR_CLOSE');
				break;
		}

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}
	}

}

