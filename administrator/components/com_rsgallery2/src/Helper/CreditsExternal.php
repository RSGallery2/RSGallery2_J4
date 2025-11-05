<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;
// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

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
              
              <h3>Honorable mentions go to jetbrains phpstorm</h3>
              <dl>
              	<dt>phpstorm</dt>
              	<dd>RSGallery2 is developed with <a href="https://www.jetbrains.com/phpstorm/">phpstorm</a> since 2015  </dd>
              </dl>
              EOT;
}
