<?php

/* 
 * Таблица списка исполниченей заданий
 */


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

     $content.='<table class="formtable">
     <tr>
     
     <th>
     <a href="'.$phpself.'?executors=1&sort=name&sortdir='.$sortdir.'">ФИО</a>
     </th>
     
     <th>
     <a href="'.$phpself.'?executors=1&sort=rights&sortdir='.$sortdir.'">Доступ</a>
     </th>
     
     <th>
      <a href="'.$phpself.'?executors=1&sort=code&sortdir='.$sortdir.'">Код</a>
     </th>

     <th>
     <a href="'.$phpself.'?executors=1&sort=email&sortdir='.$sortdir.'">Е-mail</a>
     </th>
      
     <th>
     <a href="'.$phpself.'?executors=1&sort=extnumber&sortdir='.$sortdir.'">Телефонный <br> Номер</a>
     </th>
     
     <th colspan=2>
     Действие
     </th>

     <tr>';
             
     
      
     $content.='
          <form method="POST">
          <tr>
            <td><input name="name1" type="text" size="50" maxlength="100"></td>
                        
            <td>
            <select name="rights" size="0" style="width: 120;">
            <option disabled selected>-- --</option>
            <option value="0">Полный</option>
            <option value="1">Ограниченный</option>
            </select>
            </td>

            <td><input name="code1" type="text" size="4" maxlength="4"></td>
            <td><input name="email1" type="text" size="30" maxlength="30"></td>
            <td align="center"><input name="extnumber1" type="text" size="3" maxlength="3"></td>
            <input type="hidden" name="sortdir" value="'.$sortdir.'">
            <td colspan="1" align="center"><button class="formbutton" name="addexecutor" alt="Добавить" title="Добавить" value="Добавить"><img src="pics/plus_20.png"></button></td>
           <tr>
           </form>';
           
  
    $aa = $executors->ListExecutors();
    
    $sortkey=$_GET['sort'];
    $a = $executors->SortCol($aa,$sortkey,$sortdir);
    
    
           foreach($a as $id_executor  => $val)
           {
                  $content.='<form method="POST">
                    <tr>
                    <input type="hidden" name="id_executor2" value="'.$id_executor.'">
                    <input type="hidden" name="prevrights" value="'.$aa[$id_executor]['rights'].'">    
                    <td><input name="name2" value=\''.$aa[$id_executor]['name'].'\' type="text" size="50" maxlength="100"></td>
                     <td>
                    <select name="rights2" size="0" style="width: 120;"> ';
                            
                     
                        if($aa[$id_executor]['rights']==0)
                        {   
                         $content.='<option selected value="0">Полный</option>';
                         $content.='<option value="1">Ограниченный</option>';
                        }
                        elseif($aa[$id_executor]['rights']==1)
                        {
                          $content.='<option value="0">Полный</option>'; 
                          $content.='<option selected value="1">Ограниченный</option>';
                        }   
                        elseif($aa[$id_executor]['rights']==2)
                        {
                          $content.='<option value="0">Полный</option>'; 
                          $content.='<option value="1">Ограниченный</option>';
                        }        
                    
                    
                    $content.='
                    </select>  
                    </td>
                    <td><input name="code2"  value=\''.$aa[$id_executor]['code'].'\' type="text" size="4" maxlength="4"></td>
                    <td><input name="email2" value=\''.$aa[$id_executor]['email'].'\' type="text" size="30" maxlength="30"></td>
                    <td align="center"><input name="extnumber2" value=\''.$aa[$id_executor]['extnumber'].'\' type="text" size="3" maxlength="3"></td>';

                    if($_SESSION["rights"]!=1 && $_SESSION["rights"]!=2)
                    {   
                     $content.='<td align="center"><button class="formbutton" name="updateexecutor" alt="Изменить" title="Изменить" value="Изменить"><img src="pics/save_20.png"></button></td>';
                    }
                    else
                    {
                      $content.='<td align="center"><button class="formbutton" name="updateexecutor" alt="Изменить" title="Изменить" value="Изменить" disabled><img src="pics/save_20.png"></button></td>';  
                    }    
                    
                    //<td align="center"><button class="formbutton" name="delexecutor" alt="Удалить" title="Удалить" value="Удалить"><img src="pics/delete_20.png"></button></td>

                    $content.='
                    <tr>
                    </form>'; 
                                                                          
           }


          
     
     $content.='</table>';

?>
