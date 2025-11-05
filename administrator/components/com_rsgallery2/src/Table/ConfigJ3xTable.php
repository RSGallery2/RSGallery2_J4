<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Table;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * J3x config table
 *
     * @since      5.1.0
 */
class ConfigJ3xTable extends Table
{
	public $access = null;

    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since   5.1.0     */
    public function __construct(DatabaseDriver $db)
    {
        $this->typeAlias = 'com_rsgallery2.image';

        parent::__construct('#__rsgallery2_config', 'id', $db);

        $this->access = (int)Factory::getApplication()->get('access');
    }

    // check ? auto

    // store ? auto

}
