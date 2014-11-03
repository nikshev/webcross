<?php
class Bootstrap
{
            
   public function __construct($configSection = 'live')
    {
        $GLOBALS['startTime'] = microtime(true);
     
       $rootDir = dirname(dirname(__FILE__));
        define('ROOT_DIR', $rootDir);

        set_include_path(get_include_path()
            . PATH_SEPARATOR . ROOT_DIR . '/library/'
            . PATH_SEPARATOR . ROOT_DIR . '/application/models/'
	    );
        
        
      require_once 'Zend/Loader.php';
      require_once 'Zend/Layout.php';  
      Zend_Loader::loadClass('Zend_Registry');  
      Zend_Loader::loadClass('Zend_Config_Ini'); 
      Zend_Loader::loadClass('Zend_Db');
      Zend_Loader::loadClass('Zend_Db_Table');
      Zend_Loader::loadClass('Zend_Debug'); 
      Zend_Loader::loadClass('Zend_Controller_Front'); 
      Zend_Loader::loadClass('Zend_Controller_Router_Rewrite'); 
        // Load configuration
        Zend_Registry::set('configSection', $configSection);
        $config_ini = new Zend_Config_Ini('./application/config.ini', $configSection);
        
        //$config = new Zend_Config($configArray);
        $config = new Zend_Config($config_ini->db->toArray());
        Zend_Registry::set('config', $config);
        
        date_default_timezone_set('Europe/London'); 
        
        // configure database and store to the registery
        $db = Zend_Db::factory($config_ini->db->adapter,$config_ini->db->config->toArray());
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);

        $db_r = Zend_Db::factory($config_ini->db->adapter,$config_ini->db->config->toArray());
        Zend_Db_Table::setDefaultAdapter($db_r);
        Zend_Registry::set('db_r', $db_r);

        Zend_Layout::startMvc(array('layoutPath'=>ROOT_DIR.'/application/views/layouts',   
				    'layout'=>'index'));  
    }

    public function runApp()
    {
        // setup front controller
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->throwExceptions(false);
        $frontController->setControllerDirectory(ROOT_DIR . '/application/controllers');
        $frontController->setBaseUrl('/');
        $router=$frontController->getRouter();
        $router->addRoute("findoemnum", new Zend_Controller_Router_Route(
 				     "fn/:findnum",	
				     array("findnum"=>"0000000000",
					   "controller"=>"oem",	
					   "action"=>"findoem")));
        $router->addRoute("findfoto", new Zend_Controller_Router_Route(
 				     "pic/:picname",	
				     array("picname"=>"NoPic",
					   "controller"=>"oem",	
					   "action"=>"getfoto")));
        $router->addRoute("findid", new Zend_Controller_Router_Route(
 				     "fnid/:findnum",	
				     array("findnum"=>"0",
					   "controller"=>"oem",	
					   "action"=>"findid")));
        $router->addRoute("types", new Zend_Controller_Router_Route(
 				     "types/:typenum",	
				     array("typenum"=>"0",
					   "controller"=>"carsa",	
					   "action"=>"types")));
           // run!
        try {
            $frontController->dispatch();
        } catch (Exception $exception) {
                $msg = $exception->getMessage(); 
                $trace = $exception->getTraceAsString();
                echo "<div>Error: $msg<p><pre>$trace</pre></p></div>"; 
            }
        }
    

}