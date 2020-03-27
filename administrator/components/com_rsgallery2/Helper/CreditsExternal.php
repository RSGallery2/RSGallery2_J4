<?php
/**
 * Hall of Fame in the RSGallery2 project
 * Credits: Historical list of people participating in the project
 *
 * @version       $Id: CreditsExternal.php  2012-07-09 18:52:20Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003-2019 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

namespace Joomla\Component\Rsgallery2\Administrator\Helper;

defined('_JEXEC') or die();

class CreditsExternal
{
	/** use for external libraries
	<h4>extension name </h4>
	<dl>
		<dt>Name</dt>
		<dd>link, copyright and License</dd>
	</dl>
	/**/

	const CreditsExternalText = <<<EOT

	<h3>Used external components</h3>
	<p>Actually for RSG2 (J!4x 2019/2020) there is no external component used besides libraries provided by joomla! itself. It is expected that with the implementation of slide shows and similar functions libraries will appear here </p>

	<h3>Honorable mentions go to</h3> 
	<h4>jetbrains phpstorm</h4>
	<dl>
		<dt>phpstorm</dt>
		<dd>RSGallery2 is developed with <a href="https://www.jetbrains.com/phpstorm/">phpstorm</a> since 2015  </dd>
	</dl>
EOT;
}
