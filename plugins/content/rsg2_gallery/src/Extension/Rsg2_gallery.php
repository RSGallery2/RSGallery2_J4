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

    protected const string MARKER = 'rsg2_gallery:';

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
     * Display gallery thumbs in (J3x) style in an article. The function
     * scans given article text and replaces a marker definition with
     * gallery thumbs HTML. The marker has the form
     * {MARKER parameter:value, parameter:value, ...}
     * where MARKER is defined above in PHP code
     * No parameters are required, as the basic parameters are handled by
     * the plugin. In most cases the gallery id definition is used "gid:8"
     * Example: {rsg2_gallery: gid:8}
     * Extreme example with parameters see plugin:
     * {rsg2_gallery: gid:8, images_layout:ImagesAreaJ3x.default, max_columns_in_images_view_j3x:3 }
     * Additional useful parameter
     * - debugOnlyTitle:1,
     * - isDebugSite:1
     * - max_thumbs_in_images_view_j3x:12
     *
     * @param   ContentPrepareEvent  $event
     *
     * @return bool
     *
     * @since  5.1.0
     */
    public function getRsg2_galleryDisplay(ContentPrepareEvent $event): bool
    {
        try {
            // The line below restricts the functionality to the site (ie not on api)
            if (!$this->getApplication()->isClient('site')) {
                return false;
            }

            // use this format to get the arguments for both Joomla 4 and Joomla 5
            // In Joomla 4 a generic Event is passed
            // In Joomla 5 a concrete ContentPrepareEvent is passed
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

            // check for allowed content type
            if ($context !== "com_content.article"
                && $context !== "com_content.featured"
                && $context !== "com_content.category"
            ) {
                return false;
            }

            // Nothing to change
            if (empty($article) || empty($article->text)) {
                return false;
            }

            //--- collect all appearances ---------------------------------------

            // Expression to search for.
            $pattern = "/{" . self::MARKER . "(.*)}/i";

            preg_match_all($pattern, $article->text, $matches, PREG_SET_ORDER);

            //==========================================================================
            // Replace all matches
            //==========================================================================

            if ($matches) {
                $app = Factory::getApplication();

                //--- Load component language file -------------------------------

                // $this->loadLanguage(); // load plugin language strings ? Not needed ?
                $this->loadLanguage('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');

                //--- prepare rsg2 data -------------------------------

                $helper = new Rsg2_galleryHelper();

                //--- all matches --------------------------------------

                foreach ($matches as $usrDefinition) {
                    $insertHtml = '';

                    $replaceText  = $usrDefinition[0]; // develop check
                    $replaceLen   = strlen($replaceText);
                    $replaceStart = strpos($article->text, $usrDefinition[0]);

                    $usrParams = $this->extractUserParams($usrDefinition[1]);
                    $plgParams = $this->params;

                    //======================================================
                    // replacement
                    //======================================================

                    $insertHtml .= $helper->galleryImagesHtml($usrParams, $plgParams);

                    //--- Perform the replacement ------------------------------

                    $article->text = substr_replace(
                        $article->text,
                        $insertHtml,
                        $replaceStart,
                        $replaceLen,
                    );
                }
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
     * Extract parameter given in marker area. The definition of a parameter
     * is "name:value[, name:value][ ...]"
     * Example:
     * "gid:8, images_layout:ImagesAreaJ3x.default, max_columns_in_images_view_j3x:3"
     * It returns the extracted parameters in the form of joomla registry.
     * It may return an empty registry though
     *
     * @param   string  $usrString
     *
     * @return Registry
     *
     * @throws \Exception
     *
     * @since 5.0.0
     */
    private function extractUserParams(string $usrString): Registry
    {
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
        }

        return $usrParams;
    }

}
