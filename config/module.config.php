<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AppLocalization\Controller\Index'
                => 'AppLocalization\Controller\IndexController'
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'localization' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/:lang',
                    'constraints' => array(
                        'lang' => 'en|fr',
                    ),
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'AppLocalization\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'lang'          => 'en',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => [
                    //
                   
                ],
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'AppLocalization' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/localization'   => __DIR__ . '/../view/layout/layout.phtml',
        
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'Zend\Session\SessionManager' => 'Zend\Session\SessionManager',
        ),
    ),
);
