<?php
/**
 * Author: bduhr
 * Date: 28/05/14
 * Time: 15:05
 */

namespace W3cValidatorToolbar\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class W3cValidatorControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        return new W3cValidatorController(
            $parentLocator
        );
    }
}
