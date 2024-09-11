<?php
/**
 * /**
 * @package
 * @subpackage  plg_rsg2_image
 *
 * @copyright (c) 2005-2024 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// https://docs.joomla.org/J3.x:Creating_a_content_plugin/en
// https://docs.joomla.org/JDOC:Joomla_4_Tutorials_Project/en
// https://docs.joomla.org/J4.x:Creating_a_Plugin_for_Joomla/de
// https://docs.joomla.org/J4_Plugin_example_-_Table_of_Contents

// $this->params: die Parameter, die für dieses Plugin vom Administrator gesetzt werden
// $this->_name: der Name des Plugins
// $this->_type: die Gruppe (Art) des Plugins
// $this->db: das Datenbankobjekt
// $this->app: das Anwendungsobjekt

\defined('_JEXEC') or die;

//use Joomla\CMS\Event\Event;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;
use Joomla\Registry\Registry;

use Rsgallery2\Module\Rsg2_image\Site\Helper\Rsg2_imageHelper;

//use Joomla\Event\SubscriberInterface;
//use Joomla\Utilities\ArrayHelper;

// use Rsg2_imageNamespace\Module\Rsg2_image\Site\Helper\Rsg2_imageHelper;

/**
 * look for RSG ... to be replaced by gallery image
 *
 * @return  void
 *
 * @since   4.0
 */
class PlgContentRsg2_image extends CMSPlugin
{

    /** @var \Joomla\CMS\Application\CMSApplication */
    /**
    protected $app;
    protected $db;
    /**/

    protected $debugActive = 0;
    /**
     * Load the language file on instantiation
     *
     * @var    boolean
     * @since  3.1 Joomla
     */
//    protected $autoloadLanguage = true;  -> needs

    // onContentPrepare($context, &$article, &$articleParams, $page = 0)
    public function onContentPrepare($context, &$article, $articleParams, $page = 0)
    {

        // the context could be something other than com_content
        // such as a module - in which case do nothing and return
        if ($context !== 'com_content.article') {
            return;
        }

        // Simple high performance check to determine whether bot should process further.
        if (stripos($article->text, '{rsg2_image') === false) {
            return;
        }

        try {

            // Load plugin language file only when needed
            $this->loadLanguage('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');

            HTMLHelper::_('stylesheet', 'com_rsgallery2/site/image.css', array('version' => 'auto', 'relative' => true));


            //--- Perform the replacement ------------------------------

            // Define the regular expression for the
            //$regex = "#{rsg2_display\:*(.*?)}#s";
            $regex = "|\{rsg2_image:(.*?)\}|";

            $article->text = preg_replace_callback($regex,
                array(&$this, '_replacer'),
                $article->text);

            // toDO: J3x form
            //$regex = "#{rsg2_display\:*(.*?)}#s";
            $regex = "|\{rsg2_image:(.*?)\}|";

            $article->text = preg_replace_callback($regex,
                array(&$this, '_replacer'),
                $article->text);

        } catch (Exception $e) {
            $msg = Text::_('PLG_CONTENT_RSG2_IMAGE') . ' Error (01): ' . $e->getMessage();
            $app = Factory::getApplication();
            $app->enqueueMessage($msg, 'error');
            return false;
        }

        return true;
    }

