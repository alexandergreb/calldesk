<?php

/* 
 * Таблица списка задач
 */

$statustask['1']="Активная";
$statustask['2']="Закрытая";


$statuslist['1']="Активная";
$statuslist['2']="Выполненая";
$statuslist['3']="Отменённая";

$statusexec['1']="Активная";
$statusexec['2']="Выполнена с текущей датой";
$statusexec['3']="Выполнена с указанным сроком";
$statusexec['4']="Отменённая";


$condition['1']="Выполняется в сроке";
$condition['2']="Выполнена в срок";
$condition['3']="Выполнена с отложенным сроком";
$condition['4']="Выполнена с опережением";
$condition['5']="Просроченная";
$condition['6']="Продлённая";
$condition['7']="Отложенная";
$condition['8']="Отменённая";


$prioritylist['0']="Обычный";
$prioritylist['1']="Высокий";

 /*
 * Направление сортировки по столбикам
 */
     $sortdir=$_GET['sortdir'];
     
     if($sortdir=='up')
     {
        $sortdir='down';
     }
     else
     {
        $sortdir='up'; 
     }    
 
      
     $begindate_b=$_GET['begindate_b'];
     $begindate_e=$_GET['begindate_e'];
     
     $id_executor=$_GET['id_executor'];
     $executorname=$task->getExecutorName($id_executor);
     //$content.="<center><font size=+1>Исполнитель: <b>$executorname</b></font></center><br>";
     
     
     
     $content.='<script src="calendar_ru.js" type="text/javascript"></script>';
     $content.='<table class="formtable">
     <tr>
     <th colspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=id_task&sortdir='.$sortdir.'">№</a>
     </th>
     <th colspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=taskname&sortdir='.$sortdir.'">Описание задачи</a>
     </th>';
     //if($id_executor=='0')
     //{    
      $content.='<th colspan=1>
      <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=id_executor&sortdir='.$sortdir.'">Исполнитель</a>
      </th>';
     //}
     $content.='<th rowspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=id_taskauthor&sortdir='.$sortdir.'">Автор</a>
     </th>
     <th rowspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=comment&sortdir='.$sortdir.'">Комментарий</a>
     </th>
     <th rowspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=editbegindate&sortdir='.$sortdir.'">Дата<br>создания</a>
     </th>
     <th rowspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=editenddate&sortdir='.$sortdir.'">Дата <br>окончания</a>
     </th>
     <th rowspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=priority&sortdir='.$sortdir.'">Приоритет</a>
     </th>    
     <th rowspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=condition&sortdir='.$sortdir.'">Состояние</a>
     </th>
     <th rowspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=authornotify&sortdir='.$sortdir.'">e-mail<br>уведомление</a>
     </th>
     <th rowspan=1>
     <a href="'.$phpself.'?tasks=1&begindate_b='.$begindate_b.'&begindate_e='.$begindate_e.'&statview='.$statview.'&id_executor='.$id_executor.'&sort=status&sortdir='.$sortdir.'">Статус</a>
     </th>';

      if( $_SESSION["rights0"] != '2')
     {
       $content.='<th colspan=1 rowspan=1>
        Действие
        </th>';
     }
   
     $content.='</tr>';
     
     $statview=$_GET['statview'];
          
    if($_GET['sort']=='id_task' || !isset($_GET['sort']))
    {
     if($_GET['sortdir']=='down' || !isset($_GET['sortdir']))
     {   
      $aa = $task->ListTasks($_GET['id_executor'],'DESC',$statview,$begindate_b,$begindate_e);
     }
     else
     {
      $aa = $task->ListTasks($_GET['id_executor'],'INC', $statview,$begindate_b,$begindate_e);    
     }    
     $a=$aa;
    } 
    else
    {
     $aa = $task->ListTasks($_GET['id_executor'],'INC',$statview,$begindate_b,$begindate_e);
      $sortkey=$_GET['sort']; 
      $a = $task->SortCol($aa,$sortkey,$sortdir);
    }    
     
    $b= $task -> ListNotify();
    
      //  $aa = $sales->ListSales($id_client);
     /*   
        if($_GET['sort']=='name_TT')
        {
           foreach($aa as $id_sale  => $val)
           { 
            $a[$id_sale]=$tt->getName($aa[$id_sale]['id_tt']);           
           }  
           
         if($sortdir=='up' || $sortdir=='')
         {
          asort($a);
         }
         else
         {
          arsort($a);
         }
           
        }
        elseif($_GET['sort']=='name_sotr')
        {
           foreach($aa as $id_sale  => $val)
           { 
            $a[$id_sale]=$workers->getName($aa[$id_sale]['id_sotr']);           
           }
           
         if($sortdir=='up' || $sortdir=='')
         {
          asort($a);
         }
         else
         {
          arsort($a);
         }
           
        }
        else
        {    
  
      */
         //$a=$aa;
        
         
  //      }
        
      
        
        $i=0;
           foreach($a as $id_task  => $val)
           {
        $id_condition=$task->ConditionTask($id_task);     
        $authorname=$task->getAuthorName($aa[$id_task]['id_author']);          
        $taskauthorname=$task->getTaskAuthorName($aa[$id_task]['id_taskauthor']);
        
        $content.='<form method="POST">';
        
            if($i==1)
            {
            $content.='<tr>';
            $i=0;
            }
            else 
            {$content.='<tr class="darktr" id="'.$id_task.'">'; $i=1;}
   
//=============================================================
        //  if(isset($_POST['updatetask']) && ( $id_task==$_POST['id_edittask'] ) && $aa[$id_task]['status']==1 && $_SESSION["rights0"]!=2) // Строка в таблице для изменения значений
            if(isset($_POST['updatetask']) && ( $id_task==$_POST['id_edittask'] ) && $_SESSION["rights0"]!=2) // Строка в таблице для изменения значений
          {
              
   $id_sale=$_POST['id_sale']; 
   $year=$_POST['year'];
   $month=$_POST['month'];
   $id_tt=$_POST['id_tt'];
   $id_sotr=$_POST['id_sotr'];
   $kol=$_POST['kol'];
   
   $autornotify=$_POST['autornotify'];
 
   
  
   $content.='
<script> 
    $("form#myform").submit(function(){
   $(this).append("<input type=\'hidden\' name=\'scrollTop\' value=\'"+$(document).scrollTop()+"\'>");
   $(this).append("<input type=\'hidden\' name=\'scrollLeft\' value=\'"+$(document).scrollLeft()+"\'>");
});    
</script>
';
   
 $content.='
<script>
$(document).ready(function(){
   <?php
      if(isset($_REQUEST["scrollTop"]) && isset($_REQUEST["scrollLeft"]))
         echo "window.scrollTo(".$_REQUEST["scrollTop"].",".$_REQUEST["scrollLeft"].")";
   ?>
});
</script>
';

   

   
   /*
   $content.='<script type="text/javascript">
window.scroll(0,findPos(document.getElementById("'.$id_sale.'")));
  </script>';
   */
 
     $content.='<td>'.$id_task.'</td>';
//     $content.='<td maxlength="100" style="width: 380;">'.$aa[$id_task]['taskname'].'</td>';
   //  $content.='<td><input name="taskname" value='.$aa[$id_task]['taskname'].' type="text" size="80" maxlength="100"></td>';
     $content.='<td><input name="taskname" value='.$aa[$id_task]['taskname'].' type="text"></td>';
     $content.='<td>'.$executorname.'</td>';
     
       //$content.='<td maxlength="100" style="width: 180;">'.$authorname.'</td>';
     $content.='<td maxlength="100" style="width: 180;">'.$taskauthorname.'</td>';
      $content.='<td>
            <textarea name="comment" rows=10 cols=80>'.$aa[$id_task]['comment'].'</textarea></td>';
      
         $content.='<td size="50" maxlength="50" style="width: 80;">'.$task->RusDate($aa[$id_task]['editbegindate']).'</td>';
          
         $content.='<td><input type="text" name="editenddate" value="'.$task->RusDate($aa[$id_task]['editenddate']).'" onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)"></td>';
                 
             $content.='<td><input type="hidden" name="status" value="'.$aa[$id_task]['status'].'">';          
            
            $content.='<select name="priority" size="0" style="width: 80;">';
                      
            foreach($prioritylist as $id_priority  => $val)
            { 
              if($id_priority==$aa[$id_task]['priority'])
              {    
               $content.='<option value="'.$aa[$id_task]['priority'].'" selected>'.$prioritylist[$aa[$id_task]['priority']].'</option>'; 
              }
              else
              {    
               $content.='<option value="'.$id_priority.'">'.$val.'</option>';
              } 
            }
            
             $content.='</select></td>';        
                          
             
     //$content.='<td size="11" maxlength="11" align="right">'.$condition['1'].'&nbsp</td>';
     
     if($id_condition==5)
         {        
            $content.='<td size="11" maxlength="11" align="right" class="warn"><b><font color="red">'.$condition[$id_condition].'</font></b>&nbsp</td>';
         }
         else
         {
            $content.='<td size="11" maxlength="11" align="right">'.$condition[$id_condition].'&nbsp</td>'; 
         }    
     
         if($authornotify == '1')
                {     
                  $content.='<td align="center" size="11" maxlength="11" align="right"><input type="checkbox" name="authornotify" value="1" checked></td>';
                } 
                elseif($authornotify == '0')
                {
                  $content.='<td align="center" size="11" maxlength="11" align="right"><input type="checkbox" name="authornotify" value="0"></td>';
                }
                else 
                {
                  $content.='<td align="center" size="11" maxlength="11" align="right">&nbsp</td>';     
                }
         
     
     $content.='<td>
            
            <select name="statusexec" size="0" style="width: 80;">';
            foreach($statusexec as $id_statusexec  => $val)
            { 
               if($id_statusexec==1) 
               {    
                $content.='<option value="'.$id_statusexec.'" selected>'.$val.'</option>';
                
               }
               else
               {
                $content.='<option value="'.$id_statusexec.'">'.$val.'</option>';  
               }    
               
            }
            
             $content.='</select></td>';
             
             

             
     
     $content.='<td colspan="1" align="center">';
     $content.='<input type="hidden" name="id_task" value="'.$id_task.'">';
     $content.='<input name="update_task" type="submit" title="Изменить" value="Изменить"></td></tr>
             ';              
              
          }
        //================================================  
            else
            {   
       
                
      
       
       // $bdate=$aa[$id_task]['editbegindate'];
       // $d1=DateTime::createFromFormat('Y-m-d',$aa[$id_task]['editbegindate']);
       // $s1=strtotime($d1->format('Y-m-d'));
       //echo "DDDDDDDDDDDDDDDD $s1<br>";
    
        
        
       //$id_condition=$task->ConditionTask($id_task);
    $content.='
            <td>'.$id_task.'</td>
            <td maxlength="100" style="width: 380;">'.$aa[$id_task]['taskname'].'</td>';
             $executorname=$task->getExecutorName($aa[$id_task]['id_executor1']);
             $content.='<td>'.$executorname.'</td>';   
            $content.='<td maxlength="100" style="width: 180;"><i title="'.$authorname.' добавил задачу">'.$taskauthorname.'</i></td>';
           //    $content.='<td size="11" maxlength="11" align="right">'.$aa[$id_task]['comment'].'&nbsp</td>';
      //$commentlines=substr_count($aa[$id_task]['comment'],"\n");
    
    $commentlines=substr_count($aa[$id_task]['comment'],PHP_EOL);
     
    
    if($commentlines>=1)
    {
       $commentlines=$commentlines+1; 
    }
    
        if($j==0)
            {
            $content.='<td>
                        <textarea rows='.$commentlines.' cols=80 style="color: black; background-color: #f5f5e8">'.$aa[$id_task]['comment'].'</textarea></td>';  
            $j=1;
            }
            else 
            {    
               //$content.='<td>
                        //<textarea rows='.$commentlines.' cols=80 style="color: red; background-color: lightyellow">'.$aa[$id_task]['comment'].'</textarea></td>';
               $content.='<td>
                        <textarea rows='.$commentlines.' cols=80 style="color: black; background-color: #f5deb3">'.$aa[$id_task]['comment'].'</textarea></td>';  
              $j=0;  
            }
    
            
            
    $content.='<td size="50" maxlength="30" style="width: 80;">'.$task->RusDate($aa[$id_task]['editbegindate']).'</td>
            <td size="50" maxlength="30" style="width: 80;">'.$task->RusDate($aa[$id_task]['editenddate']).'</td>';
            
       
            
            $content.='<td size="11" maxlength="11" align="right">'.$prioritylist[$aa[$id_task]['priority']].'&nbsp</td>';
            
                if($id_condition==5)
                {        
                  $content.='<td size="11" maxlength="11" align="right" class="warn"><b><font color="red">'.$condition[$id_condition].'</font></b>&nbsp</td>';
                }
                else
                {
                  $content.='<td size="11" maxlength="11" align="right">'.$condition[$id_condition].'&nbsp</td>';
                }    
                
                
                $content.='<td align="center" size="11" maxlength="11" align="right">';
                $content.='<input type="hidden" name="authornotify" value="'.$b[$id_task]['authornotify'].'">';
                
                if($b[$id_task]['authornotify'] == '1')
                {     
                  $content.='Отправлять';
                } 
                elseif($b[$id_task]['authornotify'] == '0')
                {
                  $content.='Не отпавлять';
                }
                elseif($b[$id_task]['authornotify'] == '2')
                {
                  $content.='Уже отправлено';
                }
                else 
                {
                  $content.='&nbsp';     
                }
                
                $content.='</td>';
                
                
                if($aa[$id_task]['status']==1)
                {
                  $statustask="Активная";
                } 
                else
                {
                  $statustask="Закрытая";                      
                }    
                
           // $content.='<td size="11" maxlength="11" align="right">'.$statuslist[$aa[$id_task]['status']].'&nbsp</td>';
             $content.='<td size="11" maxlength="11" align="right">'.$statustask.'&nbsp</td>'; 
            $content.='<!--
            <td><input name="updatesale" type="submit" title="Изменить" value="Изменить"></td>
            <td><input name="delsale" type="submit" title="Удалить" value="Удалить"></td>
            -->
          
            <input type="hidden" name="id_edittask" value="'.$id_task.'">';
             
           // if($aa[$id_task]['status']=='1')
           // {    
               if( $_SESSION["rights0"] != '2')
               {   
                $content.='<td align="center"><button class="formbutton" name="updatetask" alt="Редактировать" title="Редактировать" value="Редактировать"><img src="pics/pencil_20.png"></button></td>';
               } 
           // }
           // else
           // {
           //    $content.='&nbsp';  
           // }   
            
            }
            
           $content.='<tr>';
    
        $content.='</form>';
        
           }
     $content.='</table>';
     
//     $content.="\n</div>";
     
?>


