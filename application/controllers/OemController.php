<?php 
Zend_Loader::loadClass('Zend_View');

class OemController extends Zend_Controller_Action 
{ 
    public function init()
    {
        //������� ������
        require_once 'Zend/Session.php';
        Zend_Session::start();
        $this->view->baseUrl = $this->_request->getBaseUrl();
    }
    
    //������������� ����� ��� ������ OEM
    public function getFindOemForm()
    {
      require_once './application/models/Form/oemForm.php';
      $form = new OemForm();
      return $form;
     }


    //�������� �� ���������	
    public function indexAction() 
    { 
      $this->view->assign('title', '������ ������� �� ������ OEM');  
      $this->view->form = $this->getFindOemForm();
      
    } 

    //�������� ��� ������ ������
    public function findoemAction()
    {
      //������� ���� �� ��������
      $db=Zend_Registry::get('db');

      $form=$this->getFindOemForm();
      //��������� �� ������������
      //$request = $this->getRequest();
      $fn = $this->_getParam('findnum');
      if (isset($fn))
         $find_num=$fn;
      else if ($form->isValidPartial($_POST)) 
       {
        $values = $form->getValues();
        $find_num=$values['oem'];
       }
      else
       {
        return $this->_forward('index');
       }

        //������� ����� ������ � ����
        $historyTable=new Zend_Db_Table('history');        
        $historyTable->setDefaultAdapter($db);
        $data=array('findnum'=>$find_num,'sid'=>Zend_Session::getId());
        $historyTable->insert($data);

       $this->view->findNumber=$find_num;
       //���������� id_oem
       $select=$db->select()
                  ->from('oem');
       $select->where("oem='".$find_num."'");
       $stmt = $db->query($select);
       if ($row=$stmt->fetch())
        {
         $id_oem=$row['id_oem'];
        } 
        else
         $id_oem=0;

       //������� ���������� ������
       $this->getFindResults($id_oem);
     }

 
     //�������� ��� ������ ������ �� ID
     public function findidAction()
     {
      //������� ���� �� ��������
      $db=Zend_Registry::get('db');

      $form=$this->getFindOemForm();
      //��������� �� ������������
      //$request = $this->getRequest();
      $fnid = $this->_getParam('findnum');
      if (isset($fnid))
       {
         try
         {
          //���������� oem
          $select=$db->select()
                    ->from('oem');
          $select->where("id_oem=".$fnid);
          $stmt = $db->query($select);
          if ($row=$stmt->fetch())
           $find_num=$row['oem'];
          }
          catch (Exception $e) 
          {
           $find_num=$e->getMessage(); //��� �������
           }
         $this->view->findNumber=$find_num;

        //������� ����� ������ � ����
        $historyTable=new Zend_Db_Table('history');        
        $historyTable->setDefaultAdapter($db);
        $data=array('findnum'=>$find_num,'sid'=>Zend_Session::getId());
        $historyTable->insert($data);

        //������� ���������� ������
        $this->getFindResults($fnid);
       }
      else
       {
        return $this->_forward('index');
       }
     }

    //�������� ����� ������ (���� ��� ���������)
    public function findokAction()
    {
      $this->view->assign('title', '������ ������� �� ������ OEM '.$this->view->findNumber);  
      $this->view->form =$this->getFindOemForm();
    }

