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

	    if (empty ($rsgConfig)) {
            $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        }

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
						. '* <br>'
						. '* <br>'
						. '* <br>'
						//. '* <br>'
						//. '* <br>'
						//. '* <br>'
						. '</span><br><br>';
				}

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

