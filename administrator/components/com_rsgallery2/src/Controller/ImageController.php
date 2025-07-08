<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Input\Input;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Component\Menus\Administrator\Model\MenuModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel;

/**
 * The Image Controller
 *
 * @since __BUMP_VERSION__
 */
class ImageController extends FormController
{
    /**
     * The extension for which the galleries apply.
     *
     * @var    string
     * @since __BUMP_VERSION__
     */
    protected $extension;

    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input              $input    Input
     *
     * @since  __BUMP_VERSION__
     * @see    \JControllerLegacy
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        if (empty($this->extension)) {
            $this->extension = $this->input->get('extension', 'com_rsgallery2');
        }
    }

    /**
     * Remove an item.
     *
     * @return  void
     *
     * @since   1.6
     */
    /**
    public function delete()
    {
        // Check for request forgeries
        $this->checkToken();

        $user = $this->app->getIdentity();
        $cids = (array) $this->input->get('cid', [], 'array');

        if (count($cids) < 1)
        {
            $this->setMessage(Text::_('COM_RSGALLERY2_NO_IMAGE_SELECTED'), 'warning');
        }
        else
        {
            // Access checks.
            foreach ($cids as $i => $id)
            {
                if (!$user->authorise('core.delete', 'com_menus.menu.' . (int) $id))
                {
                    // Prune items that you can't change.
                    unset($cids[$i]);
                    $this->app->enqueueMessage(Text::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), 'error');
                }
            }

            if (count($cids) > 0)
            {
                // Get the model.
                /** @var \Joomla\Component\Menus\Administrator\Model\MenuModel $model *
                $model = $this->getModel();

                // Make sure the item ids are integers
                $cids = ArrayHelper::toInteger($cids);

                // Remove the items.
                if (!$model->delete($cids))
                {
                    $this->setMessage($model->getError(), 'error');
                }
                else
                {
                    // Delete image files physically

                    /** ToDo: following
                    $IsDeleted = false;

                    try
                    {

                        // ToDo: handle deleting of files like in menu (m-controller -> m-model -> m-table)

                        $filename          = $this->name;

                        //$imgFileModel = JModelLegacy::getInstance('imageFile', 'RSGallery2Model');
                        $imgFileModel = $this->getModel ('imageFile');

                        $IsFilesAreDeleted = $imgFileModel->deleteImgItemImages($filename);
                        if (! $IsFilesAreDeleted)
                        {
                            // Remove from database
                        }

                        $IsDeleted = parent::delete($pk);
                    }
                    catch (\RuntimeException $e)
                    {
                        $OutTxt = '';
                        $OutTxt .= 'Error executing image.table.delete: "' . $pk . '<br>';
                        $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                        $app = Factory::getApplication();
                        $app->enqueueMessage($OutTxt, 'error');
                    }

                    return $IsDeleted;
                    /**









                    $this->setMessage(Text::plural('COM_RSGALLERY2_N_ITEMS_DELETED', count($cids)));
                }
            }
        }

        $this->setRedirect('index.php?option=com_menus&view=menus');
    }
    /**/

    /**
     * rotate_image_left directs the master image and all dependent images to be turned left against the clock
     *
     * @since version 4.3
     */
    public function rotate_image_left()
    {
        // Done later: $this->checkToken();

        $msg = "rotate_left: " . '<br>';

        $direction = 90.000;
        $this->rotate_image($direction, $msg);
    }

    /**
     * rotate_image_right directs master image and all dependent images to be turned right with the clock
     *
     *
     * @since version 4.3
     */
    public function rotate_image_right()
    {
        // Done later: $this->checkToken();

        $msg = "rotate_right: " . '<br>';

        $direction = -90.000;
        $this->rotate_image($direction, $msg);
    }

    /**
     * rotate_image_180 directs the master image and all dependent images to be turned 180 degrees (upside down)
     *
     * @since version 4.3
     */
    public function rotate_image_180()
    {
        // Done later: $this->checkToken();

        $msg = "rotate_180: " . '<br>';

        $direction = 180.000;
        $this->rotate_image($direction, $msg);
    }

    /**
     * rotate_image directs the master image and all dependent images to be turned by given degrees
     *
     * @param   double  $direction  angle to turn the image
     * @param   string  $msg        start of message to be given to the user on setRedirect
     *
     *
     * @throws \Exception
     * @since version 4.3
     */
    public function rotate_image($direction = -90.000, $msg = '')
    {
        $this->checkToken();

        $msgType   = 'notice';
        $ImgCount  = 0;
        $ImgFailed = 0;

        try {
            $this->checkToken();

            // Access check
            $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
            if (!$canAdmin) {
                $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
                $msgType = 'warning';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
            } else {
                // standard input
                $input = Factory::getApplication()->input;

                $id = $input->get('id', 0, 'int');

                // toDo: create imageDb model
                $modelImages = $this->getModel('images');

                // Needed filename and gallery id
                //$imgFileDatas = $modelImages->ids2FileData($sids);
                //$formData = new Input($this->input->get('jform', '', 'array'));

                $modelFile = $this->getModel('imageFile');

                $fileName  = $input->get('name', '???', 'string');
                $galleryId = $input->get('gallery_id', -1, 'int');

                $IsSaved = $modelFile->rotate_image($id, $fileName, $galleryId, $direction);

                if ($IsSaved) {
                    $ImgCount++;
                } else {
                    $ImgFailed++;
                }

                // $msg '... successful assigned .... images ...
                if ($ImgCount) {
                    $msg_ok = ' Successful rotated ' . $ImgCount . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_ok, 'notice');
                }
                if ($ImgFailed) {
                    $msg_bad = ' Failed on rotation of ' . $ImgFailed . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_bad, 'error');
                }

                // not all images were rotated
                if ($ImgCount < 1) {
                    $msgType = 'warning';
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing rotate_image: "' . $direction . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        $link = 'index.php?option=com_rsgallery2&view=image&task=image.edit&id=' . $id;
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * flip_image_horizontal directs the master image and all dependent images to be flipped horizontal (left <-> right)
     *
     * @since version 4.3
     */
    public function flip_image_horizontal()
    {
        $this->checkToken();

        $msg = "flip_image_horizontal: " . '<br>';

        $flipMode = IMG_FLIP_HORIZONTAL; //  IMG_FLIP_VERTICAL,  IMG_FLIP_BOTH
        $this->flip_image($flipMode, $msg);
    }

    /**
     * flip_image_vertical directs the master image and all dependent images to be flipped horizontal (top <-> bottom)
     *
     * @since version 4.3
     */
    public function flip_image_vertical()
    {
        // Done later: $this->checkToken();

        $msg = "flip_image_vertical: " . '<br>';

        $flipMode = IMG_FLIP_VERTICAL;
        $this->flip_image($flipMode, $msg);
    }

    /**
     * flip_image_both directs the master image and all dependent images to be flipped horizontal and vertical
     *
     *
     * @since version 4.3
     */
    public function flip_image_both()
    {
        // Done later: $this->checkToken();

        $msg = "flip_image_both: " . '<br>';

        $flipMode = IMG_FLIP_BOTH;
        $this->flip_image($flipMode, $msg);
    }

    /**
     * flip_image directs the master image and all dependent images to be flipped
     * according to mode horizontal, vertical or both
     *
     * @param   int     $flipMode  mode horizontal, vertical or both
     * @param   string  $msg       start of message to be given to the user on setRedirect
     *
     * @throws \Exception
     * @since version 4.3
     */
    public function flip_image($flipMode = 0, $msg = '')
    {
        $this->checkToken();

        $msgType   = 'notice';
        $ImgCount  = 0;
        $ImgFailed = 0;

        try {
            // Access check
            $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
            if (!$canAdmin) {
                $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
                $msgType = 'warning';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
            } else {
                // standard input
                $id = $this->input->get('id', 0, 'int');

                // toDo: create imageDb model
                $modelImages = $this->getModel('images');

                // Needed filename and gallery id
                //$imgFileDatas = $modelImages->ids2FileData($sids);
                // $formData = new Input($this->input->get('jform', '', 'array'));

                $modelFile = $this->getModel('imageFile');

                $fileName  = $this->input->get('name', '???', 'string');
                $galleryId = $this->input->get('gallery_id', -1, 'int');

                $IsSaved = $modelFile->flip_image($id, $fileName, $galleryId, $flipMode);

                if ($IsSaved) {
                    $ImgCount++;
                } else {
                    $ImgFailed++;
                }

                // $msg '... successful assigned .... images ...
                if ($ImgCount) {
                    $msg_ok = ' Successful flipped ' . $ImgCount . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_ok, 'notice');
                }
                if ($ImgFailed) {
                    $msg_bad = ' Failed on flipping of ' . $ImgFailed . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_bad, 'error');
                }

                // not all images were rotated
                if ($ImgCount < 1) {
                    $msgType = 'warning';
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing flip_image: "' . $flipMode . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        $link = 'index.php?option=com_rsgallery2&view=image&task=image.edit&id=' . $id;
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Method to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since __BUMP_VERSION__
     *
	protected function allowAdd($data = [])
	{
        $app  = Factory::getApplication();
        $user = $app->getIdentity();

		return ($user->authorise('core.create', $this->extension) || count($user->getAuthorisedGalleries($this->extension, 'core.create')));
	}
	/**/

    /**
     * Method to check if you can edit a record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since __BUMP_VERSION__
     *
	protected function allowEdit($data = [], $key = 'parent_id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $app  = Factory::getApplication();
        $user = $app->getIdentity();

		// Check "edit" permission on record asset (explicit or inherited)
		if ($user->authorise('core.edit', $this->extension . '.gallery.' . $recordId))
		{
			return true;
		}

		// Check "edit own" permission on record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', $this->extension . '.gallery.' . $recordId))
		{
			// Need to do a lookup from the model to get the owner
			$record = $this->getModel()->getItem($recordId);

			if (empty($record))
			{
				return false;
			}

			$ownerId = $record->created_user_id;

			// If the owner matches 'me' then do the test.
			if ($ownerId == $user->id)
			{
				return true;
			}
		}

		return false;
	}
	/**/

    /**
     * Method to run batch operations.
     *
     * @param   object  $model  The model.
     *
     * @return  boolean  True if successful, false otherwise and internal error is set.
     *
     * @since __BUMP_VERSION__
     *
	public function batch($model = null)
	{
	$this->checkToken();

		// Set the model
		/** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel $model *
		$model = $this->getModel('Gallery');

		// Preset the redirect
		$this->setRedirect('index.php?option=com_rsgallery2&view=galleries&extension=' . $this->extension);

		return parent::batch($model);
	}
	/**/

    /**
     * Gets the URL arguments to append to an item redirect.
     *
     * @param   integer  $recordId  The primary key id for the item.
     * @param   string   $urlVar    The name of the URL variable for the id.
     *
     * @return  string  The arguments to append to the redirect URL.
     *
     * @since __BUMP_VERSION__
     *
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$append = parent::getRedirectToItemAppend($recordId);
		$append .= '&extension=' . $this->extension;

		return $append;
	}
	/**/

    /**
     * Gets the URL arguments to append to a list redirect.
     *
     * @return  string  The arguments to append to the redirect URL.
     *
     * @since __BUMP_VERSION__
     *
	protected function getRedirectToListAppend()
	{
		$append = parent::getRedirectToListAppend();
		$append .= '&extension=' . $this->extension;

		return $append;
	}
	/**/

    /**
     * Function that allows child controller access to model data after the data has been saved.
     *
     * @param   BaseDatabaseModel  $model      The data model object.
     * @param   array                                    $validData  The validated data.
     *
     * @return  void
     *
     * @since __BUMP_VERSION__
     *
	protected function postSaveHook(BaseDatabaseModel $model, $validData = [])
	{
		$item = $model->getItem();

		if (isset($item->params) && is_array($item->params))
		{
			$registry = new Registry($item->params);
			$item->params = (string) $registry;
		}

		if (isset($item->metadata) && is_array($item->metadata))
		{
			$registry = new Registry($item->metadata);
			$item->metadata = (string) $registry;
		}
	}
	/**/
}
