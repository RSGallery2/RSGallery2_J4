<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsImage2
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\RsImage2\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Registry\Registry;
 
/**
 * Image table
 *
 * @since  1.6
 */
class ImageTable extends Table
{
	/**
	 * Indicates that columns fully support the NULL value in the database
	 *
	 * @var    boolean
	 * @since  4.0.0
	 */
	protected $_supportNullValue = true;

	/**
	 * An array of key names to be json encoded in the bind function
	 *
	 * @var    array
	 * @since  3.3
	 */
//	protected $_jsonEncode = ['params'];

	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_rsgallery2.image';

		parent::__construct('#__rsg2_images', 'id', $db);
	}

	/**
	 * Stores a image reference.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   1.0
	 */
	public function store($updateNulls = false)
	{
		// Transform the params field
		if (is_array($this->params))
		{
			$registry = new Registry($this->params);
			$this->params = (string) $registry;
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
	 * @since 4.3.2
	 *
	public function delete($pk=null)
	{
		$IsDeleted = false;

		try
		{
			$imgFileModel = JModelLegacy::getInstance('imageFile', 'RSGallery2Model');

			$filename          = $this->name;
			$IsFilesAreDeleted = $imgFileModel->deleteImgItemImages($filename);
			if ($IsFilesAreDeleted)
			{
				// Remove from database
				$IsDeleted = parent::delete($pk);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing image.table.delete: "' . $pk . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsDeleted;
	}
	/**/

} // class
