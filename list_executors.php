<?php

 $aa = $executors->ListExecutors();
 
 $content.='<table border=0><tr><td valign="top">';
 
 $viewtasktable=0;
 
 if(isset($_GET['begindate_b']))
{
  $begindate_b=$_GET['begindate_b'];  
}
else
{
    $begindate_b=date('d-m-Y',strtotime("first day of  previous month"));  
  //$begindate_b=date('d-m-Y',strtotime("first day of this month"));  
}    

if(isset($_GET['begindate_e']))
{
  $begindate_e=$_GET['begindate_e'];  
}
else
{
  $begindate_e=date('d-m-Y');  
}    
 
 if(isset($_GET['id_executor']))
 {  
  $id_executor1=$_GET['id_executor'];
 }
 else
 {
  $id_executor1=0; 
 }    
 
 if(isset($_GET['statview']))
 {  
  $statview=$_GET['statview'];
 }
 else
 {
  $statview='active';
 }  
 
 
 if($_SESSION["rights0"]!=1)
 {
     
  if($begindate_b== 'dd-mm-yyyy' && $begindate_e=='dd-mm-yyyy' && $_GET['statview']=='active')
{
   //$content.='<b>Все активные<br>задачи</b></A><br><br>'; 
      $content.='<b>Задачи <br>которые в работе</b></A><br><br>';
}
else
{    
 //$content.='<A href='.$phpself.'?tasks=1&begindate_b=dd-mm-yyyy&begindate_e=dd-mm-yyyy&statview=active&id_executor=0>Все активные <br> задачи</A><br><br>';
    $content.='<A href='.$phpself.'?tasks=1&begindate_b=dd-mm-yyyy&begindate_e=dd-mm-yyyy&statview=active&id_executor=0>Задачи <br>которые в работе</A><br><br>';
}   
     
     
$begindate_b2=date('d-m-Y',strtotime("first day of previous month"));
$begindate_e2=date('d-m-Y',strtotime("last day of previous month"));
if($begindate_b2==$_GET['begindate_b'] && $begindate_e2==$_GET['begindate_e'] && $_GET['statview']=='closed')
{
   $content.='<b>Задачи <br>выполненые<br> в прошлом месяце</b></A><br><br>'; 
}
else
{    
 $content.='<A href='.$phpself.'?tasks=1&begindate_b='.$begindate_b2.'&begindate_e='.$begindate_e2.'&statview=closed&id_executor=0>Задачи <br>выполненые<br> в прошлом месяце</A><br><br>';
}
 
$begindate_b3=date('d-m-Y',strtotime("first day of this month"));
$begindate_e3=date('d-m-Y',strtotime("last day of this month"));
if($begindate_b3==$_GET['begindate_b'] && $begindate_e3==$_GET['begindate_e'] && $_GET['statview']=='closed')
{
   $content.='<b>Задачи <br>выполненые<br> в этом месяце</b></A><br><br>'; 
}
else
{    
 $content.='<A href='.$phpself.'?tasks=1&begindate_b='.$begindate_b3.'&begindate_e='.$begindate_e3.'&statview=closed&id_executor=0>Задачи <br>выполненые<br> в этом месяце</A><br><br>';
}   
     
   //вложенная легенда для фильтра: статус, список исполнителей  и период
  $content.="<fieldset style=\"width: 30%\">";
$content.="<legend>Статус</legend>";   
$content.="<DIV style=\"text-align: left\">";


//if($_GET['statview']=='all')
if($statview=='all')
{  
 $content.='<b>Все</b><br>'; 
}
else
{
  $content.='<A href='.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview=all&id_executor='.$id_executor1.'>Все</A><br>';  
}    

//if($_GET['statview']=='active')
if($statview=='active')
{ 
    $content.='<b>Активный</b><br>';   
}
else
{    
 $content.='<A href='.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview=active&id_executor='.$id_executor1.'>Активный</A><br>';
}

$content.="</DIV>";
  $content.="</fieldset><br>";   

 $content.='<script src="calendar_ru.js" type="text/javascript"></script>';
$content.="<fieldset style=\"width: 30%\">";
$content.="<legend>Период</legend>";
$content.="<DIV style=\"text-align: left\">";
$content.='<form>';
if($statview == 'closed')
{
 $content.='по дате окончания<br>';
}
else
{
 $content.='по дате создания<br>';  
}    
$content.='<input type="hidden" name="id_executor" value="'.$id_executor1.'">';
$content.='<input type="hidden" name="statview" value="'.$statview.'">';
$content.='<input type="hidden" name="tasks" value="1">';
$content.='с<input type="text" maxlength="8" size="8" name="begindate_b" value="'.$begindate_b.'" onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)"><br>
по<input type="text" maxlength="8" size="8" name="begindate_e" value="'.$begindate_e.'" onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)"><br><br>';

$content.='<input type="submit" name="showperiod" value="Показать">';
$content.='</form>';
$content.="</table>\n";          
$content.="</DIV>";
$content.="</fieldset><br>";
  
  
$content.="<fieldset style=\"width: 30%\">";
$content.="<legend>Исполнитель</legend>";   
$content.="<DIV style=\"text-align: left\">";

$ntasks = $task->CountTasks('0',$statview,$begindate_b,$begindate_e);
$content.="<table border=0>\n";
if($_GET['id_executor']=='0')
          {   
           $content.='<tr><td><b>Все</b></td><td>'.$ntasks.'</td></tr>'."\n";
           $viewtasktable=1;
          }
          else
          {    
           $content.='<tr><td><A href='.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor=0>Все</A></td><td>'.$ntasks.'</td></tr>'."\n";  
          } 
          
          foreach($aa as $id_executor  => $val)
           {
            $ntasks = $task->CountTasks($id_executor,$statview,$begindate_b,$begindate_e);
           if(isset($_GET['id_executor']) && $id_executor==$_GET['id_executor'])
                {
                  $content.='<tr><td><b>'.$aa[$id_executor]['name'].'</b></td><td>'.$ntasks.'</td></tr>'."\n";
                  $viewtasktable=1;                  
                }
                else 
                {
                    $content.='<tr><td><A href='.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'>'.$aa[$id_executor]['name'].'</A></td><td>'.$ntasks.'</td></tr>'."\n";  
                }
           
             //$content.='<tr><td><b>'.$aa[$id_executor]['name'].'</b></td></tr>';
           } 

$content.="</table>\n";          
$content.="</DIV>";
$content.="</fieldset><br>";   



 
 
   
   
   
   
   //конец вложенной таблицы (список исполнителей)
 }
  else
  {
    $content.='&nbsp';  
    $viewtasktable=1;
  }   
   $content.='</td>';
   $content.='<td>';
   if($viewtasktable==1)
   {   
    include_once ("tasklist.php");
   } 
   $content.='</td></tr>';
   $content.='</table>';
   
   
         
         
?>


