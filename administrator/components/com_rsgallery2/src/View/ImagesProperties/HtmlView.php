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
        $ImageWidths = $rsgConfig->get('image_size');
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
                $toolbar = Toolbar::getInstance('toolbar');

                //--- back  -----------------------------------

                ToolbarHelper::back();

                // https://blog.astrid-guenther.de/joomla-aktionen-in-der-werkzeugleiste/

				//--- apply, save and close ... -----------------------------------

                $user = Factory::getApplication()->getIdentity();
				$saveGroup = $toolbar->dropdownButton('save-group')
                    ->text ('JTOOLBAR_CHANGE_STATUS')
                    ->icon('fa fa-ellipsis-h')
                    ->toggleSplit(false)
                    ->buttonClass('btn btn-action')
                    ->listCheck(true);

                $saveGroup->configure(
                    function (Toolbar $childBar) use ($user) {

                        //if ($user->authorise('core.create', 'com_menus.menu'))
                        // if ($user->authorise('core.admin'))
                        {
                            $childBar->save('imagesProperties.save_imagesProperties');
//                            $childBar->save('imagesProperties.save');

                            $childBar->apply('imagesProperties.apply_imagesProperties');
//                            $childBar->apply('images.apply');

                            $childBar->archive('imagesProperties.archive_imagesProperties' );
//                            $childBar->archive('images.archive' );

                            $childBar->trash('images.trash_imagesProperties' );

                            $childBar->delete('images.delete_imagesProperties' )
                                ->message('JGLOBAL_CONFIRM_DELETE');

                        }
                    }
                );

                //--- image rotate / flip -----------------------------------

				$dropdownButton = $toolbar->dropdownButton('rotate-group')
					->text('COM_RSGALLERY2_ROTATE')
//					->toggleSplit(true)
					->toggleSplit(false)
					->icon('fa fa-sync')
                    ->listCheck(true)
					->buttonClass('btn btn-action');

				$dropdownButton->configure(
						function (Toolbar $childBar)
						{
                            $childBar->standardButton('undo-2', 'COM_RSGALLERY2_ROTATE_LEFT','imagesProperties.rotate_images_left')->icon('fa fa-undo');
							$childBar->standardButton('redo-2', 'COM_RSGALLERY2_ROTATE_RIGHT','imagesProperties.rotate_images_right')->icon('fa fa-redo');
							$childBar->standardButton('backward-2', 'COM_RSGALLERY2_ROTATE_180','imagesProperties.rotate_images_180')->icon('fa fa-sync fa-rotate-180');
							$childBar->divider('      ');
							$childBar->standardButton('fa-arrows', 'COM_RSGALLERY2_FLIP_HORIZONTAL','imagesProperties.flip_images_horizontal')->icon('fa fa-arrows-alt-h');
							$childBar->standardButton('arrow-down-4', 'COM_RSGALLERY2_FLIP_VERTICAL','imagesProperties.flip_images_vertical')->icon('fa fa-arrows-alt-v');
						}
					);

                //--- cancel  -----------------------------------

                ToolBarHelper::cancel('images.cancel', 'JTOOLBAR_CLOSE');

                break;
		}

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}
	}



}

