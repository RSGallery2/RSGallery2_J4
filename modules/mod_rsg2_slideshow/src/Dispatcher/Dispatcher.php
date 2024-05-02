<?php

namespace Rsgallery2\Module\Rsg2_slideshow\Site\Dispatcher;

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
            ->getHelper('Rsg2_slideshowHelper', $data);

		// ToDo flag that tells to identify ...
//        $msg = $helper->getText();
//        $data['msg'] = $msg;

        $appParams = $data['params'];
        $app =  $data['app'];

        //--- merge com_rsg2 parameters -------------------------------------------------

        // Load the parameters.
        $rsg2Params = Factory::getApplication('com_rsgallery2')->getParams();
        // $params_array = $rsg2Params->toArray();

        // merge
        $mergedParams = new Registry ($rsg2Params);
        $mergedParams->merge ($appParams, true);

	    $data['params'] = $mergedParams;

	    //--- debug flags -----------------------------------------------------

	    $data['isDebugSite'] = $mergedParams->get('isDebugSite');
	    $data['isDevelopSite'] = $mergedParams->get('isDevelop');

	    //--- gallery id ------------------------------------------

        $gid = $appParams['gid'];
        $data['gid'] = $gid;

        // gid = 0 ==> root view
        $isNoGallerySelected = $gid == 0;
        if ($isNoGallerySelected)
        {
            // Tell to select a gallery in the module instead
            $msg = Text::_('COM_RSGALLERY2_GALLERY_NOT_SPECIFIED_MODULE');
            $data['msg'] = $msg;

            // !!! exit !!!
            return $data;
        }

        //--- gallery data -----------------------------------------------------

        $galleryData = $helper->getGalleryData($gid);
        $data['galleryData'] = $galleryData;

        //--- gallery images -----------------------------------------------------

        $images = $helper->getImagesOfGallery($gid, $mergedParams, $app);

	    if (empty($images)) {
		    // Tell to select a gallery in the module instead
		    $msg = Text::_('COM_RSGALLERY2_NO_IMAGES_IN_GALLERY_MODULE');
		    $data['msg'] = $msg;

		    // !!! exit !!!
		    return $data;
	    }

	    $data['images'] = $images;

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
