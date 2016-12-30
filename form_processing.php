<?php

 //------------------------Обработка формы в таблицах-----------------------------------------

$executors= new ExecutorClass();
$authors= new AuthorClass();
$reg1= new RegClass();
$task= new TaskClass();
$mailer= new EmailNotifClass();

// Добавление нового исполнителя
 if($_POST['addexecutor'])
  {
      $name=trim($_POST['name1']);    
      $rights=trim($_POST['rights1']);
      $code=trim($_POST['code1']);
      $email=trim($_POST['email1']);
      $extnumber=trim($_POST['extnumber1']);
      
      if($name=='')
      { 
          $err[]='<font size="+1" style="color:red;">ФИО исполнителя не введено</font><br>';
                    
      }
    else 
     {
         if($executors->ExistsExecutor($name))
          {
              $err[]='<font size="+1" style="color:red;">Пользователь с таким ФИО уже существует</font>';
          } 
      }
     
       if(!preg_match('/^[0-9A-Za-zА-Яа-я\s\"\.\(\)\'\,\-]+$/u',$name))
      {
       $err[] = '<font size="+1" style="color:red;">ФИО может состоять только из букв, цифр и символов  ",-\'().</font>';  
      }
      
      
     if($code!='')
      { 
       if(!preg_match('/^[0-9]+$/u',$code))
       {
        $err[] = '<font size="+1" style="color:red;"> Поле код может состоять только из цифр</font>';
       }
      }
      
      if($email!='')
      { 
        if(!preg_match('/^[0-9a-zA-Za\s\"\.\-_@]+$/u',$email))
       {
        $err[] = '<font size="+1" style="color:red;"> E-mail может состоять только из латинских букв, цифр и символов  @.,-_()</font>';  
       }
      }
      
      if($code!='')
      { 
       if(!preg_match('/^[0-9]+$/u',$extnumber))
       {
        $err[] = '<font size="+1" style="color:red;"> Поле Телефонный номер может состоять только из цифр</font>';
       }
      }
      
      if(count($err) == 0)
      {    
       $executors->AddExecutor($id_executor, $rights, $code, $name, $email, $extnumber);
      }
      else
      {
        $content.='<font size="+1" style="color:red;">При вводе произошли следующие ошибки:</b></font></br>';
        foreach($err as $error)
        {
           
            $content.=$error."<br>";
        }  
        $content.="<br>";
      }    
     
  }


  // Изменение значений исполнителя
 if($_POST['updateexecutor'])
  {
      $id_executor=trim($_POST['id_executor2']);
      $name=trim($_POST['name2']);
      $rights=trim($_POST['rights2']);
      $prevrights=trim($_POST['prevrights']);
      $code=trim($_POST['code2']);
      $email=trim($_POST['email2']);
      $extnumber=trim($_POST['extnumber2']);
      
      
      
      
      if($name=='')
      { 
          $err[]='<font size="+1" style="color:red;">ФИО исполнителя не введено</font><br>';
                    
      }
   
     
       if(!preg_match('/^[0-9A-Za-zА-Яа-я\s\"\.\(\)\'\,\-]+$/u',$name))
      {
       $err[] = '<font size="+1" style="color:red;">ФИО может состоять только из букв, цифр и символов  ",-\'().</font>';  
      }
      
      
     if($code!='')
      { 
       if(!preg_match('/^[0-9]+$/u',$code))
       {
        $err[] = '<font size="+1" style="color:red;"> Поле код может состоять только из цифр</font>';
       }
      }
      
      if($email!='')
      { 
        if(!preg_match('/^[0-9a-zA-Za\s\"\.\-_@]+$/u',$email))
       {
        $err[] = '<font size="+1" style="color:red;"> E-mail может состоять только из латинских букв, цифр и символов  @.,-_()</font>';  
       }
      }
      
      if($extnumber!='')
      { 
       if(!preg_match('/^[0-9]+$/u',$extnumber))
       {
        $err[] = '<font size="+1" style="color:red;"> Поле Телефонный номер может состоять только из цифр</font>';
       }
      }
      
      
      $countadmin=$reg1->CountAdminUser();
      
      if($countadmin<2 && $prevrights==0 && $prevrights!=$rights)   
      {
        $err[] = '<font size="+1" style="color:red;"> Полный доступ пользователя '.$name.' не может быть изменён</font>';  
      }    
      if(count($err) == 0)
      {   
       $executors->UpdateExecutor($id_executor, $rights, $code, $name, $email, $extnumber);
      }
      else
      {
        $content.='<font size="+1" style="color:red;">При вводе произошли следующие ошибки:</b></font></br>';
        foreach($err as $error)
        {
           
            $content.=$error."<br>";
        }  
        $content.="<br>";
      }    
     
  }

 // Добавление автора
 if($_POST['addauthor'])
  {
  //    $id_executor=trim($_POST['id_taskauthor']);
      $authorname=trim($_POST['authorname']);
      $email=trim($_POST['email']);
      
      if($email!='')
      { 
        if(!preg_match('/^[0-9a-zA-Za\s\"\.\-_@]+$/u',$email))
       {
        $err[] = '<font size="+1" style="color:red;"> E-mail может состоять только из латинских букв, цифр и символов  @.,-_()</font>';  
       }
      }
      
      if(count($err) == 0)
      {    
        $authors->AddAuthor($authorname,$email);
      }
      else
      {
        $content.='<font size="+1" style="color:red;">При вводе произошли следующие ошибки:</b></font></br>';
        foreach($err as $error)
        {
           
            $content.=$error."<br>";
        }  
        $content.="<br>";
      }    
  
  }    
  
  // Изменение параметров автора
 if($_POST['authorupdate'])
  {
      $id_executor=trim($_POST['id_taskauthor']);
      $authorname=trim($_POST['authorname']);
      $email=trim($_POST['email']);
      
      if($email!='')
      { 
        if(!preg_match('/^[0-9a-zA-Za\s\"\.\-_@]+$/u',$email))
       {
        $err[] = '<font size="+1" style="color:red;"> E-mail может состоять только из латинских букв, цифр и символов  @.,-_()</font>';  
       }
      }
      
      if(count($err) == 0)
      {    
        $authors->UpdateAuthor($id_taskauthor,$authorname,$email);
      }
      else
      {
        $content.='<font size="+1" style="color:red;">При вводе произошли следующие ошибки:</b></font></br>';
        foreach($err as $error)
        {
           
            $content.=$error."<br>";
        }  
        $content.="<br>";
      }    
      
      
  }   
  
   // Добавление задачи
 if($_POST['addtask'])
  {
      $id_executor2=trim($_POST['id_executor']);
      $taskname=trim($_POST['taskname']);
      $begindate=trim($_POST['begindate']);
      $enddate=trim($_POST['enddate']);
      $priority=trim($_POST['priority']);
      $comment=trim($_POST['comment']);
  
      $id_author=$_SESSION["id_user0"];
      $id_taskauthor=trim($_POST['id_taskauthor']);
      
      $authornotify=$_POST['authornotify'];
      
      $status=1;
      
      
      if(isset($_POST['authornotify']))
      {
         $authornotify=1;
      }
      else
      {
         $authornotify=0;
      }    
      
      if($id_executor2=='')
      { 
        $err[] = '<font size="+1" style="color:red;">Не указан исполитель задачи</font>';
      }
      
      if($id_taskauthor=='')
      { 
        $err[] = '<font size="+1" style="color:red;">Не указан автор задачи</font>';
      }
      
      
      if($taskname=='')
      { 
        $err[] = '<font size="+1" style="color:red;">Не указано описание задачи</font>';
      }
      
      if($begindate=='dd-mm-yyyy')
      { 
        $err[] = '<font size="+1" style="color:red;">Не указан срок начала исполнения задачи</font>';
      }     
      
      if($priority=='')
      { 
        $err[] = '<font size="+1" style="color:red;">Не указан приоритет задачи</font>';
      }
      
     if($enddate=='dd-mm-yyyy')
      { 
        $err[] = '<font size="+1" style="color:red;">Не указан срок завершения исполнения задачи</font>';
      }
      else
      {
       list($dd,$mm,$yyyy)=split('-',$begindate);
       $begindate1=$yyyy.'-'.$mm.'-'.$dd;
      
       list($dd,$mm,$yyyy)=split('-',$enddate);
       $enddate1=$yyyy.'-'.$mm.'-'.$dd;
       
       $d=DateTime::createFromFormat('Y-m-d',$begindate1);
       $dbegin=strtotime($d->format('Y-m-d'));
        
       $d=DateTime::createFromFormat('Y-m-d',$enddate1);
       $dend=strtotime($d->format('Y-m-d'));  
   
      
       if($dbegin > $dend)
       { 
        $err[] = '<font size="+1" style="color:red;">Дата завершения задачи раньше даты создания</font>';
       }
      }
      
      if(count($err) == 0)
      {  
          
       
      
//       echo "TTTTTTTTTT $id_author,$id_executor2,$taskname,$begindate1,$enddate1,$status,$priority,$comment<br>";
       
       $task->AddTask($id_taskauthor,$id_author,$id_executor2,$taskname,$begindate1,$enddate1,$status,$priority,$comment,$authornotify);
      }
      else
      {
        $content.='<font size="+1" style="color:red;">При вводе произошли следующие ошибки:</b></font></br>';
        foreach($err as $error)
        {
           
            $content.=$error."<br>";
        }  
        $content.="<br>";
      }    
      
      
      
  }
 
    // Изменение значений задачи
 if($_POST['update_task'])
  {
      $id_task=trim($_POST['id_task']);
      $status=trim($_POST['status']);
      $taskname=trim($_POST['taskname']);
      $statusexec=trim($_POST['statusexec']);
      $editenddate=trim($_POST['editenddate']);
      $priority=trim($_POST['priority']);
      $comment=trim($_POST['comment']);     
      $authornotify=trim($_POST['authornotify']);
      
      
      if($statusexec=='1') // Если статус остаётся активным
      { 
       $task->UpdateTask($id_task,$taskname,$editenddate,$status,$priority,$comment,NULL);
      }
      
      if($statusexec=='2') // Если статус менаятся на выполненный на текущую дату
      {
       $execdate=date('d-m-Y');   
       $task->UpdateTask($id_task,$taskname,$execdate,'2',$priority,$comment,$execdate);   
       
       if($authornotify=='1')
       { 
        
       //Отправить уведомление автору о выполненом задания     
        $id_author=$task->getTaskIDAuthor($id_task);      
        $author_name=$task->getTaskAuthorName($id_author);
        $author_email=$task->getTaskAuthorEmail($id_author);    
        $id_executor=$task->getTaskIDExecutor($id_task);
        $executor_name=$task->getExecutorName($id_executor);
        $executor_email=$task->getExecutorEmail($id_executor);
        $begin_date=$task->getTaskBeginDate($id_task);
        $subject='Уведомление о выполнении задачи 1С';
        $body='';       
        $body_pattern=file('notify_pattern/author_notify');
        foreach ($body_pattern as $pline)
        {
          if(ereg('AUTHOR_NAME',$pline))
          {        
           $body.=preg_replace('/AUTHOR_NAME/',$author_name,$pline);
          }
          elseif (ereg('DATE',$pline)) 
          {
           $body.=preg_replace('/DATE/',$begin_date,$pline);
          }
          elseif (ereg('COMMENT',$pline)) 
          {
           $body.=preg_replace('/COMMENT/',$comment,$pline);
          }
          else
          {
           $body.=$pline; 
          }
          //$body.=$pline;
        }
        //$body=$body_pattern;
        $mailer->EmailSend($executor_name,$executor_email,$author_name,$author_email,$subject,$body);
        $task->SetEndNotify($id_task); 
           
           
       }
       
      }    
      
      if($statusexec=='3') // Если статус менаятся на выполненный с указанным сроком
      {
        list($dd,$mm,$yyyy)=split('-',$editenddate);
        $execdate=$yyyy.'-'.$mm.'-'.$dd;           
       $ddd=DateTime::createFromFormat('Y-m-d',$execdate);
       $dexec=strtotime($ddd->format('Y-m-d'));
       $ddd=DateTime::createFromFormat('Y-m-d',date('Y-m-d'));
       $dcur=strtotime($ddd->format('Y-m-d'));
       
       
       
       
       if($dexec <= $dcur)
       {    
          $task->UpdateTask($id_task,$taskname,$editenddate,'2',$priority,$comment,$editenddate);
           if($authornotify=='1')
            {   
               
               
        //Отправить уведомление автору о выполненом задания     
        $id_author=$task->getTaskIDAuthor($id_task);      
        $author_name=$task->getTaskAuthorName($id_author);
        $author_email=$task->getTaskAuthorEmail($id_author);    
        $id_executor=$task->getTaskIDExecutor($id_task);
        $executor_name=$task->getExecutorName($id_executor);
        $executor_email=$task->getExecutorEmail($id_executor);
        $begin_date=$task->getTaskBeginDate($id_task);
        $subject='Уведомление о выполнении задачи 1С';
        $body='';       
        $body_pattern=file('notify_pattern/author_notify');
        foreach ($body_pattern as $pline)
        {
          if(ereg('AUTHOR_NAME',$pline))
          {        
           $body.=preg_replace('/AUTHOR_NAME/',$author_name,$pline);
          }
          elseif (ereg('DATE',$pline)) 
          {
           $body.=preg_replace('/DATE/',$begin_date,$pline);
          }
          elseif (ereg('COMMENT',$pline)) 
          {
           $body.=preg_replace('/COMMENT/',$comment,$pline);
          }
          else
          {
           $body.=$pline; 
          }
          //$body.=$pline;
        }
        //$body=$body_pattern;
        $mailer->EmailSend($executor_name,$executor_email,$author_name,$author_email,$subject,$body);
        $task->SetEndNotify($id_task); 
                            
               
                
            }          
       }
       else
       {
          $content.='<font size="+1" style="color:red;">Дата окончания задачи еще не наступила</font><br>';
       }    
      }    
      
      if($statusexec=='4') // Если статус менаятся на отменнённый
      {
       $execdate=date('d-m-Y');   
       $task->UpdateTask($id_task,$taskname,$execdate,'3',$priority,$comment,$execdate);   
      }    
      
      
      $task->ConditionTask($id_task);
      
   //   exit;
      
  }   

 ?>