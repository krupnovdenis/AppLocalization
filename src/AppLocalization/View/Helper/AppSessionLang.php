<?php
namespace AppLocalization\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container as ContainerSession;
class AppSessionLang extends AbstractHelper
{
    function __invoke()
    {
        $SessionContainer = new ContainerSession();
        //@todo Массив недостающих языков
        if ($SessionContainer->lang == 'pt_BR') {
            return 'pt';
        }
        return $SessionContainer->lang;
    }
}