<?php

$aa=$authors->ListАuthors();

/*
 * Направление сортировки по столбикам
 */
 $sortdir=$_GET['sortdir'];

$sortkey=$_GET['sort'];
$a = $task->SortCol($aa,$sortkey,$sortdir);

$content.='<script src="calendar_ru.js" type="text/javascript"></script>';
     $content.='<table class="formtable">
     <tr>
     <th colspan=1>
     <a href="'.$phpself.'?authors=1&sort=taskauthor&sortdir='.$sortdir.'">ФИО</a>
     </th>
     <th colspan=1>
     <a href="'.$phpself.'?authors=1&sort=email&sortdir='.$sortdir.'">e-mail</a>
     </th>
     <th colspan=2>
     Действие
     </th>
     </tr>';
     $content.='<form method="POST">';
     $content.='<tr><td><input name="authorname" type="text" size="50" maxlength="100"></td>
      <td><input name="email" type="text" size="30" maxlength="30"></td>';
     $content.='<td colspan=2 align="center"><button class="formbutton" name="addauthor" alt="Добавить" title="Добавить" value="Добавить"><img src="pics/plus_20.png"></button></td></tr>';
     $content.='</form>';

 foreach($a as $id_taskauthor  => $val)
           {
             $content.='<form method="POST">';
             $content.='<tr>';
             $content.='<input type="hidden" name="id_taskauthor" value="'.$id_taskauthor.'">';
             $content.='<td><input name="authorname" value=\''.$aa[$id_taskauthor]['authorname'].'\' type="text" size="50" maxlength="100"></td>';
             $content.='<td><input name="email" value=\''.$aa[$id_taskauthor]['email'].'\' type="text" size="30" maxlength="30"></td>';
             $content.='<td align="center"><button class="formbutton" name="authorupdate" alt="Редактировать" title="Редактировать" value="Редактировать"><img src="pics/pencil_20.png"></button></td>';
             $content.='</tr>';
             $content.='</form>';
           }
     $content.='</table>';
     
?>
             

