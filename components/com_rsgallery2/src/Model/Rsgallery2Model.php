<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Registry\Registry;

/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
     * @since      5.1.0
 */
class Rsgallery2Model extends BaseDatabaseModel
{
    /**
     * @var string item
     */
    protected $_item = null;

    /**
     * Gets a rsgallery2
     *
     * @param   integer  $pk  Id for the rsgallery2
     *
     * @return  mixed Object or null
     *
     * @since   5.1.0     */
    public function getItem($pk = null)
    {
        $app = Factory::getApplication();
        $pk  = $app->input->getInt('id');

        if ($this->_item === null) {
            $this->_item = [];
        }

        if (!isset($this->_item[$pk])) {
            try {
                $db    = $this->getDatabase();
                $query = $db->createQuery();

                $query
                    ->select('*')
                    ->from($db->quoteName('#__rsg2_galleries', 'a'))
                    ->where('a.id = ' . (int)$pk);

                $db->setQuery($query);
                $data = $db->loadObject();

                if (empty($data)) {
                    throw new Exception(Text::_('COM_RSGALLERY2_ERROR_RSGALLERY2_NOT_FOUND'), 404);
                }

                $this->_item[$pk] = $data;
            } catch (Exception $e) {
                $this->setError($e);
                $this->_item[$pk] = false;
            }
        }

        return $this->_item[$pk];
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return  void
     *
     * @since   5.1.0     */
    protected function populateState()
    {
        $app = Factory::getApplication();

        //$this->setState('rsgallery2.id', $app->input->getInt('id'));
        $this->setState('params', $app->getParams());
    }
}
