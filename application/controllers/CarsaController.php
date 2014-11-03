<?php 
Zend_Loader::loadClass('Zend_View');

class CarsaController extends Zend_Controller_Action 
{ 
   public function init()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
    }
    	
    //������������� ����� ��� ������ �� ������ �������
    public function getFindCarsForm()
    {
      require_once './application/models/Form/carsaForm.php';
      $form = new CarsaForm();
      return $form;
     }
   
    //�������� �� ��������� 	
    public function indexAction() 
    { 
      $this->view->assign('title', '������ ������� �� ������ ����������');  
      $this->view->headScript()->appendFile('/public/js/jquery.js');
      $this->view->headScript()->appendFile('/public/js/jquery.php.js');
      $this->view->form = $this->getFindCarsForm();
    }

   //���������� ������� 
   public function fillmodelsAction()
    {
      
     try
      {
       // check is AJAX request or not
       if (!$this->getRequest() -> isXmlHttpRequest()) {
          $this->getResponse()-> setHttpResponseCode(404)
                              -> sendHeaders();
          $this->renderScript('empty.phtml');
          return false;
         }
         // requery php library
         require_once 'jQuery.php';
         //������� �� ���� ������ ��� ������� ����
         $carselect=$_POST["selindex"];  
         
         $db=Zend_Registry::get('db');
         $select=new Zend_Db_Select($db);
         $select->from("models",array('ID','id_model','model'))
                ->where("id_car=".$carselect);

         $stmt = $db->query($select);
         $result="";
         $rows = $stmt->fetchAll();
         $strarray=array();
         for($i=0; $i<$stmt->rowCount(); $i++)
          $result=$result.="<option value=\"".$rows[$i]["id_model"]."\" label=\"".$this->cleanStr($rows[$i]["model"])."\">".$this->cleanStr($rows[$i]["model"])."</option>";
         jQuery('#modelsSelect')->html($result);
        
       }
      catch(Exception $e)
       {
        jQuery('#modelsSelect')->html("exception:".$e->getMessage());
       }
    } 

      
    //������� �� ������� ������ "�����"(����������)
    public function findAction()
    {
      $this->view->result=$this->view->result.$this->getFindResults($_POST["modelsSelect"]);
      try
       {
        //���������� �������� ����������
        $db=Zend_Registry::get('db');
        $select=new Zend_Db_Select($db);
        $select->from("models")
               ->where("id_model=".$_POST["modelsSelect"]);
        $select->limit(1,0);
        $stmt = $db->query($select);
        if ($row=$stmt->fetch())
         {
          $model_name=$row["model"];
          $id_car=$row["id_car"];
          $db=Zend_Registry::get('db');
          $select=new Zend_Db_Select($db);
          $select->from("cars")
                 ->where("id_car=".$id_car);
           $stmt = $db->query($select);
           if ($row=$stmt->fetch())
           {
            $carname=$row["car"];
           }
         }
       }
       catch (Exception $e) 
        {
         $model="������:".$e->getMessage()." select=".$select; //��� �������
        }

      $this->view->assign('title', '����� ������� �� ����������');  
      $this->view->head_str=" ".$carname." ".$model_name;
      $this->view->headScript()->appendFile('/public/js/jquery.js');
      $this->view->headScript()->appendFile('/public/js/jquery.php.js');
      $this->view->form = $this->getFindCarsForm();
    }

    //����� ����� � ����� �� ���������� ������
    public function getFindResults($model)
    {
       $db=Zend_Registry::get('db');
       $result="";
       $width=602;
       $result=$result."<div id=\"manger_findok_pic_div\" style=\"width:599px\"><div id=\"manger_findok_pic_head_div\" style=\"width:".$width."px\" >
                        <font class=\"inet_magaizn_link_block_head_font\"><center>����� ���� ���������</center></font></div>";
       $result=$result."</div>";

      try
       {
         $select=new Zend_Db_Select($db);
         $select->from("types")
                ->where("id_model=".$model);
         $stmt = $db->query($select);
         $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:601px\"><font class=\"findok_result_font\">".
                         "<div id=\"manager_findok_tab_head\" style=\"width:255px\"><font class=\"inet_magaizn_link_block_head_font\"><center>��� ���������</center></font></div><div id=\"manager_findok_tab_head\" style=\"width:170px\"><font class=\"inet_magaizn_link_block_head_font\"><center>������ </center></font></div><div id=\"manager_findok_tab_head\" style=\"width:170px\"><font class=\"inet_magaizn_link_block_head_font\"><center>�����</center></font></div></div>";

         while ($row=$stmt->fetch())
          {
           $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:".$width."px\"><font class=\"findok_result_font\">".
                         "<div id=\"manager_findok_tab_cell\" style=\"width:251px;height:22px;\">&nbsp;<a href=\"http://newoffice.genstar.local:8080/types/"
                         .$row['ID']."\"
                         class=\"findok_result_result_link_font\">&nbsp;".$row['type']."</a></div>".
                         "<div id=\"manager_findok_tab_cell\" style=\"width:166px;height:22px;\">&nbsp;".substr($row["pconstart"],4).".".substr($row["pconstart"],0,4)."</div>".
                         "<div id=\"manager_findok_tab_cell\" style=\"width:165px;height:22px;\">&nbsp;".substr($row["pconend"],4).".".substr($row["pconend"],0,4)."</div>".
   	                 "</font></div>";
          }
        }
       catch (Exception $e) 
        {
         $result="������:".$e->getMessage()." select=".$select; //��� �������
        }
      return $result;
    }

    //����� ������� �� ���������� ������ � ���� ���������
    public function typesAction()
    {
       $id=$this->_getParam('typenum');
       $db=Zend_Registry::get('db');
       //����� � ����� ������ �� ����������
        try
        {
         $select=new Zend_Db_Select($db);
         $select->from("types")
                ->where("id=".$id);
         $select->limit(1,0);
         $stmt = $db->query($select);
         if ($row=$stmt->fetch())
          {
           $model_id=$row["id_model"]; //������������� ������
           $id_type=$row["id_type"];//������������� ����
           $typename=$row["type"]; //��� 
           $select=new Zend_Db_Select($db);
           $select->from("models")
                  ->where("id_model=".$model_id);
           $select->limit(1,0);
           $stmt = $db->query($select);
           if ($row=$stmt->fetch())
            {
             $model_name=$row["model"]; //������
             $id_car=$row["id_car"]; //������������� ����������

             $select=new Zend_Db_Select($db);
             $select->from("cars")
                    ->where("id_car=".$id_car);
             $stmt = $db->query($select);
             if ($row=$stmt->fetch())
             {
              $carname=$row["car"]; //����������
             }
          }
        }
        }
        catch (Exception $e) 
        {
         $result="������:".$e->getMessage()." select=".$select; //��� �������
        }

        $result="";
        $width=602;
        $result=$result."<div id=\"manger_findok_pic_div\" style=\"width:599px\"><div id=\"manger_findok_pic_head_div\" style=\"width:".$width."px\" >
                         <font class=\"inet_magaizn_link_block_head_font\"><center>������</center></font></div>";
        $result=$result."</div>";
        $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:601px\"><font class=\"findok_result_font\">".
                         "<div id=\"manager_findok_tab_head\" style=\"width:299px\"><font class=\"inet_magaizn_link_block_head_font\"><center>������</center></font></div><div id=\"manager_findok_tab_head\" style=\"width:298px\"><font class=\"inet_magaizn_link_block_head_font\"><center>OEM �����</center></font></div></div>";

       //����� � ����� ������ �� ����������
      try
       {
        $select=new Zend_Db_Select($db);
        $select->from(array('types'),array('id_type'));
        $select->join(array('base'),'base.id_type=types.id_type',array('id_oem'));
        $select->join(array('oem'),'base.id_oem=oem.id_oem',array('art_name','oem'));   
        $select->where('types.id_type='.$id_type);
        $select->where('types.id_model='.$model_id);
        $select->group(array('types.id_type','types.id_model','base.id_oem'));
 
        $stmt = $db->query($select);
         while ($row=$stmt->fetch())
          {
           $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:".$width."px\"><font class=\"findok_result_font\">".           
                           "<div id=\"manager_findok_tab_cell\" style=\"width:294px; height=22px;\">&nbsp;".$row["art_name"]."</div>".
                           "<div id=\"manager_findok_tab_cell\" style=\"width:294px; height=22px;\">&nbsp;<a href=\"http://newoffice.genstar.local:8080/fnid/".$row['id_oem']."\"
                           class=\"findok_result_result_link_font\">&nbsp;".$row["oem"]."</a></div></div></font>";
                         
          }
        }
        catch (Exception $e) 
        {
         $result="������:".$e->getMessage()." select=".$select; //��� �������
        }
        //$result=$result."</div></div><br>";

     $this->view->assign('title', '����� ������� �� ����������');  
     $this->view->head_str=" ".$carname." ".$model_name." ".$typename;
     $this->view->headScript()->appendFile('/public/js/jquery.js');
     $this->view->headScript()->appendFile('/public/js/jquery.php.js');
     $this->view->form = $this->getFindCarsForm();
     $this->view->result=$result."</font>";
    }

 //������� ������
 public function cleanStr($str)
 {
  for ($i=0; $i<strlen($str); $i++)
   if (ord($str[$i])==160) $str[$i]=' ';
  return $str;
 }

}