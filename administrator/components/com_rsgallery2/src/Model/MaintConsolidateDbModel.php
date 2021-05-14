<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use JModelLegacy;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;

class MaintConsolidateDbModel extends BaseDatabaseModel
{

    public static function GetImageReferences()
    {
        $xxx = [];

        try
        {
        	/**
            $db    = Factory::getDbo();
            $query = $db->getQuery(true);

            // count gallery items
            $query->select('COUNT(*)')
                // ignore root item  where id is "1"
                ->where($db->quoteName('id') . ' != 1')
                ->from('#__rsg2_galleries');

            $db->setQuery($query, 0, 1);
            $IdGallery          = $db->loadResult();

            // > 0 galleries exist
            $xxx = !empty ($IdGallery);
	        /**/
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error in GetImageReferences' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $xxx;
    }




}

