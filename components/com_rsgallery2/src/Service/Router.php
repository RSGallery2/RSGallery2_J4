<?php

/**
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Service;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Categories\CategoryInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;

/**
 * Routing class of com_rsgallery2
 *
 * @since  3.3
 */
class Router extends RouterView
{
	/**
	 * Flag to remove IDs
	 *
	 * @var    boolean
	 */
	protected $noIDs = false;

	/**
	 * The db
	 *
	 * @var DatabaseInterface
	 *
	 * @since  __BUMP_VERSION__
	 */
	private $db;

/**
	 * Content Component router constructor
	 *
	 * @param   SiteApplication           $app              The application object
	 * @param   AbstractMenu              $menu             The menu object to work with
	 * @param   CategoryFactoryInterface  $categoryFactory  The category object
	 * @param   DatabaseInterface         $db               The database object
	 */
	public function __construct(SiteApplication $app, AbstractMenu $menu, CategoryFactoryInterface $categoryFactory, DatabaseInterface $db)
	{
        /**
		$this->categoryFactory = $categoryFactory;
		$this->db              = $db;
		$params = ComponentHelper::getParams('com_foos');
		$this->noIDs = (bool) $params->get('sef_ids');
		$categories = new RouterViewConfiguration('categories');
		$categories->setKey('id');
		$this->registerView($categories);
		$category = new RouterViewConfiguration('category');
		$category->setKey('id')->setParent($categories, 'catid')->setNestable();
		$this->registerView($category);
		$foo = new RouterViewConfiguration('foo');
		$foo->setKey('id')->setParent($category, 'catid');
		$this->registerView($foo);
		$this->registerView(new RouterViewConfiguration('featured'));
		$form = new RouterViewConfiguration('form');
		$form->setKey('id');
		$this->registerView($form);
		parent::__construct($app, $menu);
		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
        /**/

        $params = ComponentHelper::getParams('com_rsgallery2');
        $this->noIDs = (bool) $params->get('sef_ids');


		//--- rules for J3x rsg2_legacy links ----------------------------------------

        /* use parent instead ??? */
        // rules for rootgalleriesj3x
        $rootgalleriesJ3x = new RouterViewConfiguration('rootgalleriesj3x');
        $rootgalleriesJ3x->setKey('gid');
        $this->registerView($rootgalleriesJ3x);
        /**/

        // 'toDo: use gal_id anf img_id instead

		// rules for galleriesJ3x,
        $galleriesJ3x = new RouterViewConfiguration('galleriesj3x');
        $galleriesJ3x->setKey('gid');
        $this->registerView($galleriesJ3x);

		// rules for galleriesJ3x,
        $galleryJ3x = new RouterViewConfiguration('galleryj3x');
		$galleryJ3x->setKey('gid');
        $this->registerView($galleryJ3x);

		// rules for slideshowJ3x
        $slideshowJ3x = new RouterViewConfiguration('slideshowj3x');
		$slideshowJ3x->setKey('gid');
        $this->registerView($slideshowJ3x);

		// rules for slidepagej3x
		// http://127.0.0.1/JoomlaFinnern/index.php/kaffee?view=slidepagej3x&gid=2&img_id=23
        $slidepagej3x = new RouterViewConfiguration('slidepagej3x');
		$slidepagej3x->setKey('gid');
        $this->registerView($slidepagej3x);

		$img_id = new RouterViewConfiguration('img_id');
		$img_id->setKey('img_id')->setParent($slidepagej3x);
		$this->registerView($img_id);

		//--- rules for new J4x links ----------------------------------------

        // rules for galleries
        $galleries = new RouterViewConfiguration('galleries');
		// $galleries->setKey('gid');
        $this->registerView($galleries);

		// rules for galleries,
		$gallery = new RouterViewConfiguration('gallery');
		// $gallery->setKey('gid');
		$this->registerView($gallery);

		// rules for images
        $images = new RouterViewConfiguration('images');
		// $images->setKey('gid');
        $this->registerView($images);

		// rules for slideshow
        $slideshow = new RouterViewConfiguration('slideshow');
		// $slideshow->setKey('gid');
        $this->registerView($slideshow);

        //---  ---------------------------------------

        parent::__construct($app, $menu);

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));

	}


