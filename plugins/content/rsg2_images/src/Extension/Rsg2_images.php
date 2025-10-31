<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Plugin\Content\Rsg2_images\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\EventInterface;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;

class Rsg2_images extends CMSPlugin implements SubscriberInterface
{
    // only for lang strings shown on execution of plugin
    protected $autoloadLanguage = true;

//	public function __construct(&$subject, $config = array())
//	{
//		$this->autoloadLanguage = true;
//
//		parent::__construct($subject, $config);
//	}

    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare' => 'getRsg2_imagesDisplay',
        ];
    }

    public function getRsg2_imagesDisplay(Event $event)
    {
        $context   = '';
        $article   = '';
        $usrParams = new Registry ();

        try {
            // support J4 (and J5)
            if (version_compare(JVERSION, '4.999999.999999', 'lt')) {
                [$context, $article, $params] = $event->getArguments();
            } else {
                $context = $event->getArgument('context');
                $article = $event->getArgument('article');
                $params  = $event->getArgument('params'); // spelling ?
            }

            if ((strpos($context, 'com_content.article') === false)
                && (strpos($context, 'com_content.category') === false)
            ) {
                return false;
            }

            //--- replacement may exist --------------------------------------------------

            if (str_contains($article->text, '{rsg2_images')) {
                $lastUserTestIdx = 0;

                //--- collect all appearances ---------------------------------------

                // Expression to search for.
                $pattern = "/{rsg2_images:(.*?)}/i";

                preg_match_all($pattern, $article->text, $matches, PREG_SET_ORDER);

//				// debug: there should be matches as text is searched
//				if(empty ($matches)) {
//					echo "<br><br>!!! article has no rsg2_images !!!<br>";
//					return null;
//				}

                // Replace all matches
                if ($matches) {
                    //--- Load plugin language file -------------------------------

                    $this->loadLanguage('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');

                    foreach ($matches as $usrDefinition) {
                        $insertHtml = '';

                        $replaceText  = $usrDefinition[0]; // develop check
                        $replaceLen   = strlen($usrDefinition[0]);
                        $replaceStart = strpos($article->text, $usrDefinition[0]);

                        //$test = $usrDefinition[1];
                        $usrParams = $this->extractUserParams($usrDefinition[1]);

                        // ToDo: check if gid is missing
                        $gid = $usrParams->get('gid', -1);
                        if ($gid < 1) {
                            $insertHtml = 'Plg RSG2 gallery: Gid missing "gid:xx,.." ';
                        }

                        // $insertHtml = Rsg2_imagesHelper::galleryImagesHtml();
                        $insertHtml = '<h4>--- Rsg2_images replacement ---</h4>' . $insertHtml;

                        // previous occurrences are replaced so the search will
                        // find the actual occurrence

                        $start =
                            //--- Perform the replacement ------------------------------

                        $article->text = substr_replace(
                            $article->text,
                            $insertHtml,
                            $replaceStart,
                            $replaceLen,
                        );
                    }
                }
            }
            // only needed when exchange the complete article ?
            // $event->stopPropagation();

        } catch (Exception $e) {
            $msg = Text::_('PLG_CONTENT_RSG2_IMAGES') . ' getRsg2_imagesDisplay: ' . ' Error (01): ' . $e->getMessage();
            $app = Factory::getApplication();
            $app->enqueueMessage($msg, 'error');

            return false;
        }


        return true;
    }

    private function extractUserParams(string $usrString)
    {
        $usrParams = new Registry();

        try {
            $usrString = trim($usrString);
            //$paramSets = explode('=', $usrParams);
            $paramSets = explode(',', $usrString);

            foreach ($paramSets as $paramSet) {
                if (!empty ($paramSet)) {
                    [$name, $value] = explode(':', $paramSet, 2);

                    $name  = trim($name);
                    $value = trim($value);

                    // ToDo: prepare indexed values template/layout ...
                    // Handle plugin specific variables or J3x to j4x transformations
                    // $isHandled = $this->handleSpecificParams ($params, $name, $value);

                    // ?? bool

                    // standard assignment
                    //if (! $isHandled)
                    {
                        $usrParams->set($name, $value);
                    }
                }
            }
        } catch (Exception $e) {
			$msg = Text::_('PLG_CONTENT_RSG2_IMAGES' . 'extractUserParams: "') . $usrString . '" Error (01): ' . $e->getMessage();
            $app = Factory::getApplication();
            $app->enqueueMessage($msg, 'error');

            return false;
        }

        return $usrParams;
    }
}
