<?php

require_once 'class.php';

$reg = new RegClass();

$countadmin=$reg->CountAdminUser();

// Страница авторизации

 $content= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
     <HTML>
     <HEAD>
     <META http-equiv="Content-Type" content="text/html; charset=utf-8">
     <TITLE>Страница регистрации</TITLE>
     <LINK rel="stylesheet" type="text/css" href="filecss.css" />
     <BODY link="#000000" vlink="#000000" alink="#000000">

    <P ALIGN="right">
    <A HREF="./index.php">Вход</A>
    </P>

     <CENTER>';


 if(isset($_POST['reg']))
{
    $err = array();
    // проверям логин
    if(!preg_match("/^[a-zA-ZА-яа-я0-9]+$/u",$_POST['login']))
    {
        $err[] = '<h2 style="color:red;">Логин может состоять только из букв и цифр</h2>';
    }
    
    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
        $err[] = '<h2 style="color:red;">Логин должен быть не меньше 3-х символов и не больше 30</h2>';
    }

    // проверяем, не сущестует ли пользователя с таким именем
    
    $reg = new RegClass();
    
    if($reg->ExistsLogin($login))
    {
        $err[] = '<h2 style="color:red;">Пользователь с таким логином уже существует в базе данных</h2>';
    }
 
    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {
        $name = $_POST['name'];
        $login = $_POST['login'];
        #делаем хеш пароля
        //$password = $_POST['password'];
        $passwordhash = md5($_POST['password']);
        
                
        if($countadmin == 0)
        {    
         $reg->AddUser($login,$passwordhash,$name,NULL);
        }
        else
        {
         $reg->AddUser($login,$passwordhash,$name,$code);
        }
        
        header("Location: index.php"); exit();
    }
    else
    {
        $content.='<h2 style="color:red;">При регистрации произошли следующие ошибки:</b><br></h2>';
        foreach($err as $error)
        {
            $content.=$error."<br>";
        }
    }  
}
 
 
 $content.= '
     
       <font size=+1>Регистация нового пользователя:</font> <form method="POST"><br>
        <table class="authtable">
        <tr>
        <td colspan="2" align="center" style="height: 1px;">
        &nbsp
        </td>
        </tr>';

//Если админский пользователь существует
   if($countadmin != 0)
        {    
         $content.= '
        <tr>
        <td align="right">
            Код:
            </td>
            <td>
            <input name="code" type="text">
          </td>
          </tr>';
        }
 
     if($countadmin==0)
     {
            $content.= '
          <tr>
          <td align="right">
            ФИО:
            </td>
            <td>
            <input name="name" type="text" style="width: 320;">
            </td>
            </tr>';
     }
        
 $content.= '
          <tr>
          <td align="right">
            Логин:
            </td>
            <td>
            <input name="login" type="text">
            </td>
            </tr>';
            
 
     
            $content.='<tr>
            <td align="right">
            Пароль: 
            </td>    
            <td>
            <input name="password" type="password">
            </td>
            </tr>
            <tr>
            <td colspan="2" align="right" style="height: 40px;">
            <input name="reg" type="submit" value="Зарегистрировать">
            </td>
            </tr>
        </form>
          ';

 
 $content.='
     </CENTER>
     </BODY>
     </HTML>';
 echo $content;



?>