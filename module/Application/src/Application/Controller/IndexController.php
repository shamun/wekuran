<?php

namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController {
  
  private $title = "Wekuran | ";
  private $keyword = "language crowdsourcing, translation services, language services";
  private $description = "Wekuran is best";


  //public function __construct(\Zend\Mvc\Router\RouteStack $router) { 
  public function __construct(){
    //you can now access the router used by the MVC application 
  }
  
  public function indexAction() {
    $this->layout()->setTemplate('layout/xtest'); 
    $r = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
    $r->headTitle($this->title . $this->description);
    $r->headMeta()->appendName('keywords', $this->keyword)->setIndent(8);
    $r->headMeta()->appendName('description', $this->description)->setIndent(8);
    $r->headMeta()->appendName('Language', 'en')->setIndent(8);
    $r->headMeta()->appendName('dc.title', $this->title)->setIndent(8);
    $r->headMeta()->appendName('dc.keywords', $this->keyword)->setIndent(8);
    $r->headMeta()->appendName('dc.description', $this->description)->setIndent(8);
    
    /* Facebook thumbnail generator for site LINK */
    $r->headMeta()->appendName('og:url', "http://www.wekuran.com")->setIndent(8);
    $r->headMeta()->appendName('og:title', $this->title)->setIndent(8);
    $r->headMeta()->appendName('og:image', "http://www.wekuran.com/images/logo.png")->setIndent(8);
    $r->headMeta()->appendName('og:description', $this->description)->setIndent(8);
    
    return new ViewModel();
    
  }
  
  

    

}
