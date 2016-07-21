<?php
namespace AppLocalization\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container as ContainerSession;

class Lang extends AbstractPlugin
{
    function __invoke()
    {
        $SessionContainer = new ContainerSession();
        if ($SessionContainer->lang == 'pt_BR') {
            return 'pt';
        }
        return $SessionContainer->lang;
    }
}