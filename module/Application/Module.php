<?php
namespace Application;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http;

class Module {

  public function onBootstrap(MvcEvent $e) { 

    $e->getApplication()->getServiceManager()->get('translator');
    $eventManager = $e->getApplication()->getEventManager();
    // This is for preDispatch 
    //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 100);
    $moduleRouteListener = new ModuleRouteListener();
    $moduleRouteListener->attach($eventManager);
    $router = $e->getApplication()->getServiceManager()->get('router'); 
    
    
    
    $route = Http\Segment::factory(array(
      'route' => '/test',
      'defaults' => array(
        'controller' => 'Application\Controller\Test',
        'action' => 'index'
      ),
    ));
    $router->addRoute('tesindex', $route, null);
    
    // Languages 
    $route = Http\Segment::factory(array(
      'route' => '/en',
      'defaults' => array(
        'controller' => 'Application\Controller\Index',
        'action' => 'index'
      ),
    ));
    $router->addRoute('enindex', $route, null);
          

    // English sub pages    
    $route = Http\Segment::factory(array(
      'route' => '/order[/:action]',
      'defaults' => array(
        'controller' => 'Application\Controller\Order',
        'action' => 'index'
      ),
    ));    
    $router->addRoute('order', $route, null);   
    
    
    
    
    $route = Http\Literal::factory(array(
      'route' => '/contact-us',
      'defaults' => array(
        'controller' => 'Application\Controller\Contactus',
        'action' => 'index'
      ),
    ));
    $router->addRoute('contactus', $route, null);        
    
    
    $route = Http\Segment::factory(array(
      'route' => '/ajax[/:action]',
      'defaults' => array(
        'controller' => 'Application\Controller\Ajax',
        'action' => 'index'
      ),
    ));
    $router->addRoute('ajax', $route, null);
    
          
    $route = Http\Segment::factory(array(
      'route' => '/sitemap.xml',
      'defaults' => array(
        'controller' => 'Application\Controller\Sitemap',
        'action' => 'index'
      ),
    ));
    $router->addRoute('sitemapxml', $route, null); 
    
    
    
  }


  public function getConfig() {
    return include __DIR__ . '/config/module.config.php';
  }

  public function getAutoloaderConfig() {
    return array(
      'Zend\Loader\ClassMapAutoloader' => array(
            __DIR__ . '/autoload_classmap.php',
        ),
      'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
          __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
        ),
      ),
    );
  }
  
  public function getServiceConfig() {
    return array(
      'initializers' => array(

          function ($instance, $sm) {

              if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {

              $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));

              }

          }

        ),
        'factories' => array(
            'Application\Model\GlobalAccess' =>  function($sm){
              $table     = new Model\GlobalAccess();
              return $table;
            },
        ),

    );
  }

}
