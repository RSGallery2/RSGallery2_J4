<?php

namespace Rsgallery2\Module\Rsg2_gallery\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

\defined('_JEXEC') or die;

class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();

//        $data['msg'] = $this->getHelperFactory()
//            ->getHelper('ExampleHelper')
//            ->getData($data['params'], $this->getApplication());
//

        $data['msg'] = "    --- Rsg2_gallery module ----- ";

        return $data;
    }

}
