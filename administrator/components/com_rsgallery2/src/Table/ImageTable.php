<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsImage2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;

/**
 * Image table
 *
 * @since __BUMP_VERSION__
 */
class ImageTable extends Table
{
	/**
	 * An array of key names to be json encoded in the bind function
	 *
	 * @var    array
	 * @since __BUMP_VERSION__
	 */
//	protected $_jsonEncode = ['params'];

	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since __BUMP_VERSION__
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_rsgallery2.image';

		parent::__construct('#__rsg2_images', 'id', $db);

        $this->access = (int) Factory::getApplication()->get('access');
	}

    /**
     * Overloaded bind function
     *
     * @param   array  $array   Named array
     * @param   mixed  $ignore  An optional array or space separated list of properties
     *          to ignore while binding.
     *
     * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
     *
     * @see     \JTable::bind
     * @since __BUMP_VERSION__
     */
    public function bind($array, $ignore = '')
    {
        if (isset($array['params']) && is_array($array['params']))
        {
            $registry = new Registry($array['params']);
            $array['params'] = (string) $registry;
        }

        return parent::bind($array, $ignore);
    }

    /**
     * Overloaded check method to ensure data integrity.
     *
     * @return  boolean  True on success.
     *
     * @since __BUMP_VERSION__
     * @throws  \UnexpectedValueException
     */
    public function check()
    {
        try {
            parent::check();
        } catch (\Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Check for valid name.
        if (trim($this->name) == '') {
            throw new \UnexpectedValueException(sprintf('The name is empty'));
        }

        //--- alias -------------------------------------------------------------

        // ToDo: aliase must be singular see below store ?
        if (empty($this->alias)) {
            $this->alias = $this->name;
        }

        $this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

        // just minuses -A use date
        if (trim(str_replace('-', '', $this->alias)) == '')
        {
            $this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
        }

        // Check the publish down date is not earlier than publish up.
        if (!empty($this->publish_down) && !empty($this->publish_up) && $this->publish_down < $this->publish_up)
        {
            throw new \UnexpectedValueException(sprintf('End publish date is before start publish date.'));
        }

        // Clean up description -- eliminate quotes and <> brackets

        if (!empty($this->description))
        {
            // Only process if not empty
            $bad_characters = array("\"", '<', '>');
            $this->description = StringHelper::str_ireplace($bad_characters, '', $this->description);
        }        else         {
            $this->description = '';
        }

        if (empty($this->params))
        {
            $this->params = '{}';
        }


        if (!(int) $this->checked_out_time)
        {
            $this->checked_out_time = null;
        }

        if (!(int) $this->publish_up)
        {
            $this->publish_up = null;
        }

        if (!(int) $this->publish_down)
        {
            $this->publish_down = null;
        }

        return true;
    }


    /**
	 * Stores a image reference.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since __BUMP_VERSION__
	 */
	public function store($updateNulls = false)
	{
        $date = Factory::getDate();
        $app  = Factory::getApplication();
        $user = $app->getIdentity();

        if ($this->id)
        {
            // Existing item
            $this->modified = $date->toSql();
            $this->modified_by = $user->get('id');
        }
        else {
            // New tag. A tag created and created_by field can be set by the user,
            // so we don't touch either of these if they are set.
            if (!(int)$this->created) {
                $this->created = $date->toSql();
            }

            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }

            if (!(int)$this->modified) {
                $this->modified = $this->created;
            }

            if (empty($this->modified_by)) {
                $this->modified_by = $this->created_user_id;
            }

            // Text must be preset
            if ($this->description == null) {
                $this->description = '';
            }
        }

        // Verify that the alias is unique
        $table = new static($this->getDbo());

        if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
        {
            $this->setError(Text::_('COM_RSGALLERY2_ERROR_UNIQUE_ALIAS'));

            return false;
        }

        return parent::store($updateNulls);
	}

	/**
	 * Deletes file images related to db image item
	 * before deleting db item
	 *
	 * @param null $pk Id of image item
	 *
	 * @return bool True if successful
	 *
	 * @since __BUMP_VERSION__
	 */
	public function delete($pk=null)
	{
		$IsDeleted = false;

		try
		{
			//$imgFileModel = JModelLegacy::getInstance('imageFile', 'RSGallery2Model');
			$imgFileModel = $this->getModel ('imageFile');

			$filename          = $this->name;
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
	}
	/**/
} // class
