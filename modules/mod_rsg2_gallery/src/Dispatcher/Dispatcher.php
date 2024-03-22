<?php

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

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

        $helper      = $this->getHelperFactory()
            ->getHelper('ExampleHelper');
        $data['msg'] = $helper->getData($data['params'], $this->getApplication());

        $helper      = $this->getHelperFactory()
            ->getHelper('ExampleHelper');
        $data['msg'] = $helper->getMsg($data['params'], $this->getApplication());

        return $data;
    }

}
