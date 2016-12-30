<?php

/*
 * Форма для аунтефикации
 */

require_once 'class.php';

session_start(); //Запускаем сессии

$auth = new AuthClass();

$reg0 = new RegClass();

// Страница авторизации

 $content= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
     <HTML>
     <HEAD>
     <META http-equiv="Content-Type" content="text/html; charset=utf-8">
     <TITLE>Управление задачами отдела 1с</TITLE>
     <LINK rel="stylesheet" type="text/css" href="filecss.css" />
     </HEAD>
     <BODY link="#000000" vlink="#000000" alink="#000000">

     <CENTER>';

if (isset($_POST["login"]) && isset($_POST["password"])) { //Если логин и пароль были отправлены
    if (!$auth->auth($_POST["login"], $_POST["password"])) { //Если логин и пароль введен не правильно
        $content.='<h2 style="color:red;">Логин и пароль введен не правильно!</h2>';
    }
}

if (isset($_GET["is_exit"])) { //Если нажата кнопка выхода
    if ($_GET["is_exit"] == 1) {
        $auth->out(); //Выходим
        header("Location: ?is_exit=0"); //Редирект после выхода
    }
}

if ($auth->isAuth()) { // Если пользователь авторизован, приветствуем:  
 // $content.="Здравствуйте, " .  $auth->getName()." " .$auth->getLogin() ;
 // $content.="<br/><br/><a href='?is_exit=1'>Выйти</a>"; //Показываем кнопку выхода
    
    header("Location: ./menu_list.php");
 //exit();
} 
else { //Если не авторизован, показываем форму ввода логина и пароля
        
    
     $countadmin=$reg0->CountAdminUser();
    
     if($countadmin==0)
     {
      $content.='          
      <P ALIGN="right">
      <A HREF="./reg_user.php">Первая регистрация</A>
      </P>';
      echo $content;
      exit;
     }
     else
     {
       $content.='          
      <P ALIGN="right">
      <A HREF="./reg_user.php">Регистрация</A>
      </P>'; 
     }    
    
    $content.='<font size=+1> Введите логин и пароль для входа: </font>';
        

$content.='<br><br><form method="post" action="">
    <table class="authtable">
    
   
    
    <tbody>
    
    <tr>
    <td>
    &nbsp
    </td>
    <td>
    &nbsp
    <td>
    </tr>

    <tr>
    <td align="right" >
    Логин:
    </td>
    
    <td align="left"> 
    <input type="text" name="login" size="20" maxlength="30"
    value="';       
   $content.=(isset($_POST["login"])) ? $_POST["login"] : null; // Заполняем поле по умолчанию 
   $content.='"/>
    </td>
    </tr>
    
    <tr>
    <td align="right">
    Пароль: 
    </td>
    <td align="left">
    <input type="password" name="password" value="" size="20" maxlength="30" />
    </td>
    </tr>
    
    <tr>
    <td>
    &nbsp
    </td>
    
    <td align="right" style="height: 40px;">
    <input type="submit" value="Войти" />
    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
    <td>
    
    </tr>
    </tbody>
    </table>
    </form>';
   

   
} 

$content.='
     </CENTER>
     </BODY>
     </HTML>';
 echo $content;


?>

