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

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Input\Input;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;




/**
 * The Gallery Controller
 *
 * @since __BUMP_VERSION__
 */
class GalleryController extends FormController
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
     *
     * /**/

    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        if (empty($this->extension)) {
            $this->extension = $this->input->get('extension', 'com_rsgallery2');
        }
    }

    /**
     * Save edited gallery parameters and goto upload form
     *
     * @since version 4.3
     */
    public function save2upload()
    {
        // $msg     = '<strong>' . 'Save2Upload ' . ':</strong><br>';
        $msg = 'Save and goto upload: ';
        // fall back link
        $link    = 'index.php?option=com_rsgallery2';
        $IsSaved = false;

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                //  tells if successful
                $IsSaved = $this->save();

                if ($IsSaved) {
                    // ToDo: Prepare gallery ID and pre select it in upload form

                    $id   = $this->input->get('id', 0, 'int');
                    $link = 'index.php?option=com_rsgallery2&view=upload' . '&id=' . $id;

                    $msg     .= ' successful';
                    $msgType = 'notice';
                    $this->setRedirect($link, $msg, $msgType);
                } else {
                    $msg     .= ' failed';
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing rebuild: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $this->setRedirect($link, $msg, $msgType);

        return $IsSaved;
    }

//    /**
//	 * Method to check if you can add a new record.
//	 *
//	 * @param   array  $data  An array of input data.
//	 *
//	 * @return  boolean
//	 *
//	 * @since __BUMP_VERSION__
//	 */
//	protected function allowAdd($data = [])
//	{
//        $app  = Factory::getApplication();
//        $user = $app->getIdentity();
//
//		return ($user->authorise('core.create', $this->extension) || count($user->getAuthorisedGalleries($this->extension, 'core.create')));
//	}
//
//	/**
//	 * Method to check if you can edit a record.
//	 *
//	 * @param   array   $data  An array of input data.
//	 * @param   string  $key   The name of the key for the primary key.
//	 *
//	 * @return  boolean
//	 *
//	 * @since __BUMP_VERSION__
//	 */
//	protected function allowEdit($data = [], $key = 'parent_id')
//	{
//		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
//        $app  = Factory::getApplication();
//        $user = $app->getIdentity();
//
//		// Check "edit" permission on record asset (explicit or inherited)
//		if ($user->authorise('core.edit', $this->extension . '.gallery.' . $recordId))
//		{
//			return true;
//		}
//
//		// Check "edit own" permission on record asset (explicit or inherited)
//		if ($user->authorise('core.edit.own', $this->extension . '.gallery.' . $recordId))
//		{
//			// Need to do a lookup from the model to get the owner
//			$record = $this->getModel()->getItem($recordId);
//
//			if (empty($record))
//			{
//				return false;
//			}
//
//			$ownerId = $record->created_user_id;
//
//			// If the owner matches 'me' then do the test.
//			if ($ownerId == $user->id)
//			{
//				return true;
//			}
//		}
//
//		return false;
//	}
//
//	/**
//	 * Method to run batch operations.
//	 *
//	 * @param   object  $model  The model.
//	 *
//	 * @return  boolean  True if successful, false otherwise and internal error is set.
//	 *
//	 * @since __BUMP_VERSION__
//	 */
//	public function batch($model = null)
//	{
//	$this->checkToken();
//
//		// Set the model
//		/** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel $model */
//		$model = $this->getModel('Gallery');
//
//		// Preset the redirect
//		$this->setRedirect('index.php?option=com_rsgallery2&view=galleries&extension=' . $this->extension);
//
//		return parent::batch($model);
//	}

}
