<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Plugin\Content\Rsg2_gallery\Extension;

use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;
use Rsgallery2\Plugin\Content\Rsg2_gallery\Helper\Rsg2_galleryHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;

// phpcs:enable PSR1.Files.SideEffects

class Rsg2_gallery extends CMSPlugin implements SubscriberInterface
{
    // only for lang strings shown on execution of plugin
    // protected $autoloadLanguage = true;  You should always load them manually

    protected const MARKER = 'rsg2_gallery:';

//  public function __construct(&$subject, $config = array())
//  {
//      $this->autoloadLanguage = true;
//
//      parent::__construct($subject, $config);
//  }

    /**
     *
     * @return string[]
     *
     * @since  5.1.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare' => 'getRsg2_galleryDisplay',
        ];
    }

    /**
     * This function processes the text of an article being presented on the site.
     *  ToDo: More description
     *
     * @param   ContentPrepareEvent  $event
     *
     * @return bool
     *
     * @since  5.1.0
     */
    public function getRsg2_galleryDisplay(ContentPrepareEvent $event)
    {
        /* This function processes the text of an article being presented on the site.
        * It replaces any text of the form "{rsg2_gallery: ...}" ....
        */
        $context   = '';
        $article   = '';
        $usrParams = new Registry();

        try {
            // The line below restricts the functionality to the site (ie not on api)
            // You may not want this, so you need to consider this in your own plugins
            if (!$this->getApplication()->isClient('site')) {
                return;
            }

            // ToDo: Remove: temp test (article not found otherwiase)
            // [$context, $article, $params, $page] = array_values($event->getArguments());

            // support J4 (and J5)
            if (version_compare(JVERSION, '4.999999.999999', 'lt')) {
                [$context, $article, $params, $page] = array_values($event->getArguments());
            } else {
                // $context = $event->getContext();
                $context = $event->getArgument('context');
                // $article = $event->getArgument('article');
                $article = $event->getItem();
                // $params  = $event->getArgument('params'); // spelling ?
                $params = $event->getParams();
                $page   = $event->getPage();
            }

            // use this format to get the arguments for both Joomla 4 and Joomla 5
            // In Joomla 4 a generic Event is passed
            // In Joomla 5 a concrete ContentPrepareEvent is passed
            // [$context, $article, $params, $page] = array_values($event->getArguments());

            // check for allowed content type
            if ($context !== "com_content.article"
                && $context !== "com_content.featured"
                && $context !== "com_content.category"
            ) {
                return;
            }

            // Nothing to change
            if (empty($article) || empty($article->text)) {
                return;
            }

            //--- replacement may exist --------------------------------------------------

            $lastUserTestIdx = 0;

            //--- collect all appearances ---------------------------------------

            // Expression to search for.
            // $pattern = "/{" . self::MARKER . "(.*?)}/i";
            $pattern = "/{" . self::MARKER . "(.+)}/i";

            preg_match_all($pattern, $article->text, $matches, PREG_SET_ORDER);

//              // debug: there should be matches as text is searched
//              if(empty ($matches)) {
//                  echo "<br><br>!!! article has no rsg2_gallery !!!<br>";
//                  return null;
//              }

            // Replace all matches
            if ($matches) {
                $plgPara = Factory::getApplication()->getConfig()->toArray();  // config params as an array

                //--- Load component language file -------------------------------

                // $this->loadLanguage(); // load plugin language strings ? Not needed ?
                $this->loadLanguage('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');

                foreach ($matches as $usrDefinition) {
                    $insertHtml = '';

                    $replaceText  = $usrDefinition[0]; // develop check
                    $replaceLen   = strlen($usrDefinition[0]);
                    $replaceStart = strpos($article->text, $usrDefinition[0]);

                    //$test = $usrDefinition[1];
                    $usrParams = $this->extractUserParams($usrDefinition[1]);

                    // check if gid is missing or wrong (no real check of DB)
                    $gid = $usrParams->get('gid', -1);
                    if ($gid < 1) {
                        $insertHtml = 'Plg RSG2 gallery: Gid missing "gid:xx,.." ';
                    }

                    // debugOnlyTitle: only tell about replacement
                    $debugOnlyTitle = $usrParams->get('debugOnlyTitle', 0);
                    if (!empty($debugOnlyTitle)) {
                        $insertHtml .= '<h4>--- Rsg2_gallery replacement ---</h4>';
                    } else {
                        //======================================================
                        // replacement
                        //======================================================

                        $helper = new Rsg2_galleryHelper();
                        $insertHtml .= $helper->galleryImagesHtml();
                    }
                }

                //--- Perform the replacement ------------------------------

                $article->text = substr_replace(
                    $article->text,
                    $insertHtml,
                    $replaceStart,
                    $replaceLen,
                );
            }

            // only needed when exchange the complete article ?
            // $event->stopPropagation();
        } catch (\Exception $e) {
            $msg = Text::_('PLG_CONTENT_RSG2_GALLERY') . ' getRsg2_galleryDisplay: ' . ' Error (01): ' . $e->getMessage();
            $app = Factory::getApplication();
            $app->enqueueMessage($msg, 'error');

            return false;
        }


        return true;
    }

    /**
     * @param   string  $usrString
     *
     * @return false|Registry
     *
     * @since version
     */
    private
    function extractUserParams(
        string $usrString,
    ) {
        $usrParams = new Registry();

        try {
            $usrString = trim($usrString);
            //$paramSets = explode('=', $usrParams);
            $paramSets = explode(',', $usrString);

            foreach ($paramSets as $paramSet) {
                if (!empty($paramSet)) {
                    if (str_contains($paramSet, ':')) {
                        [$name, $value] = explode(':', $paramSet, 2);
                    } else {
                        $name  = $paramSet;
                        $value = '';
                    }

                    $usrParams->set(trim($name), trim($value));
                    // ToDo: prepare indexed values template/layout ...
                }
            }
        } catch (\Exception $e) {
            $msg = Text::_('PLG_CONTENT_RSG2_GALLERY' . 'extractUserParams: "') . $usrString . '" Error (01): ' . $e->getMessage();
            $app = Factory::getApplication();
            $app->enqueueMessage($msg, 'error');

            return false;
        }

        return $usrParams;
    }
}
