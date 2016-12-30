<?php

$phpself=$_SERVER['PHP_SELF'];

require_once 'class.php';

require_once 'config_email.php';




session_start(); //Запускаем сессии

if(isset($_GET['viewtasks']))
 {
     $_SESSION["rights0"]='2';
 }

if($_SESSION["rights0"] != '0' && $_SESSION["rights0"] != '1' && $_SESSION["rights0"] != '2')
{
   
    header("Location: ./auth_user.php");
    echo "Access denied<br>";
    exit;
}

/*
 * Форма для управления задачами
 */


$content= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
     <HTML>
     <HEAD>
     <META http-equiv="Content-Type" content="text/html; charset=utf-8">';
     
if( $_SESSION["rights0"] == '2')
{
     $content.='<TITLE>Просмотр задач отдела 1С</TITLE>';
}
else
{
     $content.='<TITLE>Управление задачами отдела 1С</TITLE>';
}   
     $content.='<LINK rel="stylesheet" type="text/css" href="filecss.css" />
     
 <script type="text/javascript">
function findPos(obj) {
    var curtop = 0;
    if (obj.offsetParent) {
        do {
            curtop += obj.offsetTop;
            
//            document.write(obj.offsetTop);
//            document.write("Z");
//            document.write(curtop);
//            document.write("T");
//            document.write(obj.offsetParent);
//            document.write("t");
//            document.write(obj);
//            document.write("z");
            
        } while (obj = obj.offsetParent);
    return [curtop];
    }
}

</script>

<script src="scrollfix.js" type="text/javascript"></script>

     </HEAD>
     <BODY link="#000000" vlink="#000000" alink="#000000" onunload="unloadP(\'UniquePageNameHereScroll\')" onload="loadP(\'UniquePageNameHereScroll\')">
     <CENTER>';

  
     
  $auth = new AuthClass();
 
  $id_user=$auth->getID();
  
  
 $testname=$_SESSION["id_user0"];
 $test_iduser=$_SESSION["name0"];  
 $test_idex=$_SESSION["id_executor0"];
 $test_rights=$_SESSION["rights0"];
 

 //------------------------Обработка формы в таблицах-----------------------------------------
  
  include_once ("form_processing.php");
  
  //-------------------------------------------------------------------------
  
 
 if($_SESSION["rights0"] != '2')
 {
    $id_executor=$executors->getExecutorID($id_user);
   $extnumber=$task->getTaskExecutorExtNumber($id_executor);
   
  $content.='<P ALIGN="right">Логин: <b>'.$auth->getLogin().'</b><br>'.$auth->getName().'<br>Добавочный номер:<b>'.$extnumber.'</b>'.'<br><A href=./'.$phpself.'?is_exit=1>Выйти</A></P>'; //Показываем кнопку выхода
 }
 else {     
    $content.='<P ALIGN="right"><A href=./'.$phpself.'?is_exit=1>Выйти</A></P>'; //Показываем кнопку выхода 
} 
 


  
  
  
   

include_once("calldesk_client.php"); 


if( $_SESSION["rights0"] == '2')
{
  $content.="<font size=+1><b>Просмотр задач</b>";
}
//else
//{
//  $content.="<font size=+1><b>Управление задачами</b>"; 
//}    

  if (isset($_GET["is_exit"])) { //Если нажата кнопка выхода
    if ($_GET["is_exit"] == 1) {
        $auth->out(); //Выходим
        header("Location: index.php?is_exit=0"); //Редирект после выхода
    }
}
 




 $content.='<P ALIGN="left"><br>';
 $content.='<br><br>';
 $content.='<table><tr>'; 
 $content.='<td valign="top">';
 
 $content.='<table>'; //Левая вложенная таблица

 
 if(isset($_GET['tasks']))
 {
     if( $_SESSION["rights0"] == '2')
     {
       $content.='<tr><td>&nbsp</td></tr>';
     }
     else
     {
       $content.='<tr><td><b>Список задач</b></td></tr>';  
     }    
 }
 else 
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
     
     if($_SESSION["rights0"]==1)
     {
         $id_selfexecutor=$_SESSION["id_executor0"];
         //$content.='<tr><td><A href='.$phpself.'?viewtasks=1&tasks=1&statview=active&id_executor='.$id_selfexecutor.'>Список задач</A></td></tr>';
         $content.='<tr><td><A href='.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview=active&id_executor='.$id_selfexecutor.'>Список задач</A></td></tr>';
     }
     elseif($_SESSION["rights0"]==2)
     {
         $content.='<tr><td><A href='.$phpself.'?tasks=1>Список задач</A></td></tr>';
     }    
     else 
     {
         $id_selfexecutor=$_SESSION["id_executor0"];
         //$content.='<tr><td><A href='.$phpself.'?viewtasks=1&tasks=1&statview=active&id_executor=0>Список задач</A></td></tr>';
         //$content.='<tr><td><A href='.$phpself.'?tasks=1>Список задач</A></td></tr>';
         $content.='<tr><td><A href='.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview=active&id_executor='.$id_selfexecutor.'>Список задач</A></td></tr>';
         
     }
    
 }
 
 
if($_SESSION["rights0"]!=2)
{
     
  if(isset($_GET['addtask']))
 {
    $content.='<tr><td><b>Добавить задачу</b></td></tr>';
 }
 else 
 {
    $content.='<tr><td><A href='.$phpself.'?addtask=1>Добавить задачу</A></td></tr>';
 } 
    
 if(isset($_GET['authors']))
 {
   $content.='<tr><td><b>Авторы</b></td></tr>';
 }
 else 
 {
   $content.='<tr><td><A href='.$phpself.'?authors=1>Авторы</A></td></tr>';  
 }
 
if(isset($_GET['executors']))
 {
   $content.='<tr><td><b>Исполнители</b></td></tr>';
 }
 else 
 {
   $content.='<tr><td><A href='.$phpself.'?executors=1>Исполнители</A></td></tr>';  
 }
 
} 
 
 

 

 
 $content.='</table>'; //конец левой вложенной таблицы
  $content.='</td>';
  
  $content.='<td valign="top">';
  
  
  //Правая вложенная таблица
  
 
  
  
 if(isset($_GET['executors']))
 {      
    include_once ("executors.php");
 }
  
 if(isset($_GET['authors']))
 {      
    include_once ("list_authors.php");
 }
 
 if(isset($_GET['addtask']))
 {  
    include_once ("addtask.php");
 }
 
 
 if(isset($_GET['tasks']))
 {   
      include_once ("list_executors.php");   
 }
 
 
 
 
  $content.='</td>';
  
  
  $content.='</tr></table>';
  
 
 
 $content.='</P>';
  
 
$content.='
     </CENTER>';
     

$content.='<script>'
        . 'alert( getDocHeight() );'
        . '</script>';

$content.='</BODY>
     </HTML>';

 echo $content;
 
 
?> 