    /**
     * Replaces the matched tags with gallery html output
     *
     * @param array $matches An array of matches (see preg_match_all)
     * @return bool|string
     * @throws Exception
     */
    protected function _replacer($matches)
    {
        global $rsgConfig;

        if (!$matches) {
            return false;
        }

        $app = Factory::getApplication();

        try {
            //
            $this->debugActive = $this->params->get('debug', '0');

            // Save the default configuration because a user might change the
            // parameters via the plugin but can also use the plugin multiple
            // times on one page (use "clone" because in PHP5 objects are passed
            // by reference).
            // $original_rsgConfig = clone $rsgConfig;

            $rsgConfig = JComponentHelper::getParams( 'com_rsgallery2' );

            // toDo: debug site !!!
            $DebugActive = $rsgConfig->get('isDebugSite');
            /**
            if ($DebugActive) {
                // Include the JLog class.
                jimport('joomla.log.log');

                // Get the date for log file name
                $date = Factory::getDate()->format('Y-m-d');

                // Add the logger.
                JLog::addLogger(
                // Pass an array of configuration options
                    array(
                        // Set the name of the log file
                        //'text_file' => substr($application->scope, 4) . ".log.php",
                        'text_file' => 'rsgallery2.GalleryDisplay.log.' . $date . '.php',

                        // (optional) you can change the directory
                        'text_file_path' => 'logs'
                    ),
                    //JLog::ALL ^ JLog::DEBUG // leave out db messages
                    JLog::ALL
                );

                // start logging...
                JLog::add('Start plg_rsg2_imagedisplay: debug active in RSGallery2', JLog::DEBUG);
            }
            /**/

            //----------------------------------------------------------------
            // Get attributes from matches and create "clean" array from them
            //----------------------------------------------------------------


            $attribs = explode(',', $matches[1]);
            if ( ! is_array($attribs)) {
                $errText = '??? ' . $matches[1] . '->No attributes ???';
                if ($DebugActive) {
                    JLog::add($errText, JLog::DEBUG);
                }

                return $errText;
            }

            $usrParams = $this->extractParams ($attribs);

            // ToDo: use gids in first place: change RSG2_imageHelper -> modul ?mod_... ?? ....
            $usrParams->set ('SelectGallery', $usrParams->get('gid'));


            $model = $app->bootComponent('com_rsgallery2')->getMVCFactory()->createModel('Image', 'Site', ['ignore_request' => true]);
            $image = Rsg2_imageHelper::getList($usrParams, $model, $app);


// Test
//$layout = new FileLayout('Test.search');
            $layoutSearch    = new FileLayout('components.com_rsgallery2.layouts.Search.search', JPATH_SITE);
            $layoutImages    = new FileLayout('components.com_rsgallery2.layouts.ImagesArea.default', JPATH_SITE);
//echo $tabLayout->render(array('id' => $id, 'active' => $active, 'title' => $title));
// echo $layout->render();

            $displayData['H'] = $image;


            $html[] = '<h1> Plugin RSGallery2 "images" view </h1>';
            $html[] = '<hr>';
            $html[] =  $layoutSearch->render($displayData);;
            $html[] = '<hr>';
            $html[] = $layoutImages->render($displayData);
            $html[] = '<hr>';
            $html[] = '<br>';
            $html[] = '';
            $html[] = '';
            $html[] = '';
            $html[] = '';
            $html[] = '';

            // implode($html);
            // implode(' ', $html);
            // implode('< /br>', $html);

            $content_output = implode($html);

            /**
            // Go over attribs to get template, gid and possible parameters
            foreach ($clean_attribs as $key => $value) {//$key is 0, 1, etc. $value is semantic, etc.
                switch ($key) {
                    // template (required), e.g. semantic
                    case 0:
                        if (isset($clean_attribs[0]) and (string)$clean_attribs[0]) {
                            $template = strtolower($clean_attribs[0]);
                        } else {
                            $template = Null;
                        }
                        break;
                    //  gallery id(required), e.g. 2
                    case 1:
                        if (isset($clean_attribs[1]) and (int)$clean_attribs[1]) {
                            $gallery_id = $clean_attribs[1];
                        } else {
                            $gallery_id = Null;
                        }
                        break;
                    // parameters like displaySearch=0;
                    default:
                        $pieces = explode("=", $clean_attribs[$key]);
                        // Change the configuration parameter with the value
                        if (count($pieces) > 1) {
                            //$rsgConfig->$pieces[0] = $pieces[1];
                            //$rsgConfig [$pieces] = $pieces[1];
                            $rsgConfig->set($pieces[0], $pieces[1]);
                        }
                }
            }
            /**/

            //--- Start: Several checks on template and gallery id --------------------------------

            /**
            // Check we have a template name
            if (!isset($template)) {
                if ($DebugActive) {
                    $msg = Text::_('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_NO_TEMPLATE_NAME_GIVEN');
                    $app->enqueueMessage($msg, 'message');
                    JLog::add('Template not found: "' . $template . '"', JLog::DEBUG);
                }

                return false;
            }

            // Check the template is indeed installed
            $templateLocation = JPATH_RSGALLERY2_SITE . '/templates/' . $template . '/index.php';
            if (!file_exists($templateLocation)) {
                if ($DebugActive) {
                    $msg = Text::sprintf('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_TEMPLATE_DIRECTORY_NOT_FOUND', $template);
                    $app->enqueueMessage($msg, 'message');
                    JLog::add('Template location not found: "' . $templateLocation . '"', JLog::DEBUG);
                }
                return false;
            }

            // Check we have a gallery id
            if (!isset($gallery_id)) {
                if ($DebugActive) {
                    $msg = Text::_('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_NO_GALLERY_ID_GIVEN');
                    $app->enqueueMessage($msg, 'message');
                    JLog::add('no gallery id found: "' . $gallery_id . '"', JLog::DEBUG);
                }
                return false;
            }

            // Check if a gallery with gallery id exists
            // Get gallery details first
            $db = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->getQuery(true);
            $query->select('id, name, published'); // ToDo: Perhaps access could be checked as well
            $query->from('#__rsgallery2_galleries');
            $query->where('id = ' . (int)$gallery_id);
            $db->setQuery($query);
            $galleryDetails = $db->loadAssoc();

            // Does the gallery exist?
            if (!$galleryDetails) {
                if ($DebugActive) {
                    $msg = Text::sprintf('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_NO_SUCH_GALLERY_ID_EXISTS', $gallery_id);
                    $app->enqueueMessage($msg, 'message');
                    JLog::add('gallery id not found in DB: "' . $gallery_id . '"', JLog::DEBUG);
                }
                return false;
            }

            // Is the gallery published?
            if (!$galleryDetails['published']) {
                if ($DebugActive) {
                    $msg = Text::sprintf('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY_GALLERY_UNPUBLISHED', $galleryDetails['name'], $gallery_id);
                    $app->enqueueMessage($msg, 'message');
                    JLog::add('gallery not published: "' . $gallery_id . '"', JLog::DEBUG);
                }
                return false;
            }
            //--- End: Several checks on template and gallery id ---------------------------------


            // Cache the current request array to a variable before doing anything
            $original_request = $_REQUEST;
            $original_get = $_GET;
            $original_post = $_POST;

            //--- patch the input variables ---------------

            //The article has lang, language, Itemid, option, view, catid and id
            //Get rid of catid and id, change option and view, set gallery_id (gid).
            $input = Factory::getApplication()->input;
            //JRequest::setVar('catid',Null);   //Is there a way to unset this?

            // Id may otherwise try to retrieve a image
            JRequest::setVar('id', Null);    //Is there a way to unset this?
            //JRequest::setVar('option','com_rsgallery2');
            //JRequest::setVar('view', 'gallery');

            //JRequest::setVar('gid', $gallery_id);
            $input->set('gid', $gallery_id);
            //JRequest::setVar('rsgTemplate', $template);
            $input->set('rsgTemplate', $template);


            //--- Get the RSGallery2 gallery template HTML! -----------------------
            ob_start();
            rsgInstance::instance(); // With option $showTemplate = true
            $content_output = ob_get_contents();
            ob_end_clean();

            /**
             * if ($DebugActive) {
             * JLog::add('$content_output\n' . $content_output . '\n', JLog::DEBUG);
             * }
             * /**/

            /**
            // Reset the original request array when finished
            $_REQUEST = $original_request;
            $_GET = $original_get;
            $_POST = $original_post;
            $rsgConfig = clone $original_rsgConfig;
            /**/

            return $content_output;

        } catch (Exception $e) {
            $msg = Text::_('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY') . ' Error (02): ' . $e->getMessage();
            $app = Factory::getApplication();
            $app->enqueueMessage($msg, 'error');
            return false;
        }

        return false;
    }


