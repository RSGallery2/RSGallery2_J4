<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\MaintenanceJ3x;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Filesystem\Path;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel;

/**
 * View class for a list of rsgallery2.
 *
     * @since      5.1.0
 */
class HtmlView extends BaseHtmlView
{
    protected $configVars;

    protected $configVarsOld;

    protected $form;

    protected $j3x_galleriesHtml;

	protected $j3x_galleryIdsMerged = null;
	protected $j3x_galleriesSorted = null;
	protected $j4x_galleries = null;
	/**
	 * @var mixed|\stdClass
	 * @since 5.1.0
	 */
	protected mixed $isDevelop;
	/**
	 * @var
	 * @since 5.1.0
	 */
	protected $j3xConfigItems;
	/**
	 * @var array
	 * @since 5.1.0
	 */
	protected array $j4xConfigItems;
	/**
	 * @var mixed
	 * @since 5.1.0
	 */
	protected mixed $assistedJ3xItems;
	/**
	 * @var mixed
	 * @since 5.1.0
	 */
	protected mixed $assistedJ4xItems;
	/**
	 * @var mixed
	 * @since 5.1.0
	 */
	protected mixed $mergedItems;
	/**
	 * @var mixed
	 * @since 5.1.0
	 */
	protected mixed $untouchedJ3xItems;
	/**
	 * @var mixed
	 * @since 5.1.0
	 */
	protected mixed $untouchedJ4xItems;
	/**
	 * @var array
	 * @since 5.1.0
	 */
	protected array $j3x_transformGalleryIdsTo_j4x;
	/**
	 * @var array
	 * @since 5.1.0
	 */
	protected array $galleryIdsJ3x_dbImagesNotMoved;
	/**
	 * @var array
	 * @since 5.1.0
	 */
	protected array $j3xNotMovedInfo;
	/**
	 * @var
	 * @since 5.1.0
	 */
	protected $galleryIdsJ3x_NotMoved;
	/**
	 * @var
	 * @since 5.1.0
	 */
	protected $galleryIdsJ3xAsJ4x;
	/**
	 * @var string
	 * @since 5.1.0
	 */
	protected string $sidebar;

