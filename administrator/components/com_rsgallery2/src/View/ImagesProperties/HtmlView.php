<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\ImagesProperties;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Button\customButton;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;

/**
 * View class for a list of rsgallery2.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $pagination;
	protected $state;

    protected $ImagePath;
    protected $DisplayImgWidth;

	protected $form;
    protected $editor;
    protected $editorParams;

    protected $isDebugBackend;
    protected $isDevelop;


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
	    global $rsgConfig;

        //--- config --------------------------------------------------------------------

        if (empty ($rsgConfig)) {
                $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        }
        //$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        $this->isDebugBackend = $rsgConfig->get('isDebugBackend');
        $this->isDevelop = $rsgConfig->get('isDevelop');


        $this->items = $this->get('Items');

		// paths to image (galleryid
        $this->ImagePath = new ImagePaths ();

        // size of display image
        $ImageWidths = $rsgConfig->get('image_width');
        $exploded = explode(',', $ImageWidths);
        $this->DisplayImgWidth = $exploded[0];


        $editor = Factory::getApplication()->get('editor');
        $this->editor = Editor::getInstance($editor);
        // SET EDITOR PARAMS
        $this->editorParams = array(
            'smilies'=> '1' ,
            'style'  => '1' ,
            'layer'  => '0' ,
            'table'  => '0' ,
            'clear_entities'=>'0');

        $this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

		$Layout = $this->getLayout();

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
			case 'yyyRawView':


				break;

			case 'yyyRawEdit':
				break;

			default:
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. 'Tasks: <br>'
						. '* modal on image click <br>'
						. '* <br>'
						. '* <br>'
						//. '* <br>'
						//. '* <br>'
						//. '* <br>'
						. '</span><br><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_ADD_IMAGES_PROPERTIES', 'image'));

				ToolbarHelper::back();

				ToolBarHelper::apply('imagesProperties.apply_imagesProperties');

//				ToolbarHelper::assign();

				ToolBarHelper::save('imagesProperties.save_imagesProperties');
//				ToolBarHelper::cancel('imagesProperties.cancel_imagesProperties');
				ToolBarHelper::cancel('images.cancel', 'JTOOLBAR_CLOSE');

				ToolbarHelper::archiveList('images.trash');
				ToolbarHelper::trash('images.trash');
//				ToolBarHelper::deleteList('', 'ImagesProperties.delete_imagesProperties', 'JTOOLBAR_DELETE');

//				ToolbarHelper::saveGroup (
//					[
//						['apply', 'imagesProperties.apply_imagesProperties'],
//						['save', 'imagesProperties.save_imagesProperties'],
//						['cancel', 'images.cancel'],
//						['archiveList', 'images.trash'],
//					],
//					'btn-success'
//				);
//

				$toolbar = Toolbar::getInstance('toolbar');

//				$formGroup = $bar->dropdownButton('form-group');
//				$formGroup->getName();
//				$formGroup->configure();
//
//				$bar->publish('contacts.publish')->listCheck(true);

				$dropdownButton = $toolbar->dropdownButton('rotate-group')
					->text('*Rotate')
//					->toggleSplit(true)
					->toggleSplit(false)
//					->icon('fa fa-sync')
					->icon('fas fa-image fa-spin')
//					->icon('fas fa-sync fa-spin')
					->buttonClass('btn btn-success btn-sm');

				$dropdownButton->configure(
						function (Toolbar $childBar)
						{
							// $childBar->standardButton('remove', 'Button 3');
//							$childBar->customButton('imagesProperties.rotate_images_left', 'undo-2', '', 'COM_RSGALLERY2_ROTATE_LEFT', true);
                            $childBar->standardButton('undo-2', 'COM_RSGALLERY2_ROTATE_LEFT','imagesProperties.rotate_images_left');
//							$childBar->customButton('imagesProperties.rotate_images_right', 'redo-2', '', 'COM_RSGALLERY2_ROTATE_RIGHT', true);
//							$childBar->customButton('imagesProperties.rotate_images_180', 'backward-2', '', 'COM_RSGALLERY2_ROTATE_180', true);
//							$childBar->divider();
//							$childBar->customButton('imagesProperties.flip_images_horizontal', 'arrow-right-4', '', 'COM_RSGALLERY2_FLIP_HORIZONTAL', true);
//							$childBar->customButton('imagesProperties.flip_images_vertical', 'arrow-down-4', '', 'COM_RSGALLERY2_FLIP_VERTICAL', true);
//							$childBar->customButton('rotate_left_x', 'COM_RSGALLERY2_ROTATE_LEFT', 'imagesProperties.rotate_images_left');
//							$childBar->customButton('rotate_right_x', 'COM_RSGALLERY2_ROTATE_RIGHT','imagesProperties.rotate_images_right');
//							$childBar->customButton('rotate_180_x', 'COM_RSGALLERY2_ROTATE_180', 'imagesProperties.rotate_images_180');
//							$childBar->divider();
//							$childBar->customButton('flip_horizontal_x', 'COM_RSGALLERY2_FLIP_HORIZONTAL', 'imagesProperties.flip_images_horizontal');
//							//$childBar->customButton('flip_vertical_x', 'COM_RSGALLERY2_FLIP_VERTICAL', 'imagesProperties.flip_images_vertical');
//							$childBar->customButton('flip_vertical_x');


							//$childBar->divider('HEADER');
							//$childBar->divider();
							$childBar->standardButton('folder', 'COM_RSGALLERY2_ROTATE_RIGHT');

							$childBar->divider();
							$childBar->standardButton('trash', 'Button 5');
							$childBar->standardButton('question', 'Button 6');
						}
					);

				//--- turn image -> flip / rotate -------------------------------

				// ToolBarHelper;::spacer('50px');
				ToolBarHelper::custom('', '', '', '   ', false);
//				ToolbarHelper::divider();
				ToolbarHelper::spacer(50);



				ToolBarHelper::custom('imagesProperties.rotate_images_left', 'undo-2', '', 'COM_RSGALLERY2_ROTATE_LEFT', true);
				ToolBarHelper::custom('imagesProperties.rotate_images_right', 'redo-2', '', 'COM_RSGALLERY2_ROTATE_RIGHT', true);
				ToolBarHelper::custom('imagesProperties.rotate_images_180', 'backward-2', '', 'COM_RSGALLERY2_ROTATE_180', true);
				ToolBarHelper::custom('imagesProperties.flip_images_horizontal', 'arrow-right-4', '', 'COM_RSGALLERY2_FLIP_HORIZONTAL', true);
				ToolBarHelper::custom('imagesProperties.flip_images_vertical', 'arrow-down-4', '', 'COM_RSGALLERY2_FLIP_VERTICAL', true);


// ToDO: test drop down ...

//                ToolbarHelper::divider();
//                $toolbar->appendButton('Popup', 'bars', 'COM_FINDER_STATISTICS', 'index.php?option=com_finder&view=statistics&tmpl=component', 550, 350, '', '', '', Text::_('COM_FINDER_STATISTICS_TITLE'));
//                ToolbarHelper::divider();

//                $dropdown = $toolbar->dropdownButton('status-group')
//                    ->text('JTOOLBAR_CHANGE_STATUS')
//                    ->toggleSplit(false)
//                    ->icon('fa fa-ellipsis-h')
//                    ->buttonClass('btn btn-action')
//                    ->listCheck(true);
//
//                $childBar = $dropdown->getChildToolbar();
//                $childBar->popupButton('test')
//                    ->text('JTOOLBAR_BATCH')
//                    ->selector('collapseModal')
//                    ->listCheck(true);
//

////                // $childBar->
//                $toolbar->popupButton()
//                ->url(Route::_('index.php?option=com_banners&view=download&tmpl=component'))
//                ->text('JTOOLBAR_EXPORT')
//                ->selector('downloadModal')
//                ->icon('icon-download')
//                ->footer('<button class="btn btn-secondary" data-bs-dismiss="modal" type="button"'
//                    . ' onclick="window.parent.Joomla.Modal.getCurrent().close();">'
//                    . Text::_('COM_BANNERS_CANCEL') . '</button>'
//                    . '<button class="btn btn-success" type="button"'
//                    . ' onclick="Joomla.iframeButtonClick({iframeSelector: \'#downloadModal\', buttonSelector: \'#exportBtn\'})">'
//                    . Text::_('COM_BANNERS_TRACKS_EXPORT') . '</button>'
//                );



                break;
		}

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}
	}



}