// Doc: How to route ID -> https://www.techfry.com/joomla/how-to-create-router-for-joomla-component

/* use parent instead ??? */
// J3x - Root Gallery overview
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=rootgalleriesj3x&gid=0&images_show_title=2&images_show_description=0&images_show_search=0&images_column_arrangement=1&max_columns_in_images_view=4&images_row_arrangement=2&max_rows_in_images_view=5&max_thumbs_in_images_view=20&displaySearch=1&displayRandom=0&displayLatest=0&galleries_count=4&display_limitbox=1&galleries_show_title=1&galleries_show_description=0&galleries_show_owner=0&galleries_show_size=0&galleries_show_date=0&galleries_show_pre_label=0&displaySlideshow=0&galleries_description_side=global&latest_count=4&random_images=5&intro_text=%3Cp%3EHeader%20for%20galleries%20below%3C/p%3E&random_count=4&galleries_show_slideshow=1&Itemid=148
	public function getRootgalleriesJ3xSegment($gid, $query)
	{
		//return array((int) $gid => $gid);

        // root has no gallery ID
        $void = '0';
        $segment = '';

        // parent gallery
        if ($gid > 0) {
            $db      = Factory::getContainer()->get(DatabaseInterface::class);
            $dbquery = $db->getQuery(true);

            $dbquery->select($dbquery->qn('alias'))
                ->from($db->qn('__rsg2_galleries'))
                ->where('id = ' . $db->q($gid));

            $db->setQuery($dbquery);

            $gid .= ':' . $db->loadResult();

            list($void, $segment) = explode(':', $gid, 2);
        }

        return array($void => $segment);
    }
	public function getRootgalleriesJ3xId($segment, $query)
	{
//		return (int) $segment;

        $gid = 0; // root gallery

        // parent gallery
        if ( ! empty ($segment)) {
            $db      = Factory::getContainer()->get(DatabaseInterface::class);
            $dbquery = $db->getQuery(true);

            $dbquery->select($dbquery->qn('id'))
                ->from($dbquery->qn('#__rsg2_galleries'))
                ->where('alias = ' . $dbquery->q($segment));

            $db->setQuery($dbquery);

            if (!(int)$db->loadResult()) {
                $gid = false;
            }

            $gid = $db->loadResult();
        }

        return $gid;
	}

/* use parent instead */

// J3x - Galleries by Parent
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=galleriesj3x&gid=0&images_show_title=2&images_show_description=0&images_show_search=0&images_column_arrangement=1&max_columns_in_images_view=4&images_row_arrangement=2&max_rows_in_images_view=5&max_thumbs_in_images_view=20&Itemid=160
	public function getGalleriesJ3xSegment($gid, $query)
	{
//		return array((int) $gid => $gid);

        // fall back
        $void = '0';
        $segment = '';

        // parent gallery
        if ($gid > 0) {
            $db      = Factory::getContainer()->get(DatabaseInterface::class);
            $dbquery = $db->getQuery(true);

            $dbquery->select($dbquery->qn('alias'))
                ->from($db->qn('#__rsg2_galleries'))
                ->where('id = ' . $db->q($gid));

            $db->setQuery($dbquery);

            $gid .= ':' . $db->loadResult();

            list($void, $segment) = explode(':', $gid, 2);
        }

        return array($void => $segment);
	}

	public function getGalleriesJ3xId($segment, $query)
	{
//		return (int) $segment;
        $gid = 0; // root gallery

        // parent gallery
        if ( ! empty ($segment)) {
            $db      = Factory::getContainer()->get(DatabaseInterface::class);
            $dbquery = $db->getQuery(true);

            $dbquery->select($dbquery->qn('id'))
                ->from($dbquery->qn('#__rsg2_galleries'))
                ->where('alias = ' . $dbquery->q($segment));

            $db->setQuery($dbquery);

            if (!(int)$db->loadResult()) {
                $gid = false;
            }

            $gid = $db->loadResult();
        }

        return $gid;
	}


