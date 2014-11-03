<?php 
Zend_Loader::loadClass('Zend_View');

class IndexController extends Zend_Controller_Action 
{ 
   public function init()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
    }
    	
    public function indexAction() 
    { 
      $this->view->assign('title', 'Главная страница');  
    } 


}