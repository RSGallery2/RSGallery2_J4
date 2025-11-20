<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\Exception\ResourceNotFound;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\Component\Media\Administrator\Model\ApiModel;
use Joomla\Database\DatabaseInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 *
 *
 * @since  4.2.0
 */
class VersionModel extends BaseModel
{
    /**
     * Instance of com_media's ApiModel
     *
     * @var ApiModel
     * @since  4.1.0
     */
//    private $versionApiModel;

    public function __construct($config = [])
    {
        parent::__construct($config);

//        $this->versionApiModel = new ApiModel();
    }

    /**
     * Method to get a single files or folder.
     *
     * @return  \stdClass  A file or folder object.
     *
     * @since   4.1.0
     * @throws  ResourceNotFound
     */
    public function getItem()
    {

        $componentName = 'com_rsgallery2';

        $oVersion = new \stdClass();
        $oVersion->version = "xx.xx.xx";
        $oVersion->creationDate = "2025.xx.xx";

        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            $query = $db->createQuery()
                ->select($db->quoteName('manifest_cache'))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote($componentName));
            $db->setQuery($query);

            $manifest = json_decode($db->loadResult(), true);

            $oVersion->version = $manifest['version'];
            $oVersion->creationDate = $manifest['creationDate'];
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $oVersion;
    }
}
