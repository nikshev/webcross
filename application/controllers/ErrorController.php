<?php 
Zend_Loader::loadClass('Zend_View');

class ErrorController extends Zend_Controller_Action 
{ 
   public function init()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
    }
    	
    public function errorAction() 
    { 
      $this->view->assign('title', 'Ошибка');  
      $errors = $this->_getParam('error_handler');
      switch ($errors->type) {
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
          $this->view->result='HTTP/1.1 404 Not Found!';
         break; 
        default:
          $this->view->result='HTTP/1.1 404 Not Found! Неизвестная ошибка!';
          //print implode("\n",$this->getResponse()->getException());
         break;
      } 
     }
}