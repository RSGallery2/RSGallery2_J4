<?php
/**
 * @package       com_rsgallery2
 * @subpackage    plg_rsg2_gallery
 *
 * @copyright (c) 2005-2024 RSGallery2 Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

// https://docs.joomla.org/JDOC:Joomla_4_Tutorials_Project/en
// https://docs.joomla.org/J4.x:Creating_a_Plugin_for_Joomla/de
// https://docs.joomla.org/J4_Plugin_example_-_Table_of_Contents

// $this->params: die Parameter, die für dieses Plugin vom Administrator gesetzt werden
// $this->_name: der Name des Plugins
// $this->_type: die Gruppe (Art) des Plugins
// $this->db: das Datenbankobjekt
// $this->app: das Anwendungsobjekt

defined('_JEXEC') or die;

//use Joomla\CMS\Event\Event;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Registry\Registry;

// use Rsgallery2\Module\Rsg2_images\Site\Helper\Rsg2_imagesHelper;

//use Joomla\Event\SubscriberInterface;
//use Joomla\Utilities\ArrayHelper;

// use Rsg2_imagesNamespace\Module\Rsg2_images\Site\Helper\Rsg2_imagesHelper;

/**
 * look for RSG ... to be replaced by gallery images
 *
 * @return  void
 *
 * @since   4.0
 */
class PlgContentRsg2_gallery extends CMSPlugin
{
    /** @var CMSApplication */
    /**
     * protected $app;
     * protected $db;
     * /**/

    protected $debugActive = 0;


    //              onContentPrepare($context, &$article, $articleParams, $page = 0)
    public function onContentPrepare($context, &$article, &$articleParams, $page = 0)
    {
        $canProceed = $context === 'com_content.article';

        if (!$canProceed) {
            return null;
        }

//        $view = Factory::getApplication()->input->get('view');
//
//        echo "<br>----------------------------------<br>";
//        echo '<br/>the context is '.$context;
//        echo '<br/>the params are '.json_encode($articleParams, true);
//        echo '<br/>the page is '.$page;
//        echo '<br/>the view is '.$view;
//

        // Simple high performance check to determine whether bot should process further.
        if (stripos($article->text, '{rsg2_gallery') === false) {
            return null;
        }

        try {
            //--- collect all appearances ---------------------------------------

            // ToDo: use pre fetch regex ...
            // Expression to search for.
            $regex = "/{rsg2_gallery:(.*?)}/i";

            // Find all instances of plugin and put in $matches.
            $matches = [];
            preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

            // there should be matches as text is searched
            if (empty ($matches)) {
                echo "<br><br>!!! article has no text !!!<br>";

                return null;
            }


            //--- load css/js -----------------------------------------------

            //  keep to know what is available
            // $pluginPath = 'plugins/content/' . $this->_name;

            $doc = Factory::getApplication()->getDocument();
            $wa  = $doc->getWebAssetManager();

            //--- Load plugin language file -------------------------------

            $this->loadLanguage('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');

            //--- Perform the replacement ------------------------------

            $article->text = preg_replace_callback(
                $regex,
                [&$this, '_replacer'],
                $article->text,
            );
//            echo "<br>----------------------------------<br>";


        } catch (Exception $e) {
            $msg = Text::_('PLG_CONTENT_RSG2_GALLERY') . ' Error (01): ' . $e->getMessage();
            $app = Factory::getApplication();
            $app->enqueueMessage($msg, 'error');

            return false;
        }


        return true;
    }

    /**
     * Replaces the matched tags with gallery html output
     *
     * @param   array  $matches  An array of matches (see preg_match_all)
     *
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

            $rsgConfig = ComponentHelper::getParams('com_rsgallery2');

            // toDo: debug site !!!
            $DebugActive = $rsgConfig->get('isDebugSite');
            /**
             * if ($DebugActive) {
             * // Include the JLog class.
             * jimport('joomla.log.log');
             *
             * // Get the date for log file name
             * $date = Factory::getDate()->format('Y-m-d');
             *
             * // Add the logger.
             * JLog::addLogger(
             * // Pass an array of configuration options
             * array(
             * // Set the name of the log file
             * //'text_file' => substr($application->scope, 4) . ".log.php",
             * 'text_file' => 'rsgallery2.GalleryDisplay.log.' . $date . '.php',
             *
             * // (optional) you can change the directory
             * 'text_file_path' => 'logs'
             * ),
             * //JLog::ALL ^ JLog::DEBUG // leave out db messages
             * JLog::ALL
             * );
             *
             * // start logging...
             * JLog::add('Start plg_rsg2_gallerydisplay: debug active in RSGallery2', JLog::DEBUG);
             * }
             * /**/

            //----------------------------------------------------------------
            // Get attributes from matches and create "clean" array from them
            //----------------------------------------------------------------

            $attribs = explode(',', $matches[1]);
            if (!is_array($attribs)) {
                // ToDo: add outer query so its clear where it came from ...
                $errText = '??? ' . $matches[1] . '->No attributes ???';
                if ($DebugActive) {
                    JLog::add($errText, JLog::DEBUG);
                }

                return $errText;
            }

            $usrParams = $this->extractParams($attribs);

            // ToDo: use gids in first place: change Rsg2_imagesHelper -> modul ?mod_... ?? ....
            $usrParams->set('SelectGallery', $usrParams->get('gid'));

