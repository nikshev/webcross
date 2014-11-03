<?
 $picname="2167";
 
 if ($picname==0 || $picname=='0' || !file_exists($file_name))
  {
   $picname="NoPic";
   $no_logo=1;
  }
 echo $picname."  ".$file_name;
?>