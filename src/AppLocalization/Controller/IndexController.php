<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/AppLocalization for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AppLocalization\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Zend\Json\Json;
use Zend\Debug\Debug;
use Acex\Entity\Country as CountryEntity;
use Zend\Db\Sql\Predicate\Like;
use Zend\View\Model\JsonModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        
    }
}
