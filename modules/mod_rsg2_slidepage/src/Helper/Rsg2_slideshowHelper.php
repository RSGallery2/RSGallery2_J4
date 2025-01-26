<?php
/**
 * @package       Joomla.Site
 * @subpackage    mod_rsg2_slideshow
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Module\Rsg2_slideshow\Site\Helper;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;
use RuntimeException;

use function defined;

defined('_JEXEC') or die;

/**
 * Helper for mod_rsg2_slideshow
 *
 * @since  __BUMP_VERSION__
 */
class Rsg2_slideshowHelper implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    protected $galleryModel; // ToDo: rename to slideshowModel

    public function __construct(array $data)
    {
        // boot component only once Model('Gallery', 'Site')

        $app = $data['app'];

        // ToDo: add params, app to local vars

        // SiteApplication $app
        $this->galleryModel = $app
            ->bootComponent('com_rsgallery2')
            ->getMVCFactory()
            // ->createModel('Gallery', 'Site', ['ignore_request' => true]);
            ->createModel('SlideshowJ3x', 'Site', ['ignore_request' => true]);

        $appParams = $app->getParams();
        // $this->galleryModel->setState ('params') = $appParams;

    }


    public static function getList($params, $model, $app)
    {
        // Set application parameters in model
        $appParams = $app->getParams();
        $model->setState('params', $appParams);

        $model->setState('list.start', 0);
        //$model->setState('filter.condition', ContentComponent::CONDITION_PUBLISHED);
        //$model->setState('filter.condition', Rsgallery2Component::CONDITION_PUBLISHED);

        // Set the filters based on the module params
        $model->setState('list.limit', (int)$params->get('count', 5));

        // This module does not use tags data
        $model->setState('load_tags', false);

        // Access filter
        $access = !ComponentHelper::getParams('com_rsgallery2')->get('show_noauth');
        //$authorised = Access::getAuthorisedViewLevels(Factory::getContainer()->get(UserFactoryInterface::class)->get('id'));
        //$model->setState('filter.access', $access);

//		// Category filter
//		$model->setState('filter.category_id', $params->get('catid', array()));
//
//		// Filter by language
//		$model->setState('filter.language', $app->getLanguageFilter());
//
//		// Filter by tag
//		$model->setState('filter.tag', $params->get('tag', array()));
//
//		// Featured switch
//		$featured = $params->get('show_featured', '');
//
//		if ($featured === '')
//		{
//			$model->setState('filter.featured', 'show');
//		}
//		elseif ($featured)
//		{
//			$model->setState('filter.featured', 'only');
//		}
//		else
//		{
//			$model->setState('filter.featured', 'hide');
//		}

//		// Filter by id in case it should be excluded
//		if ($params->get('exclude_current', true)
//			&& $app->input->get('option') === 'com_content'
//			&& $app->input->get('view') === 'article')
//		{
//			// Exclude the current article from displaying in this module
//			$model->setState('filter.article_id', $app->input->get('id', 0, 'UINT'));
//			$model->setState('filter.article_id.include', false);
//		}

        // Set ordering
        $ordering = $params->get('ordering', 'a.publish_up');
        $model->setState('list.ordering', $ordering);

        if (trim($ordering) === 'rand()') {
            $model->setState(
                'list.ordering',
                Factory::getContainer()->get(DatabaseInterface::class)->getQuery(true)->rand(),
            );
        } else {
            $direction = $params->get('direction', 1) ? 'DESC' : 'ASC';
            $model->setState('list.direction', $direction);
            $model->setState('list.ordering', $ordering);
        }

        // Check if we should trigger additional plugin events
        $triggerEvents = $params->get('triggerevents', 1);
        $SelectGallery = $params->get('SelectGallery', 1);


        //$input = Factory::getApplication()->input;
        $input = $app->input;
        //$input->set( 'gid' , '2' );
        $input->set('gid', $SelectGallery);

        // Retrieve Content
        $items = $model->getItems();

        foreach ($items as &$item) {
//			$item->readmore = \strlen(trim($item->fulltext));
//			$item->slug     = $item->id . ':' . $item->alias;
//
//			if ($access || \in_array($item->access, $authorised))
//			{
//				// We know that user has the privilege to view the article
//				$item->link     = Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language));
//				$item->linkText = Text::_('MOD_ARTICLES_NEWS_READMORE');
//			}
//			else
//			{
//				$item->link = new Uri(Route::_('index.php?option=com_users&view=login', false));
//				$item->link->setVar('return', base64_encode(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language)));
//				$item->linkText = Text::_('MOD_ARTICLES_NEWS_READMORE_REGISTER');
//			}
//
//			$item->introtext = HTMLHelper::_('content.prepare', $item->introtext, '', 'mod_articles_news.content');
//
//			// Remove any images belongs to the text
//			if (!$params->get('image'))
//			{
//				$item->introtext = preg_replace('/<img[^>]*>/', '', $item->introtext);
//			}
//
//			// Show the Intro/Full image field of the article
//			if ($params->get('img_intro_full') !== 'none')
//			{
//				$images = json_decode($item->images);
//				$item->imageSrc = '';
//				$item->imageAlt = '';
//				$item->imageCaption = '';
//
//				if ($params->get('img_intro_full') === 'intro' && !empty($images->image_intro))
//				{
//					$item->imageSrc = htmlspecialchars($images->image_intro, ENT_COMPAT, 'UTF-8');
//					$item->imageAlt = htmlspecialchars($images->image_intro_alt, ENT_COMPAT, 'UTF-8');
//
//					if ($images->image_intro_caption)
//					{
//						$item->imageCaption = htmlspecialchars($images->image_intro_caption, ENT_COMPAT, 'UTF-8');
//					}
//				}
//				elseif ($params->get('img_intro_full') === 'full' && !empty($images->image_fulltext))
//				{
//					$item->imageSrc = htmlspecialchars($images->image_fulltext, ENT_COMPAT, 'UTF-8');
//					$item->imageAlt = htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'UTF-8');
//
//					if ($images->image_intro_caption)
//					{
//						$item->imageCaption = htmlspecialchars($images->image_fulltext_caption, ENT_COMPAT, 'UTF-8');
//					}
//				}
//			}
//
//			if ($triggerEvents)
//			{
//				$item->text = '';
//				$app->triggerEvent('onContentPrepare', array('com_content.article', &$item, &$params, 0));
//
//				$results                 = $app->triggerEvent('onContentAfterTitle', array('com_content.article', &$item, &$params, 0));
//				$item->afterDisplayTitle = trim(implode("\n", $results));
//
//				$results                    = $app->triggerEvent('onContentBeforeDisplay', array('com_content.article', &$item, &$params, 0));
//				$item->beforeDisplayContent = trim(implode("\n", $results));
//
//				$results                   = $app->triggerEvent('onContentAfterDisplay', array('com_content.article', &$item, &$params, 0));
//				$item->afterDisplayContent = trim(implode("\n", $results));
//			}
//			else
//			{
//				$item->afterDisplayTitle    = '';
//				$item->beforeDisplayContent = '';
//				$item->afterDisplayContent  = '';
//			}
        }

        return $items;
//		/** @var \Joomla\Component\Content\Site\Model\ArticlesModel $model */
//		$model = $app->bootComponent('com_content')
//			->getMVCFactory()->createModel('Articles', 'Site', ['ignore_request' => true]);
//
//		// Set application parameters in model
//		$appParams = $app->getParams();
//		$model->setState('params', $appParams);
//
//		$model->setState('list.start', 0);
//		$model->setState('filter.condition', ContentComponent::CONDITION_PUBLISHED);
//
//		// Set the filters based on the module params
//		$model->setState('list.limit', (int) $params->get('count', 5));
//
//		// This module does not use tags data
//		$model->setState('load_tags', false);
//
//		// Access filter
//		$access     = !ComponentHelper::getParams('com_content')->get('show_noauth');
//		$authorised = Access::getAuthorisedViewLevels(Factory::getContainer()->get(UserFactoryInterface::class)->get('id'));
//		$model->setState('filter.access', $access);
//
//		// Category filter
//		$model->setState('filter.category_id', $params->get('catid', array()));
//
//		// Filter by language
//		$model->setState('filter.language', $app->getLanguageFilter());
//
//		// Filter by tag
//		$model->setState('filter.tag', $params->get('tag', array()));
//
//		// Featured switch
//		$featured = $params->get('show_featured', '');
//
//		if ($featured === '')
//		{
//			$model->setState('filter.featured', 'show');
//		}
//		elseif ($featured)
//		{
//			$model->setState('filter.featured', 'only');
//		}
//		else
//		{
//			$model->setState('filter.featured', 'hide');
//		}
//
//		// Filter by id in case it should be excluded
//		if ($params->get('exclude_current', true)
//			&& $app->input->get('option') === 'com_content'
//			&& $app->input->get('view') === 'article')
//		{
//			// Exclude the current article from displaying in this module
//			$model->setState('filter.article_id', $app->input->get('id', 0, 'UINT'));
//			$model->setState('filter.article_id.include', false);
//		}
//
//		// Set ordering
//		$ordering = $params->get('ordering', 'a.publish_up');
//		$model->setState('list.ordering', $ordering);
//
//		if (trim($ordering) === 'rand()')
//		{
//			$model->setState('list.ordering', Factory::getContainer()->get(DatabaseInterface::class)->getQuery(true)->rand());
//		}
//		else
//		{
//			$direction = $params->get('direction', 1) ? 'DESC' : 'ASC';
//			$model->setState('list.direction', $direction);
//			$model->setState('list.ordering', $ordering);
//		}
//
//		// Check if we should trigger additional plugin events
//		$triggerEvents = $params->get('triggerevents', 1);
//
//		// Retrieve Content
//		$items = $model->getItems();
//
//		foreach ($items as &$item)
//		{
//			$item->readmore = \strlen(trim($item->fulltext));
//			$item->slug     = $item->id . ':' . $item->alias;
//
//			if ($access || \in_array($item->access, $authorised))
//			{
//				// We know that user has the privilege to view the article
//				$item->link     = Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language));
//				$item->linkText = Text::_('MOD_ARTICLES_NEWS_READMORE');
//			}
//			else
//			{
//				$item->link = new Uri(Route::_('index.php?option=com_users&view=login', false));
//				$item->link->setVar('return', base64_encode(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language)));
//				$item->linkText = Text::_('MOD_ARTICLES_NEWS_READMORE_REGISTER');
//			}
//
//			$item->introtext = HTMLHelper::_('content.prepare', $item->introtext, '', 'mod_articles_news.content');
//
//			// Remove any images belongs to the text
//			if (!$params->get('image'))
//			{
//				$item->introtext = preg_replace('/<img[^>]*>/', '', $item->introtext);
//			}
//
//			// Show the Intro/Full image field of the article
//			if ($params->get('img_intro_full') !== 'none')
//			{
//				$images = json_decode($item->images);
//				$item->imageSrc = '';
//				$item->imageAlt = '';
//				$item->imageCaption = '';
//
//				if ($params->get('img_intro_full') === 'intro' && !empty($images->image_intro))
//				{
//					$item->imageSrc = htmlspecialchars($images->image_intro, ENT_COMPAT, 'UTF-8');
//					$item->imageAlt = htmlspecialchars($images->image_intro_alt, ENT_COMPAT, 'UTF-8');
//
//					if ($images->image_intro_caption)
//					{
//						$item->imageCaption = htmlspecialchars($images->image_intro_caption, ENT_COMPAT, 'UTF-8');
//					}
//				}
//				elseif ($params->get('img_intro_full') === 'full' && !empty($images->image_fulltext))
//				{
//					$item->imageSrc = htmlspecialchars($images->image_fulltext, ENT_COMPAT, 'UTF-8');
//					$item->imageAlt = htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'UTF-8');
//
//					if ($images->image_intro_caption)
//					{
//						$item->imageCaption = htmlspecialchars($images->image_fulltext_caption, ENT_COMPAT, 'UTF-8');
//					}
//				}
//			}
//
//			if ($triggerEvents)
//			{
//				$item->text = '';
//				$app->triggerEvent('onContentPrepare', array('com_content.article', &$item, &$params, 0));
//
//				$results                 = $app->triggerEvent('onContentAfterTitle', array('com_content.article', &$item, &$params, 0));
//				$item->afterDisplayTitle = trim(implode("\n", $results));
//
//				$results                    = $app->triggerEvent('onContentBeforeDisplay', array('com_content.article', &$item, &$params, 0));
//				$item->beforeDisplayContent = trim(implode("\n", $results));
//
//				$results                   = $app->triggerEvent('onContentAfterDisplay', array('com_content.article', &$item, &$params, 0));
//				$item->afterDisplayContent = trim(implode("\n", $results));
//			}
//			else
//			{
//				$item->afterDisplayTitle    = '';
//				$item->beforeDisplayContent = '';
//				$item->afterDisplayContent  = '';
//			}
//		}
//
//		return $items;
    }

    public function getGalleryData(int $gid)
    {
        return $this->galleryModel->galleryData($gid);
    }









    /**
     * Get a list of the latest articles from the article model
     *
     * @param   Registry  &$params  object holding the models parameters
     *
     * @return  mixed
     *
     * @since 1.6
     */
