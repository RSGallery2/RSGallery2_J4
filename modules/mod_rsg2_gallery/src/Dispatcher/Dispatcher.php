<?php

namespace Rsgallery2\Module\Rsg2_gallery\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

\defined('_JEXEC') or die;

class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    protected function getLayoutData(): array
    {
        // module(self) params, input , app, ? module? , ,
        $data = parent::getLayoutData();

        $helper = $this->getHelperFactory()
            ->getHelper('Rsg2_galleryHelper', $data);

//        $msg = $helper->getText();
//        $data['msg'] = $msg;

        $appParams = $data['params'];
        $app =  $data['app'];

        //--- merge com_rsg2 parameters -------------------------------------------------

        // Load the parameters.
        $rsg2Params = Factory::getApplication('com_rsgallery2')->getParams();
        $params_array = $rsg2Params->toArray();

        // merge
        // ? order first rsg2 or first app ?
        $mergedParams = new Registry ($rsg2Params);
        $mergedParams->merge ($appParams, true);

        //--- gallery id ------------------------------------------

        $gid = $appParams['gid'];
        $data['gid'] = $gid;

        // gid = 0 ==> root view
        $isDisplayRootGalleries = $gid === 0;
        if ($isDisplayRootGalleries)
        {
            // Tell to select a gallery in the module instead
            $msg = Text::_('COM_RSGALLERY2_SELECT_GALLERY_MODULE');
            $data['msg'] = $msg;

            // !!! exit !!!
            return $data;
        }

        //--- gallery data -----------------------------------------------------

//        $this->isDebugSite = $params->get('isDebugSite');
//        $this->isDevelopSite = $params->get('isDevelop');

        //--- gallery data -----------------------------------------------------

        $galleryData = $helper->getGalleryData($gid);
        $data['galleryData'] = $galleryData;

        //--- gallery images -----------------------------------------------------

        $images = $helper->getImagesOfGallery($gid, $mergedParams, $app);


//        $this->items      = $this->get('Items');
//
//        $model = $this->getModel();
//        $this->gallery = $model->galleryData($this->galleryId);
//
//
//        if ( ! empty($this->items)) {
//            // Add image paths, image params ...
//            $data = $model->AddLayoutData ($this->items);
//        }
//
//        // Flag indicates to not add limitstart=0 to URL
//        $this->pagination->hideEmptyLimitstart = true;
//
//

        return $data;
    }

}
