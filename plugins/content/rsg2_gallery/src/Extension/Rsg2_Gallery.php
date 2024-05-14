<?php

namespace RSGallery2\Plugin\Content\Rsg2_Gallery\Extension;

/**
 * @package     Joomla.Plugin
 * @subpackage  Content.contact
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;

class Rsg2_Gallery extends CMSPlugin implements SubscriberInterface
{
	// only for lang strings shown on execution of plugin
	protected $autoloadLanguage = true;

	public static function getSubscribedEvents () : array
	{
		return ['onContentPrepare' => 'getRsg2_galleryDisplay'];
	}

	public function getRsg2_galleryDisplay (Event $event)
	{
		$context = $event->getArgument('context');

		if (strpos ($context , 'com_content.article') === false) {
			return;
		}

		$article = $event->getArgument('1');

//		// Simple high performance check to determine whether bot should process further.
//		if (stripos($article->text, '{rsg2_gallery') === false) {
//			return null;
//		}

		try {

			//--- replacement may exist --------------------------------------------------

			if (str_contains($article->text, '{rsg2_gallery'))
			{
				$lastUserTestIdx = 0;

				//--- collect all appearances ---------------------------------------

				// Expression to search for.
				$pattern = "/{rsg2_gallery:(.*?)}/i";

				preg_match_all($pattern, $article->text, $matches, PREG_SET_ORDER);

				// debug: there should be matches as text is searched
				if(empty ($matches)) {
					echo "<br><br>!!! article has no rsg2_gallery !!!<br>";
					return null;
				}


				// No matches, skip this
				if ($matches)
				{
					//--- Load plugin language file -------------------------------

					$this->loadLanguage('com_rsgallery2', JPATH_SITE . '/components/com_rsgallery2');



					foreach ($matches as $usrDefinition)
					{

						$test = trim($usrDefinition[1]);

						$replaceText = $usrDefinition[1];
						$replaceLen = strlen($usrDefinition[1]);
						$replaceStart = strpos($article->text, $usrDefinition[1]);

						$matcheslist = explode(',', $usrDefinition[1]);


						$userParams = ""; // ToDo: ....yyyy


						$insertImages = Rsg2_galleryHelper::HtmlImages();

						// previous occurrences are replaced so the search will
						// find the actual occurrence

						$start =
						//--- Perform the replacement ------------------------------

						$article->text = substr_replace(
							$article->text,
							$insertImages,
							$replaceStart,
							$replaceLen);

					}
				}
			}
			// only needed when exchange the complete article ?
			// $event->stopPropagation();

		} catch (Exception $e) {
			$msg = Text::_('PLG_CONTENT_RSG2_GALLERY') . ' Error (01): ' . $e->getMessage();
			$app = Factory::getApplication();
			$app->enqueueMessage($msg, 'error');
			return false;
		}


		return true;
	}









}
