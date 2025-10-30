<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Image;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;



//use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;

/**
 * HTML View class for image edit
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The Form object
     *
     * @var  Form
     */
    protected $form;
    protected $imgUrl;

    /**
     * The active item
     *
     * @var  object
     */
    protected $item;

    /**
     * The model state
     *
     * @var  \stdClass
     */
    protected $state;

    /**
     * Flag if an association exists
     *
     * @var  boolean
     */
    protected $assoc;

    /**
     * The actions the user is authorised to perform
     *
     * @var  \stdClass
     */
    protected $canDo;

    /**
     * Is there a content type associated with this gallery aias
     *
     * @var    boolean
     * @since  5.1.0     */
    protected $checkTags = false;

    protected $isDebugBackend;
    protected $isDevelop;

    /**
     * Display the view.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        //--- config --------------------------------------------------------------------

        $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        //$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        $this->isDebugBackend = $rsgConfig->get('isDebugBackend');
        $this->isDevelop      = $rsgConfig->get('isDevelop');

        //--- Form --------------------------------------------------------------------

        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        if (!$this->item->use_j3x_location) {
            $ImagePath = new ImagePathsModel ();
            $ImagePath->setPaths_URIs_byGalleryId($this->item->gallery_id);
            $this->imgUrl = $ImagePath->getDisplayUrl($this->item->name);
        } else {
            $ImagePathJ3x = new ImagePathsJ3xModel ();
            $this->imgUrl = $ImagePathJ3x->getDisplayUrl($this->item->name);
        }

        //$section = $this->state->get('gallery.section') ? $this->state->get('gallery.section') . '.' : '';
        //$this->canDo = ContentHelper::getActions($this->state->get('gallery.component'), $section . 'gallery', $this->item->id);
        $this->canDo = ContentHelper::getActions('com_rsgallery2', 'gallery', $this->item->id);
        $this->assoc = $this->get('Assoc');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        // Check if we have a content type for this alias
        if (!empty(TagsHelper::getTypes('objectList', [$this->state->get('gallery.extension') . '.gallery'], true))) {
            $this->checkTags = true;
        }

        /**
		// If we are forcing a language in modal (used for associations).
		if ($this->getLayout() === 'modal' && $forcedLanguage = Factory::getApplication()->input->get('forcedLanguage', '', 'cmd'))
		{
			// Set the language field to the forcedLanguage and disable changing it.
			$this->form->setValue('language', null, $forcedLanguage);
			$this->form->setFieldAttribute('language', 'readonly', 'true');

			// Only allow to select galleries with All language or with the forced language.
			$this->form->setFieldAttribute('parent_id', 'language', '*,' . $forcedLanguage);

			// Only allow to select tags with All language or with the forced language.
			$this->form->setFieldAttribute('tags', 'language', '*,' . $forcedLanguage);
		}
		/**/

        // different toolbar on different layouts
        $Layout = Factory::getApplication()->input->get('layout');
        $this->addToolbar($Layout);

        Factory::getApplication()->input->set('hidemainmenu', true);

        parent::display($tpl);
        return;
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   5.1.0     */
    protected function addToolbar($Layout = 'default')
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        // on develop show open tasks if existing
        if (!empty ($this->isDevelop)) {
            echo '<span style="color:red">'
                . 'Tasks: <br>'
                . '* published_up, published_down: preset on first save with published <br>'
                . '* <br>'
                . '* description to each input parameter "_DESC"<br>'
                . '* options: Values not defined and add params<br>'
                . '* options: show user, layout ... see article<br>'
                . '* Save and goto prev/next<br>'
                . '* test published_up, published_down: on Web page <br>'
                . '* Save and next, save and previous: move inside gallery<br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
                . '</span><br><br>';
        }

        switch ($Layout) {
            case 'raw_edit':
                ToolBarHelper::title(Text::_('COM_RSGALLERY2_RAW_EDIT_IMAGE', 'image'));

                //--- apply, save and close ... -----------------------------------

                // ...

                break;

            case 'edit':
            default:
                ToolBarHelper::title(Text::_('COM_RSGALLERY2_EDIT_IMAGE', 'image'));

                //--- apply, save and close ... -----------------------------------

                ToolBarHelper::apply('image.apply');
                ToolBarHelper::save('image.save');

                $toolbar = Toolbar::getInstance('toolbar');

                //--- image rotate / flip -----------------------------------

                $dropdownButton = $toolbar
                    ->dropdownButton('rotate-group')
                    ->text('COM_RSGALLERY2_ROTATE')
////                    ->toggleSplit(true)
//                    ->toggleSplit(false)
                    ->icon('fa fa-sync')
//                    ->listCheck(false)
                    ->buttonClass('btn btn-action');

                $dropdownButton->configure(
                    function (Toolbar $childBar) {
                        $childBar->standardButton('undo-2', 'COM_RSGALLERY2_ROTATE_LEFT', 'image.rotate_image_left')->icon('fa fa-undo');
                        $childBar->standardButton('redo-2', 'COM_RSGALLERY2_ROTATE_RIGHT', 'images.rotate_image_right')->icon('fa fa-redo');
                        $childBar->standardButton('backward-2', 'COM_RSGALLERY2_ROTATE_180', 'images.rotate_image_180')->icon('fa fa-sync fa-rotate-180');
                        $childBar->divider('      ');
                        $childBar->standardButton('fa-arrows', 'COM_RSGALLERY2_FLIP_HORIZONTAL', 'image.flip_image_horizontal')->icon('fa fa-arrows-alt-h');
                        $childBar->standardButton('arrow-down-4', 'COM_RSGALLERY2_FLIP_VERTICAL', 'image.flip_image_vertical')->icon('fa fa-arrows-alt-v');
                    }
                );

                //--- cancel  -----------------------------------

                //ToolBarHelper::save2new('image.save2new');
                if (empty($this->item->id)) {
                    ToolBarHelper::cancel('image.cancel', 'JTOOLBAR_CLOSE');
                } else {
                    ToolBarHelper::cancel('image.cancel', 'JTOOLBAR_CLOSE');
                }

//				ToolBarHelper::custom ('gallery.save2upload','upload','','COM_RSGALLERY2_SAVE_AND_GOTO_UPLOAD', false);

                break;
        }

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2')) {
            $toolbar->preferences('com_rsgallery2');
        }
    }

}

