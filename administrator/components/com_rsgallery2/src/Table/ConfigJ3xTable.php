<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

use function defined;

/**
 * J3x config table
 *
 * @since __BUMP_VERSION__
 */
class ConfigJ3xTable extends Table
{
    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since __BUMP_VERSION__
     */
    public function __construct(DatabaseDriver $db)
    {
        $this->typeAlias = 'com_rsgallery2.image';

        parent::__construct('#__rsgallery2_config', 'id', $db);

        $this->access = (int)Factory::getApplication()->get('access');
    }

    // check ? auto

    // store ? auto

}