    /**
     * @param $attributes string [] 'name=value'
     *
     * @return array|string|string[]|null
     *
     * @since version
     */
    function extractParams($attributes)
    {
        $params = new Registry();

        try
        {
            foreach ($attributes as $attribute) {

                $items = explode('=', $attribute);

                if (count ($items) > 0) {

                    $name = $this->clean_string($items[0]);
                    $value = '';

                    if (count ($items) > 1) {
                        $value = trim($items[1]);
                    }

                    // ToDo: ? multiple gids?

                    // Handle plugin specific variables or J3x to j4x transformations
                    $isHandled = $this->handleSpecificParams ($params, $name, $value);

                    // standard assingment
                    if (! $isHandled) {
                        $params->set ($name, $value);
                    }
                }

            }


        }

        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing PLG Rsg2_image::extractParams: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $params;
    }


    /**
     * Remove spaces (&nbsp;) from attributes and trim white space
     *
     * @param string $attributeIn
     * @return  string
     */
    function clean_string($attributeIn)
    {
        $attribute = str_replace("&nbsp;", '', "$attributeIn");
        // $attribute = trim ($attribute); // surprisingly only one blank removed
        $attribute = preg_replace('/\s/u', '', $attribute); // '/u' -> unicode

        return $attribute;
    }

