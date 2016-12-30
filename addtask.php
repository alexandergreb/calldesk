<?php

/* 
 * Таблица добавления задания заданий
 */

/*
 * Направление сортировки по столбикам
 */
    
$aa = $executors->ListExecutors();
$aaa=$authors->ListАuthors();

$aaaa = $authors->SortCol($aaa,'authorname',$sortdir);

     $content.='<table class="formtable">
     ';
             
     
      
     $content.='
          <form method="POST">
          

            <tr>
            <input type="hidden" name="id_executor" value="'.$id_executor.'">
            <td>Исполнитель</td>';
   
if($_SESSION["rights"]!=1)
{
$content.='<td> <select name="id_executor" size="0">
<option value="" selected>-- --</option>';

 foreach($aa as $id_executor  => $val)
           {
             $id_user=$_SESSION["id_user0"];
             $id_selfexecutor=$executors->getExecutorID($_SESSION["id_user0"]);
             if($id_executor==$id_selfexecutor)
             {    
              $content.='<option value="'.$id_executor.'" selected>'.$aa[$id_executor]['name'].'</option>';
             }
             else
             {
              $content.='<option value="'.$id_executor.'">'.$aa[$id_executor]['name'].'</option>';  
             }    
           }
           
            $content.='</selected></tr>'; 
}
else
{
    $id_selfexecutor=$_SESSION["id_executor"];
    $content.='<input type="hidden" name="id_executor" value="'.$id_selfexecutor.'">';
    $content.='<td>'.$auth->getName().'</td>';
}    

 //Выбор автора   
$content.='<tr>
            <td>Автор</td>';

    $content.='<td> <select name="id_taskauthor" size="0">
           <option value="" selected>-- --</option>';
    
    foreach($aaaa as $id_taskauthor  => $val)
           {
              $content.='<option value="'.$id_taskauthor.'">'.$aaa[$id_taskauthor]['authorname'].'</option>';      
           }
    $content.='</selected></tr>'; 
    

          $content.='<tr>
            <td>Описание задачи</td><td><input name="taskname" type="text" size="80" maxlength="100"></td>
            </tr> 
            
                    
            <tr><td>Срок исполнения</td>
            <td>
            

 <script src="calendar_ru.js" type="text/javascript"></script>
с <input type="text" maxlength="8" size="8" name="begindate" value="'.date('d-m-Y').'" onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">
по <input type="text" maxlength="8" size="8" name="enddate" value="dd-mm-yyyy" onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">

 
          <tr><td>Приоритет</td>
            <td>
            <select name="priority" size="0" style="width: 120;">
                <option value="0" selected>Обычный</option>
                <option value="1">Высокий</option>
            </select>
            </td>
           </tr>    


            </td>
           </tr> 

           <tr>
           <td>Комментарий</td>
            <td>
            <textarea name="comment" rows=10 cols=80>
            </textarea>
            </td>
           </tr>

           <tr>
             <td></td>
             <td>
             <input type="checkbox" name="authornotify" value="1" checked>
             Уведомлять автора о выполнении задачи по e-mail
             </td>
           </tr>

           
           
           <tr>
            <td colspan=2  align="right"><input type="submit" name="addtask" value="Добавить задачу"></td>
           </tr>
           </form>';
           
     
          
          
          
     $content.='</table>';

   
     
     
?>

