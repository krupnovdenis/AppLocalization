<?php
namespace AppLocalization\View\Helper;

use Zend\View\Helper\AbstractHelper;
class AppRouteAction extends AbstractHelper
{
    public function __invoke() {
        $routeMatch = $this->getView()->getHelperPluginManager()->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();
        return $routeMatch->getParam('action');
    }
}

