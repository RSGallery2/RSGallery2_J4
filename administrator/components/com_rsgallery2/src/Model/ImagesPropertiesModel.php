<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright (c) 2016-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * RSGallery2 Component Images Model
 *
 * @since __BUMP_VERSION__
 */
class ImagesPropertiesModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * @param   MVCFactoryInterface  $factory  The factory.
	 *
	 * @see     \JControllerLegacy
	 * @since __BUMP_VERSION__
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null)
	{
/**
		//  which fields are needed for filter function
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'name', 'a.name',
				'gallery_id', 'a.gallery_id',

                'published', 'a.published',

                'created', 'a.created',
                'created_by', 'a.created_by',

                'modified', 'a.modified',
                'modified_by', 'a.modified_by',

                'ordering', 'a.ordering',

                'hits', 'a.hits',
                'rating', 'a.rating',
                'votes', 'a.votes',
                'comments', 'a.comments',
				'tag',
				'gallery_name'
			);
		}

		if (Associations::isEnabled())
		{
			$config['filter_fields'][] = 'association';
		}
		/**/

		parent::__construct($config, $factory);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function populateState($ordering = 'a.id', $direction = 'desc')
	{
		/**
		// $app = Factory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}


		//$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');
		//// Adjust the context to support forced languages.
		//if ($forcedLanguage)
		//{
		//	$this->context .= '.' . $forcedLanguage;
		//}

		$extension = $app->getUserStateFromRequest($this->context . '.filter.extension', 'extension', 'com_rsgallery2', 'cmd');
		$this->setState('filter.extension', $extension);
		$parts = explode('.', $extension);

		// Extract the component name
		$this->setState('filter.component', $parts[0]);

		// Extract the optional section name
		$this->setState('filter.section', (count($parts) > 1) ? $parts[1] : null);

		$search   = $this->getUserStateFromRequest($this->context . '.search', 'filter_search');
		$this->setState('filter.search', $search);

		$gallery_id = $this->getUserStateFromRequest($this->context . '.filter.gallery_id', 'filter_gallery_id');
		$this->setState('filter.gallery_id', $gallery_id);
		/**/

		// List state information.
		parent::populateState($ordering, $direction);

	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
//		$id .= ':' . $this->getState('filter.extension');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.gallery_id');
//		$id .= ':' . $this->getState('filter.published');
//		$id .= ':' . $this->getState('filter.access');
//		$id .= ':' . $this->getState('filter.language');
//		$id .= ':' . $this->getState('filter.level');
		$id .= ':' . $this->getState('filter.tag');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get a database query to list images.
	 *
	 * @return  \DatabaseQuery object.
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		// $db = $this->getContainer()->get(DatabaseInterface::class);
        $db    = $this->getDatabase();
		$query = $db->getQuery(true);

        $app  = Factory::getApplication();
        $user = $app->getIdentity();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				/**/
				'list.select',
				'a.id, '
				. 'a.name, '
				. 'a.alias, '
				. 'a.description, '
                . 'a.note, '
				. 'a.gallery_id, '
                . 'a.title, '

				. 'a.params, '
				. 'a.published, '

                . 'a.hits, '
				. 'a.rating, '
				. 'a.votes, '
				. 'a.comments, '

 //               . 'a.publish_up,'
 //               . 'a.publish_down,'

                . 'a.checked_out, '
				. 'a.checked_out_time, '
				. 'a.created, '
				. 'a.created_by, '
				. 'a.created_by_alias, '
				. 'a.modified, '
				. 'a.modified_by, '

				. 'a.ordering, '
                . 'a.approved, '
                . 'a.asset_id, '
				. 'a.access, '
				. 'a.use_j3x_location '
			)
		);
		$query->from('#__rsg2_images as a');

		/* parent gallery name */
		$query->select('gal.name as gallery_name')
			->join('LEFT', '#__rsg2_galleries AS gal ON gal.id = a.gallery_id'
			);

		//// Join over the language
		//$query->select('l.title AS language_title, l.image AS language_image')
		//	->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		/**
		// Join over the associations.
		$assoc = $this->getAssoc();

		if ($assoc)
		{
			$query->select('COUNT(asso2.id)>1 as association')
				->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_rsgallery2.item'))
				->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
				->group('a.id, l.title, uc.name, ag.title, ua.name');
		}
		/**/

        /**
		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('a.level <= ' . (int) $level);
		}
        /**/

		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}

        // Filter on the gallery Id.
        if ($gallery_id = $this->getState('filter.gallery_id'))
        {
            $query->where('a.gallery_id = ' . $db->quote($gallery_id));
        }

        // Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published IN (0, 1))');
		}

        // Filter by search in name and others
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'a.name LIKE ' . $search
				. ' OR a.title LIKE ' . $search
				. ' OR a.alias LIKE ' . $search
				. ' OR a.description LIKE ' . $search
				. ' OR gal.name LIKE ' . $search
				. ' OR a.note LIKE ' . $search
				. ' OR a.created LIKE ' . $search
				. ' OR a.modified LIKE ' . $search
			);
		}

		// cid's in URL ?
		$input = Factory::getApplication()->input;
		$cids = $input->get('cid', -1, 'int');

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo 'cids: "' . json_encode($cids) . '"<br>';
		}

		// use given cids or all images
		if (is_array ($cids))
		{
			$strCids = implode(", ", $cids);
			$query->where('a.id IN (' . $strCids . ')');
		}
		else
		{
			if (empty ($cids))
			{
				//$query->where('a.id = ' .  (int) $cids . '');
				$query->where('a.id = 0');
			}
		}






		/**
		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = ' . $db->quote($language));
		}
		/**/

		// Filter by a single tag.
		/**
		$tagId = $this->getState('filter.tag');

		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote($extension . '.category')
				);
		}
		/**/

		// Add the list ordering clause

/**
		// changes need changes above too -> populateState
		$orderCol  = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'desc');

		if ($orderCol == 'a.ordering' || $orderCol == 'ordering')
		{
			$orderCol = 'a.gallery_id ' . $orderDirn . ', a.ordering';
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));
/**/

		$listOrdering = $this->getState('list.ordering', 'a.id');
		$listDirn = $db->escape($this->getState('list.direction', 'DESC'));

		if ($listOrdering == 'a.access')
		{
			$query->order('a.access ' . $listDirn . ', a.id ' . $listDirn);
		}
		else
		{
			$query->order($db->escape($listOrdering) . ' ' . $listDirn);
		}

		/**
		// Group by on Images for \JOIN with component tables to count items
		$query->group(
			/**  * /
			'a.id, '
			. 'a.name, '
			. 'a.alias, '
			. 'a.description, '
			. 'a.gallery_id, '
			. 'a.title, '

			. 'a.note, '
			. 'a.params, '
			. 'a.published, '
//            . 'a.published_up, '
//            . 'a.published_down, '

			. 'a.hits, '
			. 'a.rating, '
			. 'a.votes, '
			. 'a.comments, '

			. 'a.checked_out, '
			. 'a.checked_out_time, '
			. 'a.created, '
			. 'a.created_by, '
			. 'a.created_by_alias, '
			. 'a.modified, '
			. 'a.modified_by, '

			. 'a.ordering, '
            . 'a.approved, '
			. 'a.asset_id, '
			. 'a.access, '
			. 'a.use_j3x_location '
			/**  * /
		);
		/**/

		return $query;
	}


} // class
