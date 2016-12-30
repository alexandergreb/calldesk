<?php

require_once 'class.php';

$content= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
     <HTML>
     <HEAD>
     <META http-equiv="Content-Type" content="text/html; charset=utf-8">
     <TITLE>Управление задачами отдела 1С</TITLE>
     <LINK rel="stylesheet" type="text/css" href="filecss.css" />
     </HEAD>
     <BODY link="#000000" vlink="#000000" alink="#000000">

     <CENTER>';

$content.='<br><br><br>';


if(isset($_POST['viewtasks']))
{
    
     if(isset($_GET['begindate_b']))
{
  $begindate_b=$_GET['begindate_b'];  
}
else
{
  $begindate_b=date('d-m-Y',strtotime("first day of previous month"));  
}    

if(isset($_GET['begindate_e']))
{
  $begindate_e=$_GET['begindate_e'];  
}
else
{
  $begindate_e=date('d-m-Y');  
}     
    
  //header('Location: ./menu_list.php?viewtasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview=active&tasks=1&id_executor=0');
   header('Location: ./menu_list.php?viewtasks=1&tasks=1&begindate_b=dd-mm-yyyy&begindate_e=dd-mm-yyyy&statview=active&id_executor=0');
}    

if(isset($_POST['managetasks']))
{
  header("Location: ./auth_user.php");
}    



 $content.='<form method="POST">';
 $content.='<table>';
 $content.='<tr><td align="center"><button class="formbutton" name="viewtasks" title="Просмотр задач" value="Просмотр задач отдела 1С"><font size=+1>Просмотр задач</font></button></td></tr>';
 $content.='<tr><td align="center">&nbsp</td></tr>';
 $content.='<tr><td align="center"><button class="formbutton" name="managetasks" title="Редактирование задач" value="Редактирование задач отдела 1С"><font size=+1>Редактирование задач</font></button></td></tr>';
 $content.='</table>';
$content.='</form>';
 
$content.='
     </CENTER>
     </BODY>
     </HTML>';
 echo $content;
