<?php
 /*
  Форма логина
 */
 Zend_Loader::loadClass('Zend_Form');
 Zend_Loader::loadClass('Zend_Form_Element_Text');
 Zend_Loader::loadClass('Zend_Form_Element_Password');
 Zend_Loader::loadClass('Zend_Form_Element_Submit');
 class OemForm extends Zend_Form
 {
 
  public function init()
   {
    $this->setAction('/oem/findoem')
         ->setMethod('post')
         ->setAttrib('id', 'oemForm');

    $this->addElementPrefixPath('App_Form_Decorator', 'App/Form/Decorator/', 'decorator');//Для того чтоб мона было найти кнопку
    //Создание и конфигурирование элемента username
    $findOem = new Zend_Form_Element_Text('oem', array(
                'required'    => true,
                'label'       => 'Номер OEM:',
                'maxlength'   => '30',
                'validators'  => array(
                array('StringLength', true, array(0, 30))
             ),
            'filters'     => array('StringTrim'),
        ));
    
     $findOem->setAttrib('class','text'); 
     //Удаляем все существующие декораторы, назначенные по умолчанию
     $findOem->clearDecorators();
        
     // Назначаем новые, включая наш декоратор Calendar
     // Это необходимо для того что бы изображение календаря размещалось сразу за полем ввода
     $findOem
            ->addDecorator('ViewHelper')
            ->addDecorator('Button')
            ->addDecorator('Errors')            
            ->addDecorator('HtmlTag', array('tag' => 'dd'))
            ->addDecorator('Label', array('tag' => 'dt'));
    $this->addElement($findOem);
   }
 }