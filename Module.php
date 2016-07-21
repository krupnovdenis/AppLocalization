<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/AppLocalization for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AppLocalization;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Session\Container as ContainerSession;
use Zend\Validator\AbstractValidator;
use Zend\Debug\Debug;

class Module implements 
    AutoloaderProviderInterface,
    ViewHelperProviderInterface
{
    public function getControllerPluginConfig()
    {
        return [
            'invokables' => [
                'lang' => 'AppLocalization\Controller\Plugin\Lang',
            ]
        ];
        
    }
    
    public function getServiceConfig()
    {
        return array();
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getEventManager()->attach(
            MvcEvent::EVENT_ROUTE,
            function($e) {
                $this->setLocalization($e);
            },
            -1000
        );
        
        //редирект с home на application
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            MvcEvent::EVENT_DISPATCH,
            function($e) {
                $controller = $e->getTarget();
                $ContainerSession    = new ContainerSession();
                $lang = $controller->params()->fromRoute('lang', $ContainerSession->lang);
        
                if ('home' === $e->getRouteMatch()->getMatchedRouteName()
                    || 'application' === $e->getRouteMatch()->getMatchedRouteName()) {
                    $controller->plugin('redirect')->toRoute('localization', array('lang' => $lang));
                }
            });
        
        /*
         * Автоматическое присвоение языка
         * 1. Смена языка ?chLang
         * 2. Язык без необходимости менять view->url
         */
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            MvcEvent::EVENT_DISPATCH,
            function($e) {
                $controller             = $e->getTarget();
                $ContainerSession       = new ContainerSession();
                $lang = $controller->params()->fromRoute('lang', $ContainerSession->lang);
                
                /*
                 * Устанвливаем язык сессии из chLang и язык сайта
                 * */
                
                if (is_null($controller->getRequest()->getQuery('chLang', null))) {
                    return false;
                }
                
                //?chLang
                $ContainerSession->lang = $controller->getRequest()->getQuery('chLang');
                
                $e->getRouteMatch()->setParam('lang', $ContainerSession->lang);
               
                return $controller->plugin('redirect')->toRoute(
                    $e->getRouteMatch()->getMatchedRouteName(), array(
                        'lang' => $ContainerSession->lang
                        )
                    );
        }, 10);
        
        //fix layout
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractActionController', 
            MvcEvent::EVENT_DISPATCH, 
            function(MvcEvent $e) {
                $controller      = $e->getTarget();
                $controllerClass = get_class($controller);
                
                $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
//                             echo $moduleNamespace, '<hr/>';
                $config          = $e->getApplication()->getServiceManager()->get('config');
            
                if (isset($config['module_layouts'][$moduleNamespace])) {
                    $controller->layout($config['module_layouts'][$moduleNamespace]);
                }
        }, 100);
        
    }

    
    function setLocalization(MvcEvent $e) {
        $ContainerSession    = new ContainerSession();
        
        /*
         * @TODO  'en' выставляться должен в настройках Options
         */
        $baseLanguage = ( $ContainerSession->lang) ? $ContainerSession->lang : 'en';
        $ContainerSession->lang = $e->getRouteMatch()->getParam('lang', $baseLanguage);
        
        /*
         * url default [lang] fix - 
         */
        $e->getRouter()->setDefaultParam('lang', $ContainerSession->lang);
        
        $config     = $e->getApplication()->getServiceManager()->get('config');
        $translator = $e->getApplication()->getServiceManager('translator');
        
        $locale = $config['lang'][$ContainerSession->lang]['locale'];
        //         echo $ContainerSession->lang .', '. $locale;
        $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');
        
        
        if (isset($config['lang'][$ContainerSession->lang])){
            //устанавливаем локаль на определенный язык
            $translator->setLocale( $config['lang'][$ContainerSession->lang]['locale']);
        } else {
            //устанавливаем локаль на неизвестный/не существующий язык - по дефолту
            $lang = array_shift( $config['lang']);
            $translator->setLocale( $lang['locale']);
        }
        /*
         * @todo смотреть как называются языки ViewHelper
         */
        if ($ContainerSession->lang == 'pt') $ContainerSession->lang = 'pt_BR';
        
        /*
         * @todo массив неподдерживаемых языков
         * */
        $lang_file = ($ContainerSession->lang == 'lv') ? 'en' : $ContainerSession->lang ;
        $translator->addTranslationFile(
            'phpArray',
            './vendor/zendframework/zend-i18n-resources/languages/'.$lang_file.'/Zend_Validate.php'
        );

        AbstractValidator::setDefaultTranslator( $translator);
    }
    
    function getViewHelperConfig() {
        return [
            'invokables' => [
                'appRouteAction'        => 'AppLocalization\View\Helper\AppRouteAction',
                'appMatchedRouteName'   => 'AppLocalization\View\Helper\AppMatchedRouteName',
                'lang'                  => 'AppLocalization\View\Helper\AppSessionLang',
            ],
        ];
    }
}
