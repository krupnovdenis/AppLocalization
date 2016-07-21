<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'lang' => array(
        'ru' => array(
            'lang' => 'russion',
            'locale' => 'ru_RU',
        ),
        'en' => array(
            'lang' => 'english',
            'locale' => 'en_US',
        ),
        'es' => array(
            'lang' => 'espanol',
            'locale'=> 'es_ES',
        ),
        'it' => array(
            'lang' => 'italian',
            'locale'=> 'it_IT',
        ),
        'de' => array(
            'lang' => 'german',
            'locale'=> 'de_DE',
        ),
        'pt' => array(
            'lang' => 'portuguese',
            'locale'=> 'pt_BR',
        ),
        'fr' => array(
            'lang' => 'French',
            'locale'=> 'fr_FR',
        )
    )
);
