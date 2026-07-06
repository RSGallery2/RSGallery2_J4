<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\Exception\ResourceNotFound;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Component\Media\Administrator\Model\ApiModel;
use Joomla\Database\DatabaseInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since 5.0.10
 */
class LatestGalleryModel extends BaseDatabaseModel
{
//    public function __construct($config = [])
//    {
//        parent::__construct($config);
//    }

    /**
     * Method to get latest gallery data
     *
     * @return  \stdClass  A file or folder object.
     *
     * @since   4.1.0
     * @throws  ResourceNotFound
     */
    public function getItem()
    {
        $oGallery = new \stdClass();

        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            $limit = 1;

            $query = $db->createQuery()
                ->select('*')
                ->from('#__rsg2_galleries')
                ->order($db->quoteName('id') . ' DESC')
                ->setLimit($limit);
            $db->setQuery($query);

            $oGallery = $db->loadObject();

        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $oGallery;
    }

}