// J3x - Single Gallery
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=galleryj3x&gid=2&images_show_title=1&images_show_description=1&images_show_search=0&images_column_arrangement=1&max_columns_in_images_view=0&images_row_arrangement=2&max_rows_in_images_view=5&max_thumbs_in_images_view=15&displaySearch=0&gallery_show_title=1&gallery_show_description=0&gallery_show_slideshow=1&Itemid=149
	public function getGalleryJ3xSegment($gid, $query)
	{
        // ToDo: parent ?
		return array((int) $gid => $gid);
	}
	public function XgetGalleriesJ3xId($segment, $query)
	{
        // ToDo: parent ?
		return (int) $segment;
	}


// J3x - Slideshow
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=slideshowj3x&gid=2&Itemid=419
	public function getSlideshowJ3xSegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getSlideshowJ3xId($segment, $query)
	{
		return (int) $segment;
	}

// J3x - Slidepagej3x
	// http://127.0.0.1/JoomlaFinnern/index.php/kaffee?view=slidepagej3x&gid=2&img_id=23
	public function getSlidepagej3xSegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getSlidepagej3xId($segment, $query)
	{
		return (int) $segment;
	}

	public function getImg_idSegment($img_id, $query)
	{
		return array((int) $img_id => $img_id);
	}
	public function getImg_idId($segment, $query)
	{
		return (int) $segment;
	}


// J3x - Module Images
// http://127.0.0.1/Joomla4x/index.php?option=com_content&view=category&layout=blog&id=12&Itemid=360
	public function getYGalleriesJ3xSegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getYGalleriesJ3xId($segment, $query)
	{
		return (int) $segment;
	}


// J3x - Plugin Images
// wrong: http://127.0.0.1/Joomla4x/index.php?option=com_content&view=category&layout=blog&id=13&Itemid=361
	public function getAGalleriesJ3xSegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getAGalleriesJ3xId($segment, $query)
	{
		return (int) $segment;
	}


// RSG2 Root Galleries
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=galleries&gid=0&galleries_show_intro=0&galleries_show_title=2&galleries_show_description=0&galleries_show_search=0&galleries_column_arrangement=1&max_columns_in_galleries_view=4&galleries_row_arrangement=2&max_rows_in_galleries_view=5&max_galleries_in_galleries_view=20&Itemid=127
	public function getGalleriesSegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getGalleriesId($segment, $query)
	{
		return (int) $segment;
	}


// RSG2 Galleries by Parent
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=galleries&gid=3&galleries_show_intro=0&galleries_show_title=2&galleries_show_description=0&galleries_show_search=0&galleries_column_arrangement=1&max_columns_in_galleries_view=4&galleries_row_arrangement=2&max_rows_in_galleries_view=5&max_galleries_in_galleries_view=20&Itemid=153
	public function getZBGalleriesJ3xSegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getZBGalleriesJ3xId($segment, $query)
	{
		return (int) $segment;
	}


// RSG2 Gallery Images
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=gallery&gid=2&images_show_title=2&images_show_description=0&images_show_search=0&images_column_arrangement=1&max_columns_in_images_view=4&images_row_arrangement=2&max_rows_in_images_view=5&max_thumbs_in_images_view=20&Itemid=154
	public function getGallerySegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getGalleryId($segment, $query)
	{
		return (int) $segment;
	}


// RSG2 Slideshow
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=slideshow&gid=2&Itemid=155
	public function getSlideshowSegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getSlideshowId($segment, $query)
	{
		return (int) $segment;
	}


// RSG2 gallery images
// http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=images&gid=2&images_column_arrangement=1&max_columns_in_images_view=4&images_row_arrangement=2&max_rows_in_images_view=5&max_thumbs_in_images_view=16&images_show_title=2&images_show_description=0&images_show_search=0&Itemid=109
	public function getImagesSegment($gid, $query)
	{
		return array((int) $gid => $gid);
	}
	public function getImagesId($segment, $query)
	{
		return (int) $segment;
	}


// RSG2 Module - image
// .
// .
// .
// .


//
//    public function preprocess(&$query)
//    {
//        $menu = CMSApplication::getInstance('site')->getMenu();
//
//        // Search for all menu items for your component
//        $candidates = $menu->getItems('component', 'com_eventary');
//
//        if (!$candidates) return; // Nothing found
//
//        // Check each if it suits current $query
//        foreach ($candidates as $candidate)
//        {
//            if ( /* .. $candidate is goood */)
//            {
//                $query['Itemid'] = $candidate->id;
//                break;
//            }
//        }
//    }














}