    /**
     * Check for J3x parameter by index without value -> layout ..
     * or handöle lists (multiple gids / iids)
     * or ...
     *
     * @param $params  Registry Add new set
     * @param $name
     * @param $value
     *
     *
     * @since version
     */
    function handleSpecificParams ($params, $name, $value)
    {
        // ToDo: prepare indexed values template/layout ...
        $isHandled = false;



        return $isHandled;
    }


}

/**

public
function dummy()
{
     * // $app = Factory::getApplication();
     *
     * //--- Retrieve params -----------------------
     *
     * $selectGallery = $params->get('SelectGallery');
     * $localFolder = $params->get('LocalFolder');
     * $folderUrl = $params->get('FolderUrl');
     *
     *
     * $image = [];
     *
     * // Use gallery images (?org/display/thumb ?)
     * if ($selectGallery > 0) {
     *
     * // ToDo: retrieve path to thumbs ? ....
     *
     * } else {
     *
     * // Use local folder images ?
     * if ( $localFolder) {
     *
     * $image = Rsg2_imageHelper::getImageNamesOfFolder($localFolder);
     *
     * } else {
     * // Use gallery is expected ?
     * if ($folderUrl) {
     *
     * $image = Rsg2_imageHelper::getImageNamesOfUrl($folderUrl);
     *
     * } else {
     *
     * // Nothing selected
     * $app->enqueueMessage('plg_rsg2_image: source path for images is not defined in module "' . $module->title . '" definition');  // . __LINE__);
     * }
     * }
     * }
     *
     *
     * // Tests
     * $localFolder = JPATH_ROOT . '/images/rsgallery2/2/thumbs/';
     * $image = Rsg2_imageHelper::getImageNamesOfFolder($localFolder);
     *
     * $folderUrl = 'http://localhost/joomla4x/images/rsgallery2/2/thumbs/';
     * $folderUrl = \Joomla\CMS\Uri\Uri::root() . '/images/rsgallery2/2/thumbs/';
     * $image = Rsg2_imageHelper::getImageNamesOfUrl($folderUrl);
     *
     *
     * require ModuleHelper::getLayoutPath('plg_rsg2_image', $params->get('layout', 'default'));
     *

}
/**/