	/**
     * Method to display the view.
     *
     * @param   string  $tpl  A template file to load. [optional]
     *
     * @return  mixed  A string if successful, otherwise an \Exception object.
     *
     * @since   5.1.0     */
    public function display($tpl = null)
    {
        $Layout = Factory::getApplication()->input->get('layout');
        //echo '$Layout: ' . $Layout . '<br>';

        $rsgConfig       = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        $this->isDevelop = $rsgConfig->get('isDevelop');

        //---  --------------------------------------------------------------

        HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=config&layout=RawView');
        /**/

        //--- load additional language file --------------------------------

        $lang = Factory::getApplication()->getLanguage();
		$lang->load('com_rsg2_j3x',
			Path::clean(JPATH_ADMINISTRATOR . '/components/' . 'com_rsgallery2'), null, false, true);

        //---  --------------------------------------------------------------

        $j3xModel = $this->getModel();

        $Layout = Factory::getApplication()->input->get('layout');
        // ? layout to lower ?
        switch ($Layout) {
            case 'dbcopyj3xconfig':

                try {
                    $this->j3xConfigItems = $j3xModel->j3xConfigItems();
                    $this->j4xConfigItems = $rsgConfig->toArray();

                    // Configuration test lists: untouchedRsg2Config, untouchedJ3xConfig, 1:1 merged, assisted merges
                    [
                        $this->assistedJ3xItems,
                        $this->assistedJ4xItems,
                        $this->mergedItems,
                        $this->untouchedJ3xItems,
                        $this->untouchedJ4xItems,
                    ] = $j3xModel->MergeJ3xConfigTestLists($this->j3xConfigItems, $this->j4xConfigItems);
                } catch (\RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                break;

            case 'dbtransferj3xgalleries':
                try {
                    // gallery list
                    $this->j3x_galleriesSorted = $j3xModel->j3x_galleriesListSorted();
                    $this->j4x_galleries       = $j3xModel->j4x_galleriesList();

	                $this->j3x_galleryIdsMerged = $j3xModel->MergedJ3xIdsDbGalleries ($this->j3x_galleriesSorted, $this->j4x_galleries);
                }
                catch (\RuntimeException $e)
                {
                    $OutTxt = '';
                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                //--- reset transferred flag if necessary ------------------------------------------------------------

                $this->isDoCopyJ3xDbGalleries = !$rsgConfig->get('j3x_db_galleries_copied');

                if ($this->isDoCopyJ3xDbGalleries) {
                    // All J3x galleries transferred ?
                    if (count($this->j3x_galleryIdsMerged) == count($this->j3x_galleriesSorted)) {
                        $this->isDoCopyJ3xDbGalleries = false;

                        $isOk = ConfigRawModel::writeConfigParam('j3x_db_galleries_copied', true);
                    }
                } else {
                    // reset flag
                    // All J3x galleries transferred ?
                    if (count($this->j3x_galleryIdsMerged) != count($this->j3x_galleriesSorted)) {
                        $this->isDoCopyJ3xDbGalleries = true;

                        $isOk = ConfigRawModel::writeConfigParam('j3x_db_galleries_copied', false);
                    }
                }

                //--- message on is transferred ------------------------------------------------------

                // All galleries transferred (not to be copied)
                if (!$this->isDoCopyJ3xDbGalleries) {
                    $msg = "Successful moved <strong>all</strong> J3x DB gallery items";
                    $app = Factory::getApplication();
                    $app->enqueueMessage($msg, 'notice');
                }

                break;

            case 'dbtransferj3ximages':
                try {
//	                $this->j3x_images = [];
//	                $this->j4x_images = [];
//
//	                $this->j3x_imageIdsMerged = [];

                    //$this->j3x_galleriesSorted = $j3xModel->j3x_galleriesList_transferred_YN();
                    $this->j3x_galleriesSorted = $j3xModel->j3x_DbGalleryList_imagesTransferred_YN();

                    // request from DB data
                    $isDbImagesDoTransfer = $j3xModel->isImagesDoTransfer($this->j3x_galleriesSorted);

                    //--- reset transferred flag if necessary ------------------------------------------------------------

                    $this->isDoCopyJ3xDbImages = !$rsgConfig->get('j3x_db_images_copied');

                    if ($this->isDoCopyJ3xDbImages) {
                        // All J3x db images transferred ?
                        if (!$isDbImagesDoTransfer) {
                            $this->isDoCopyJ3xDbImages = false;

                            $isOk = ConfigRawModel::writeConfigParam('j3x_db_images_copied', true);
                        }
                    } else {
                        // reset flag
                        // All J3x db images transferred ?
                        if ($isDbImagesDoTransfer) {
                            $this->isDoCopyJ3xDbImages = true;

                            $isOk = ConfigRawModel::writeConfigParam('j3x_db_images_copied', false);
                        }
                    }

                    //--- message on is transferred ------------------------------------------------------

                    if (!$this->isDoCopyJ3xDbImages) {
                        $msg = "Successful moved <strong>all</strong> J3x DB image items";
                        $app = Factory::getApplication();
                        $app->enqueueMessage($msg, 'notice');
                    }

                    //--- Form --------------------------------------------------------------------

                    $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/forms/moveJ3xImages.xml';
                    $form    = Form::getInstance('movej3ximages', $xmlFile);

                    // Check for errors. Form must beloaded before
                    if ($errors = $this->get('Errors')) {
                        if (count($errors)) {
                            throw new GenericDataException(implode("\n", $errors), 500);
                        }
                    }

                    $this->form = $form;
                } catch (\RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                break;

				// gid ++
            case 'changeJ3xMenuLinks':

                // to be upgraded
                $this->j3xRsg2MenuLinks = $j3xModel->dbValidJ3xGidMenuItems();
                // to be degraded
                $this->j4xRsg2MenuLinks = $j3xModel->dbValidJ4xGidMenuItems();

                break;

            case 'lowerJ4xMenuLinks':

                // to be upgraded
                $this->j3xRsg2MenuLinks = $j3xModel->dbUpperCaseJ4xGidMenuItems();
                // to be degraded
                $this->j4xRsg2MenuLinks = $j3xModel->dbValidJ4xGidMenuItems();

                break;

            case 'movej3ximages':
                try {
                    $this->j3x_galleriesSorted = $j3xModel->j3x_GalleryList_imagesTransferred_YN();

                    // request from DB data
                    $isImagesDoTransfer = $j3xModel->isImagesDoTransfer($this->j3x_galleriesSorted);

                    //--- reset transferred flag if necessary ------------------------------------------------------------

                    $this->isDoCopyJ3xImages = !$rsgConfig->get('j3x_images_copied');

                    if ($this->isDoCopyJ3xImages) {
                        // All J3x db images transferred ?
                        if (!$isImagesDoTransfer) {
                            $this->isDoCopyJ3xImages = false;

                            $isOk = ConfigRawModel::writeConfigParam('j3x_images_copied', true);
                        }
                    } else {
                        // reset flag
                        // All J3x db images transferred ?
                        if ($isImagesDoTransfer) {
                            $this->isDoCopyJ3xImages = true;

                            $isOk = ConfigRawModel::writeConfigParam('j3x_images_copied', false);
                        }
                    }

                    //--- message on is transferred ------------------------------------------------------

                    if (!$this->isDoCopyJ3xImages) {
                        $msg = "Successful moved <strong>all</strong> J3x image files";
                        $app = Factory::getApplication();
                        $app->enqueueMessage($msg, 'notice');
                    }

                    $this->j3x_galleries = $this->j3x_galleriesSorted;
                    $this->j4x_galleries = [];

                    $this->j3x_transformGalleryIdsTo_j4x  = [];
                    $this->galleryIdsJ3x_dbImagesNotMoved = [];
                    $this->j3xNotMovedInfo                = [];

                    // J3x images exist
                    if ($this->isDoCopyJ3xImages) {
                        // $this->j3x_galleries = $j3xModel->j3x_galleriesList();
                        $this->j4x_galleries = $j3xModel->j4x_galleriesList();

//                        //$this->galleryIdsJ3xAsJ4x = $j3xModel->j3x_transformGalleryIdsTo_j4x();
//
                        $this->galleryIdsJ3x_NotMoved = $j3xModel->galleryIdsJ3x_ImagesNotMoved($this->j3x_galleries);
                        $this->galleryIdsJ3xAsJ4x     = $j3xModel->j3x_transformGalleryIdsTo_j4x($this->j3x_galleries);
//	                    $this->galleryIds4ImgsToBeMoved = $j3xModel->j3x_galleries4ImageMove($this->galleryIdsJ3xAsJ4x);
//
//
//	                    // finished by last call (move) ?  ToDo: call ajax check on empty list after move
//                        if(count($this->galleryIds4ImgsToBeMoved) == 0) {
//
//                            $this->isDoCopyJ3xImages = false;
//                        } else
//                        {
//	                        $this->isDoCopyJ3xImages = true;
//                        }
//                        $rsgConfig->set('j3x_images_copied', ! $this->isDoCopyJ3xImages);
//                        ConfigRawModel::writeConfigParam ('j3x_images_copied', ! $this->isDoCopyJ3xImages);
//

                    }

                    //--- Form --------------------------------------------------------------------

                    $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/forms/moveJ3xImages.xml';
                    $form    = Form::getInstance('movej3ximages', $xmlFile);

                    // Check for errors.
                    /* Must load form before */
                    if ($errors = $this->get('Errors')) {
                        if (count($errors)) {
                            throw new GenericDataException(implode("\n", $errors), 500);
                        }
                    }

                    $this->form = $form;
                } catch (\RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                break;

	        case 'resetupgradeflags':
				try
				{
					//--- Form --------------------------------------------------------------------

					$xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/forms/resetupgradeflags.xml';
					$form    = Form::getInstance('movej3ximages', $xmlFile);

					// Check for errors. Form must beloaded before
					if ($errors = $this->get('Errors'))
					{
						if (count($errors))
						{
							throw new GenericDataException(implode("\n", $errors), 500);
						}
					}

					$c1 = $rsgConfig->get('j3x_db_config_copied');
					$c2 = $rsgConfig->get('j3x_db_galleries_copied');
					$c3 = $rsgConfig->get('j3x_db_images_copied');
					$c4 = $rsgConfig->get('j3x_menu_gid_increased');
					$c5 = $rsgConfig->get('j3x_images_copied');
					$c6 = $rsgConfig->get('j3x_menu_gid_moved_to_id');

					$form->setValue('dbcopyj3xconfiguser', null, $rsgConfig->get('j3x_db_config_copied'));
					$form->setValue('dbtransferj3xgalleries', null, $rsgConfig->get('j3x_db_galleries_copied'));
					$form->setValue('dbtransferj3ximages', null, $rsgConfig->get('j3x_db_images_copied'));
					$form->setValue('changeJ3xMenuLinks', null, $rsgConfig->get('j3x_menu_gid_increased'));
					$form->setValue('movej3ximagesuser', null, $rsgConfig->get('j3x_images_copied'));
					$form->setValue('changeGidMenuLinks', null, $rsgConfig->get('j3x_menu_gid_moved_to_id'));

					$this->form = $form;

                } catch (\RuntimeException $e)
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
        $this->sidebar = Sidebar::render();

        $this->addToolbar($Layout);

        /**/

        parent::display($tpl);
        return;
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   5.1.0     */
    protected function addToolbar($Layout)
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        switch ($Layout) {
            case 'dbcopyj3xconfig':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
//					echo '<span style="color:red">'
//						. 'Tasks: <br>'
////		        		. '* <br>'
////				        . '* <br>'
////      				. '* <br>'
//						. '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG'), 'screwdriver');

				ToolBarHelper::custom('MaintenanceJ3x.copyJ3xConfig2J4xOptions', 'copy', '', 'COM_RSGALLERY2_COPY_COMPLETE_J3X_CONFIGURATION', false);
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');
                break;

            case 'dbcopyj3xconfiguser':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
//					echo '<span style="color:red">'
//						. 'Tasks: <br>'
//					. '* activate / hide button<br>'
////		        		. '* <br>'
////				        . '* <br>'
////      				. '* <br>'
//						. '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_COPY_J3X_CONFIG'), 'screwdriver');

				ToolBarHelper::custom('MaintenanceJ3x.copyJ3xConfig2J4xOptionsUser', 'copy', '', 'COM_RSGALLERY2_COPY_COMPLETE_J3X_CONFIGURATION', false);
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');
                break;

            case 'dbtransferj3xgalleries':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
                        . '* ! Db J3x gallery transfer: enable single transfers <br>'
                        . '* user should only see what is necessary: use debug / develop for others<br>'
                        . '* Left out: Button for copy single galleries -> no functions for adding , actual table is cleared on start so ...'
                        . '* !!! asset id !!! <br>'
                        . '* db variable "access". how to use ???<br>'
//				        . '* !!! Test resume of partly copied galleries !!! <br>'
//     	    			. '* <br>'
//				        . '* <br>'
//     	    			. '* <br>'
                        . '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES'), 'screwdriver');

				ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xGalleries2J4x', 'copy', '', 'COM_RSGALLERY2_DB_TRANSFER_ALL_J3X_GALLERIES', false);
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

                // actual on copy the table is cleared first. So it is not possible to do it with single entries
                // ToolBarHelper::custom ('MaintenanceJ3x.COM_RSGALLERY2_DB_TRANSFER_SELECTED_J3X_GALLERIES','undo','','COM_RSGALLERY2_COPY_SELECTED_J3X_GALLERIES', true);

                break;

            case 'dbtransferj3xgalleriesuser':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
//					echo '<span style="color:red">'
//						. 'Tasks: <br>'
////     	    			. '* <br>'
////				        . '* <br>'
////     	    			. '* <br>'
//						. '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES'), 'screwdriver');

				ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xGalleries2J4xUser', 'copy', '', 'COM_RSGALLERY2_DB_TRANSFER_ALL_J3X_GALLERIES', false);
				ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xGalleries2J4x', 'copy', '', 'COM_RSGALLERY2_DB_TRANSFER_J3X_GALLERIES_SINGLE', false);
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

                // actual on copy the table is cleared first. So it is not possible to do it with single entries
                // ToolBarHelper::custom ('MaintenanceJ3x.COM_RSGALLERY2_DB_TRANSFER_SELECTED_J3X_GALLERIES','undo','','COM_RSGALLERY2_COPY_SELECTED_J3X_GALLERIES', true);

                break;

            case 'dbtransferj3ximages':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
                        . '* Remove double code parts: See also images raw view -> import into views<br>'
                        . '* Remove J3x image list below<br>'
                        . '* Remove J4x gallery list below<br>'
//      				. '* <br>'
//		        		. '* <br>'
//				        . '* <br>'
//      				. '* <br>'
                        . '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES'), 'screwdriver');

				ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xImages2J4x', 'copy', '', 'COM_RSGALLERY2_DB_COPY_ALL_J3X_IMAGES', false);
				ToolBarHelper::custom('MaintenanceJ3x.revertCopyDbJ3xImages2J4xUser', 'delete', '', 'COM_RSGALLERY2_DB_REVERT_COPY_ALL_J3X_IMAGES', false);
                //ToolBarHelper::custom ('MaintenanceJ3x.copyDbSelectedJ3xImages2J4x','undo','','COM_RSGALLERY2_DB_COPY_SELECTED_J3X_IMAGES', true);
				ToolBarHelper::custom ('MaintenanceJ3x.copyDbImagesOfSelectedGalleries','undo','','COM_RSGALLERY2_DB_COPY_IMAGES_BY_J3X_GALLERY', true);
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

                break;

            case 'dbtransferj3ximagesuser':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
//					echo '<span style="color:red">'
//						. 'Tasks: <br>'
////      				. '* <br>'
////		        		. '* <br>'
////				        . '* <br>'
////      				. '* <br>'
//						. '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES'), 'screwdriver');

				ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xImages2J4x', 'copy', '', 'COM_RSGALLERY2_DB_COPY_ALL_J3X_IMAGES', false);
				ToolBarHelper::custom ('MaintenanceJ3x.dbtransferj3ximages','undo','','COM_RSGALLERY2_DB_TRANSFER_J3X_IMAGES_SINGLE', false);
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

                break;

            case 'changeJ3xMenuLinks':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
                        . '* separate link and parameter (parameter as hidden option)<br>'
//		        		. '* <br>'
//				        . '* <br>'
//      				. '* <br>'
                        . '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_INCREASE_MENU_GID'), 'screwdriver');

				ToolBarHelper::custom('MaintenanceJ3x.j3xUpgradeJ3xMenuLinks', 'add', '', 'COM_RSGALLERY2_INCREASE_MENU_GID', false);
                // ToDo: remove
				ToolBarHelper::custom('MaintenanceJ3x.j3xUpgradeJ3xMenuLinks', 'minus', '', 'COM_RSGALLERY2_DECREASE_MENU_GID', false);
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

                break;

            case 'lowerJ4xMenuLinks':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
                        . '* ?<br>'
//		        		. '* <br>'
//				        . '* <br>'
//      				. '* <br>'
                        . '</span><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_LOWER_MENU_LINKS'), 'screwdriver');

				ToolBarHelper::custom('MaintenanceJ3x.j3xLowerJ4xMenuLinks', 'arrow-down-4', '', 'COM_RSGALLERY2_LOWER_MENU_LINKS', false);
                // ToDo: remove
                //ToolBarHelper::custom('MaintenanceJ3x.j3xUpgradeJ3xMenuLinks', 'minus', '', 'COM_RSGALLERY2_DECREASE_MENU_GID', false);
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

                break;

            case 'movej3ximages':
            case 'movej3ximagesuser':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
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
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2')) {
            $toolbar->preferences('com_rsgallery2');
        }
    }

}