            $MVCFactory = $app->bootComponent('com_rsgallery2')->getMVCFactory();
            $model      = $MVCFactory->createModel('Images', 'Site', ['ignore_request' => true]);

            // $images = Rsg2_imagesHelper::getList($usrParams, $model, $app);
            $images = [];


// Test
//$layout = new FileLayout('Test.search');
            $layoutSearch = new FileLayout('components.com_rsgallery2.layouts.Search.search', JPATH_SITE);
            $layoutImages = new FileLayout('components.com_rsgallery2.layouts.ImagesArea.default', JPATH_SITE);
//echo $tabLayout->render(array('id' => $id, 'active' => $active, 'title' => $title));
// echo $layout->render();

            $displayData['images'] = $images;


            $html[] = '<h1> Plugin RSGallery2 PLG "gallery - images" view </h1>';
            $html[] = '<hr>';
            $html[] = $layoutSearch->render($displayData);
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
             * // Go over attribs to get template, gid and possible parameters
             * foreach ($clean_attribs as $key => $value) {//$key is 0, 1, etc. $value is semantic, etc.
             * switch ($key) {
             * // template (required), e.g. semantic
             * case 0:
             * if (isset($clean_attribs[0]) and (string)$clean_attribs[0]) {
             * $template = strtolower($clean_attribs[0]);
             * } else {
             * $template = Null;
             * }
             * break;
             * //  gallery id(required), e.g. 2
             * case 1:
             * if (isset($clean_attribs[1]) and (int)$clean_attribs[1]) {
             * $gallery_id = $clean_attribs[1];
             * } else {
             * $gallery_id = Null;
             * }
             * break;
             * // parameters like displaySearch=0;
             * default:
             * $pieces = explode("=", $clean_attribs[$key]);
             * // Change the configuration parameter with the value
             * if (count($pieces) > 1) {
             * //$rsgConfig->$pieces[0] = $pieces[1];
             * //$rsgConfig [$pieces] = $pieces[1];
             * $rsgConfig->set($pieces[0], $pieces[1]);
             * }
             * }
             * }
             * /**/

            //--- Start: Several checks on template and gallery id --------------------------------

            /**
             * if ($DebugActive) {
             * JLog::add('$content_output\n' . $content_output . '\n', JLog::DEBUG);
             * }
             * /**/

            /**
             * // Reset the original request array when finished
             * $_REQUEST = $original_request;
             * $_GET = $original_get;
             * $_POST = $original_post;
             * $rsgConfig = clone $original_rsgConfig;
             * /**/

            return $content_output;
        } catch (Exception $e) {
            $msg = Text::_('PLG_CONTENT_RSGALLERY2_GALLERYDISPLAY') . ' Error (02): ' . $e->getMessage();
            $app = Factory::getApplication();
            $app->enqueueMessage($msg, 'error');

            return false;
        }
        // return false;
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

        try {
            foreach ($attributes as $attribute) {
                $items = explode('=', $attribute);

                if (count($items) > 0) {
                    $name  = $this->clean_string($items[0]);
                    $value = '';

                    if (count($items) > 1) {
                        $value = trim($items[1]);
                    }

                    // ToDo: ? multiple gids?

                    // Handle plugin specific variables or J3x to j4x transformations
                    $isHandled = $this->handleSpecificParams($params, $name, $value);

                    // standard assignment
                    if (!$isHandled) {
                        $params->set($name, $value);
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing PLG Rsg2_images::extractParams: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $params;
    }


    /**
     * Remove spaces (&nbsp;) from attributes and trim white space
     *
     * @param   string  $attributeIn
     *
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
    function handleSpecificParams($params, $name, $value)
    {
        // ToDo: prepare indexed values template/layout ...
        $isHandled = false;


        return $isHandled;
    }


}

/**
 *
 * public
 * function dummy()
 * {
 * // $app = Factory::getApplication();
 *
 * //--- Retrieve params -----------------------
 *
 * $selectGallery = $params->get('SelectGallery');
 * $localFolder = $params->get('LocalFolder');
 * $folderUrl = $params->get('FolderUrl');
 *
 *
 * $images = [];
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
 * $images = Rsg2_imagesHelper::getImageNamesOfFolder($localFolder);
 *
 * } else {
 * // Use gallery is expected ?
 * if ($folderUrl) {
 *
 * $images = Rsg2_imagesHelper::getImageNamesOfUrl($folderUrl);
 *
 * } else {
 *
 * // Nothing selected
 * $app->enqueueMessage('plg_rsg2_images: source path for images is not defined in module "' . $module->title . '" definition');  // . __LINE__);
 * }
 * }
 * }
 *
 *
 * // Tests
 * $localFolder = JPATH_ROOT . '/images/rsgallery2/2/thumbs/';
 * $images = Rsg2_imagesHelper::getImageNamesOfFolder($localFolder);
 *
 * $folderUrl = 'http://localhost/joomla4x/images/rsgallery2/2/thumbs/';
 * $folderUrl = \Joomla\CMS\Uri\Uri::root() . '/images/rsgallery2/2/thumbs/';
 * $images = Rsg2_imagesHelper::getImageNamesOfUrl($folderUrl);
 *
 *
 * require ModuleHelper::getLayoutPath('plg_rsg2_images', $params->get('layout', 'default'));
 *
 *
 * }
 * /**/


