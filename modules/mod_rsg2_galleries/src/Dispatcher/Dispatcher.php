<?php
/**
 * @package        RSGallery2
 * @subpackage     mod_rsg2_galleries
 * @copyright  (c)  2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * @author         finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Module\Rsg2_galleries\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

use function defined;

defined('_JEXEC') or die;

class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    protected function getLayoutData(): array
    {
        // module(self) params, input , app, ? module? , ,
        $data = parent::getLayoutData();

        $helper = $this
            ->getHelperFactory()
            ->getHelper('Rsg2_galleriesHelper', $data);

        // ToDo flag that tells to identify ...
//        $msg = $helper->getText();
//        $data['msg'] = $msg;

        $appParams = $data['params'];
        $app       = $data['app'];

        //--- merge com_rsg2 parameters -------------------------------------------------

        // Load the parameters.
        $rsg2Params = Factory::getApplication('com_rsgallery2')->getParams();
        // $params_array = $rsg2Params->toArray();

        // merge
        $mergedParams = new Registry ($rsg2Params);
        $mergedParams->merge($appParams, true);

        $data['params'] = $mergedParams;

        //--- debug flags -----------------------------------------------------

        $data['isDebugSite']   = $mergedParams->get('isDebugSite');
        $data['isDevelopSite'] = $mergedParams->get('isDevelop');

        //--- gallery id ------------------------------------------

        $gid         = $appParams['gid'];
        $data['gid'] = $gid;

        // gid = 0 ==> root view
        $isRootGallerySelected = $gid == 0;
        if (!$isRootGallerySelected) {
            //--- gallery data -----------------------------------------------------

            $galleryData         = $helper->getGalleryData($gid);
            $data['galleryData'] = $galleryData;
        }

        //--- galleries  -----------------------------------------------------

        $galleries = $helper->getGalleriesOfGallery($gid, $mergedParams, $app);

        if (empty($galleries)) {
            // Tell to select a gallery in the module instead
            $msg         = Text::_('COM_RSGALLERY2_NO_SUB_GALLEIES_IN_GALLERIES_MODULE');
            $data['msg'] = $msg;

            // !!! exit !!!
            return $data;
        }

        $data['galleries'] = $galleries;

        return $data;
    }

}
