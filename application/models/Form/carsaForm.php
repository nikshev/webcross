<?php
 /*
  Форма логина
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

    //Достаем базу из регистра для заполнения массива автомобилей
    $db=Zend_Registry::get('db');
    $select=$db->select()
              ->from("cars");
    $select->order('car');
    $stmt = $db->query($select);
    //$car_array = $stmt->fetchAll(PDO::FETCH_COLUMN,1);

    $this->addElementPrefixPath('App_Form_Decorator', 'App/Form/Decorator/', 'decorator');//Для того чтоб мона было найти кнопку
    //Создание и конфигурирование элемента carsSelect
    $carsSelect= $this->createElement('select', 'carsSelect');
    $carsSelect->setAttrib('id', 'carsSelect');
    $carsSelect->setAttrib('onChange', "javascript:$.php(\"/carsa/fillmodels\",{selindex:this.options[this.selectedIndex].value});return false;");
    $carsSelect->setLabel('Автомобиль:');
    while ($row=$stmt->fetch())
       $carsSelect->addMultiOptions(array("".$row["id_car"].""=>$row["car"]));
    $this->addElement($carsSelect);
     

    //Создание и конфигурирование элемента ModelsSelect
    $ModelsSelect= $this->createElement('select', 'modelsSelect');
    $ModelsSelect->setAttrib('id', 'modelsSelect');
    $ModelsSelect->setLabel('Модель:');
    $this->addElement($ModelsSelect);

     //Создания кнопки 'Найти':
     $submit=new Zend_Form_Element_Submit('findCars',array(
                 'required'    => true,
                 'label'       => 'Найти'));

     $submit->setAttrib('class','find');   
     $this->addElement($submit);
   }
 }