    //������� ������� ���������� �� ������ OEM ������
    public function getFindResults($id_oem)
    {
      try
       {
        //������� ���� �� ��������
        $db=Zend_Registry::get('db');
      
       //��������� ������ ������� ���� 
       $result="<div id=\"manger_first_findok_div\">";
        
       $select=$db->select()
                  ->from('oem');
       $select->where("id_oem=".$id_oem);
       $stmt = $db->query($select);
       if ($row=$stmt->fetch())
        {
         $find_num=$row['oem'];
         $groupname=$row['art_name'];
         $id_pic=$row['art_id'];
        }

        //����� � ����� ��������
        $result=$result."<div id=\"manger_findok_pic_div\" style=\"width:500px\"><div id=\"manger_findok_pic_head_div\" style=\"width:500px\" >
                         <font class=\"inet_magaizn_link_block_head_font\"><center>����������</center></font></div>";
        $result=$result."<div id=\"manger_findok_pic_body_div\" style=\"width:500px; margin-top:0.2em; margin-left:0.2em;\"><center><image src=\"http://newoffice.genstar.local:8080/pic/".$id_pic."\" width=\"480\"><div id=\"manger_findok_pic_body_div\" style=\"width:330px; margin-top:0.2em; \"><center><br><a href=\"http://www.genstar.com.ua/1280/buy.php?".$find_num."\" class=\"findok_result_result_link_font\">������ �� genstar.com.ua</a></center><br></div></div>";  
        $result=$result."</div>";
        
        //����� � ����� ���.������
        /*  $result=$result."<div id=\"manager_findok_tab\" style=\"width:322px\">"; //�������� �������
          $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:322px\">
                         <div id=\"manager_findok_tab_head\" style=\"width:322px\">
                          <center>
                           <font class=\"inet_magaizn_link_block_head_font\">".$groupname."  ".$find_num."</font>
                          </center>
                         </div>
                         </div>";
         //������� ���.������
         $select=$db->select()
                   ->from('criteria');
         $select->where("id_oem=".$id_oem);
         $stmt = $db->query($select);
         $param="";
         while ($row=$stmt->fetch())
          $param=$param.$row['criteria_text'].$row['criteria_value']."; ";

         $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:322px\"><font class=\"findok_result_font\">".
                         "<div id=\"manager_findok_tab_cell\" style=\"width:322px\">".$param."</div>".
 		         "</font></div>";
         $result=$result."</div>\n"; //����������� �������
       */

         //����� � ����� ���������
         $result=$result."<div id=\"manger_second_findok_div\">"; //�������� ���� manger_second_findok_div
         $select=$db->select()
                   ->from(array('agr'=>'agregat'),
                          array('id_oem'))
                   ->join(array('dt'=>'data'),
                                'agr.id_code=dt.id_agregat',
                                array('id_oem as dt_id_oem')); 
         $select->where('agr.id_oem='.$id_oem);
         $stmt = $db->query($select);
         $id_oem_arr=array();
         $j=0;
         while ($row= $stmt->fetch())
          {
           $id_oem_arr[$j]=$row['dt_id_oem'];
           $j++;
          }


         //������� ������� ��������� ��� �������� ������
         //�������� ������� ��� ������ ���������
         $result=$result."<div id=\"manager_findok_tab\" style=\" margin-top:1.5em; margin-left:0em; width:620px; background: #ffffff;\">"; 
         $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:620px;\">
                          <div id=\"manager_findok_tab_head\" style=\"width:620px;\">
                           <font class=\"inet_magaizn_link_block_head_font\"><center> ����������� ��� OEM: ".$find_num."<center></font>
                          </div>";
         $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:620px;\">
                         <div id=\"manager_findok_tab_head\" style=\"width:214px\">
                          <font class=\"inet_magaizn_link_block_head_font\">&nbsp;&nbsp;������ </font>
                         </div>
                         <div id=\"manager_findok_tab_head\" style=\"width:170px\">
                          <font class=\"inet_magaizn_link_block_head_font\" >&nbsp;&nbsp;����� OEM </font>
                         </div>
                         <div id=\"manager_findok_tab_head\" style=\"width:230px\">
                          <font class=\"inet_magaizn_link_block_head_font\">&nbsp;&nbsp;������������� </font>
                         </div></div>";

         for($i=0; $i<count($id_oem_arr); $i++)
          {
           $select=$db->select()
                      ->from('oem')
                      ->where("id_oem=".$id_oem_arr[$i]."");

            $stmt = $db->query($select);
        
      
            if ($row = $stmt->fetch()) {
             $manufacturer=$row['man'];

            if (strlen($row['art_name'])>1)
             $art_name=$row['art_name'];
            else
             $art_name="&nbsp;";

            if (strlen($manufacturer)<2)
             $manufacturer="��� ������";
            
            if (strlen($row['oem'])>6)
             $oem=substr($row['oem'],0,3)."***".substr($row['oem'],6);
            else
             $oem=substr($row['oem'],0,3)."***";

            $result=$result."<div id=\"manager_findok_tab_row\" style=\"width:621px; overflow:hidden;\"><font class=\"findok_result_font\">".
                           "<div id=\"manager_findok_tab_cell\" style=\"width:209px; overflow:hidden; height:22px;\">".$art_name."</div>".
                           "<div id=\"manager_findok_tab_cell\" style=\"width:165px; overflow:hidden; height:22px;\"><a href=\"".$rootDir."/fnid/".$row['id_oem']."\"
                           class=\"findok_result_result_link_font\">".$oem."</a></div>".
                           "<div id=\"manager_findok_tab_cell\" style=\"width:228px; overflow:hidden; height:22px;\">".$manufacturer."</div>\n".
    		           "</font></div>";
           }
          }
         $result=$result."</div>\n"; //����������� ������� ��� ������ ��������� �����������
        //����� � ����� ����������
        
        //��������� ���������� �� �����
        $result=$result."</div>"; //����������� ���� manger_second_findok_div
        $this->view->result=$result;
       } 
        catch (Exception $e) 
       {
        $this->view->result="������:".$e->getMessage()." select=".$select; //��� �������
       }
      
       //��������� �� �������� ������ �����������         
       return $this->_forward('findok');
    }

    //����� ������� ����������� (�� ����������! ������� ����������� �� �����)
    public function getfotoAction()
    {
      // disable view and layout 
      Zend_Layout::getMvcInstance()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(); 

     //������������ � ��������� � ��������� ���������� 
      //� ���� ������ .jpg � .bmp � ��������� ������ .jpg
     $picname = $this->_getParam('picname');
     $no_logo=1;
     $file_name=$_SERVER['DOCUMENT_ROOT']."/pics/".$picname.".jpg";
     if ($picname==0 || $picname=='0' || !file_exists($file_name))
      {
       $picname="NoPic";
       $no_logo=1;
      }
      
     $picname="http://newoffice.genstar.local:8080/pics/".$picname.".jpg";
     $logoname="http://newoffice.genstar.local:8080/pics/genstar.png";
     
     $info_img = getimagesize($picname); // �������� ���������� �����������
     list($width, $height) = $info_img; 
     $image = imagecreatefromjpeg($picname);  
     if ($no_logo==0)
      {
       // ����������� ���� �� ������� �����������
       imagealphablending($image, true); 
       $logo_image = imagecreatefrompng($logoname); 
       $logo_width = ImageSX($logo_image); 
       $logo_height = ImageSY($logo_image); 
       //imagecopy($image, $logo_image, $width-($logo_width+10), $height-($logo_height+10), 0, 0, $logo_width, $logo_height); 
       imagecopy($image, $logo_image, $width-400, $height/2, 0, 0, $logo_width, $logo_height); 
      }

     $this->getResponse()->setHeader('Content-Type', 'image/jpeg');
     imagejpeg($image);
     imagedestroy($image);
    }
}