//	public static function getList(Registry $params, BannersModel $model, CMSApplication $app)
    /**
     * Get a list of the gallery images from the slideshow model.     *
     *
     * @param   Registry        $params  The module parameters
     * @param   CMSApplication  $app     The application
     *
     * @return  array
     */
    public function getImagesOfGallery(int $gid, Registry $params, SiteApplication $app)
    {
        $images = [];

        try {
            $model = $this->galleryModel;

            //--- state -------------------------------------------------

            $state = $model->getState();

            // Set application parameters in model
            $appParams = $app->getParams();

            $model->setState('params', $params);

            $model->setState('list.start', 0);
            $model->setState('filter.published', 1);

            // Set the filters based on the module params
            // $model->setState('list.limit', (int) $params->get('count', 5));
            $model->setState('list.limit', 99);

            // This module does not use tags data
            $model->setState('load_tags', false);

            $model->setState('gallery.id', $gid);
            $model->setState('gid', $gid);

            //--- images -----------------------------------------------------------------------

//             $this->galleryModel->populateState();

            // $images= $this->galleryModel->get('Items');
            $images = $this->galleryModel->getItems();

            if (!empty($images)) {
                // Add image paths, image params ...
                $data = $this->galleryModel->AddLayoutData($images);
            }
        } catch (RuntimeException $e) {
            // ToDo: Message more explicit
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $images;
    }




//
//    public static function getImageNamesOfUrl ($folderUrl)
//    {
//        $Images = [];
//
//        $html = file_get_contents($folderUrl);
//////        $data = file_get_contents(JPATH_ROOT . '/' . $path);
////        $data = json_decode($html, true);
////        $data = json_decode($html);
////        $data = $html ? json_decode($html, true) : null;
////
//
//        // ToDo: first element is wrong: check regex
////        // toDo: Only allowed extensions
////        $count = preg_match_all("((http|https|ftp|ftps)://?([a-zA-Z0-9\\\./]*.jpg))", $html, $files);
//        $count = preg_match_all('/<a href="([^"]+)(png|jpg|webp\/)">[^<]*<\/a>/i', $html, $files);
//        for ($i = 0; $i < $count; ++$i) {
//            $fileName = $files[1][$i] . $files[2][$i];
//////            echo "File: " . $fileName . "<br />\n";
////
//            $Images[] = $folderUrl . '/' . $fileName;
//        }
////
//////        var_dump($files);
//
//        return $Images;
//    }
//
//
//    public static function getImageNamesOfFolder ($folder)
//    {
//        $Images = [];
//
//        // toDo: Only allowed extensions
//        foreach(glob($folder . '*.{jpg,JPG,jpeg,JPEG,png,PNG}',GLOB_BRACE) as $fileName) {
//            // echo "File: " . $fileName . "<br />\n";
//            $Images[] =  $fileName;
//        }
//
//        return $Images;
//    }
//

    public function getText()
    {
        $msg = "    --- Rsg2_slideshow module ----- ";

        return $msg;
    }


}

