<?php
 /*
  ����� ������
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

    $this->addElementPrefixPath('App_Form_Decorator', 'App/Form/Decorator/', 'decorator');//��� ���� ���� ���� ���� ����� ������
    //�������� � ���������������� �������� username
    $findOem = new Zend_Form_Element_Text('oem', array(
                'required'    => true,
                'label'       => '����� OEM:',
                'maxlength'   => '30',
                'validators'  => array(
                array('StringLength', true, array(0, 30))
             ),
            'filters'     => array('StringTrim'),
        ));
    
     $findOem->setAttrib('class','text'); 
     //������� ��� ������������ ����������, ����������� �� ���������
     $findOem->clearDecorators();
        
     // ��������� �����, ������� ��� ��������� Calendar
     // ��� ���������� ��� ���� ��� �� ����������� ��������� ����������� ����� �� ����� �����
     $findOem
            ->addDecorator('ViewHelper')
            ->addDecorator('Button')
            ->addDecorator('Errors')            
            ->addDecorator('HtmlTag', array('tag' => 'dd'))
            ->addDecorator('Label', array('tag' => 'dt'));
    $this->addElement($findOem);
   }
 }