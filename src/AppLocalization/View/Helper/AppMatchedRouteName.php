<?php
namespace AppLocalization\View\Helper;

use Zend\View\Helper\AbstractHelper;

use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\HelperPluginManager;

class AppMatchedRouteName extends AbstractHelper implements ServiceLocatorAwareInterface {

    private $serviceLocator;

    public function __invoke($newParams = array()) {
        /** @var \Zend\Mvc\Router\RouteMatch $routeMatch */
        $routeMatch = $this->serviceLocator->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();
        $oldParams = $routeMatch->getParams();
        $params = array_merge($oldParams, $newParams);

        /** @var \Zend\View\HelperPluginManager $viewHelperController */
        $viewHelperController = $this->getServiceLocator()->getServiceLocator()->get('ViewHelperManager');

        /** @var \Zend\View\Helper\Url $urlHelper */
        $urlHelper = $viewHelperController->get('url');

        $trueUrl = $urlHelper(null, $params);
        return $trueUrl;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }
}