<?php
 /*
  ����� ������
 */
 Zend_Loader::loadClass('Zend_Form');
 Zend_Loader::loadClass('Zend_Form_Element_Text');
 Zend_Loader::loadClass('Zend_Form_Element_Password');
 Zend_Loader::loadClass('Zend_Form_Element_Submit');

 class CarsaForm extends Zend_Form
 {
 
  public function init()
   {
    $this->setAction('/carsa/find')
         ->setMethod('post')
         ->setAttrib('id', 'carsForm');

    //������� ���� �� �������� ��� ���������� ������� �����������
    $db=Zend_Registry::get('db');
    $select=$db->select()
              ->from("cars");
    $select->order('car');
    $stmt = $db->query($select);
    //$car_array = $stmt->fetchAll(PDO::FETCH_COLUMN,1);

    $this->addElementPrefixPath('App_Form_Decorator', 'App/Form/Decorator/', 'decorator');//��� ���� ���� ���� ���� ����� ������
    //�������� � ���������������� �������� carsSelect
    $carsSelect= $this->createElement('select', 'carsSelect');
    $carsSelect->setAttrib('id', 'carsSelect');
    $carsSelect->setAttrib('onChange', "javascript:$.php(\"/carsa/fillmodels\",{selindex:this.options[this.selectedIndex].value});return false;");
    $carsSelect->setLabel('����������:');
    while ($row=$stmt->fetch())
       $carsSelect->addMultiOptions(array("".$row["id_car"].""=>$row["car"]));
    $this->addElement($carsSelect);
     

    //�������� � ���������������� �������� ModelsSelect
    $ModelsSelect= $this->createElement('select', 'modelsSelect');
    $ModelsSelect->setAttrib('id', 'modelsSelect');
    $ModelsSelect->setLabel('������:');
    $this->addElement($ModelsSelect);

     //�������� ������ '�����':
     $submit=new Zend_Form_Element_Submit('findCars',array(
                 'required'    => true,
                 'label'       => '�����'));

     $submit->setAttrib('class','find');   
     $this->addElement($submit);
   }
 }