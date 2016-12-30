<?php

/** 
 * Класс для авторизации
 */ 
class DBClientClass {

 const DB_HOST = 'localhost';
 const DB_NAME = 'calldesk';
 const DB_USER = 'calldeskuser';
 const DB_PASSWORD = 'calldesk123';

//private $_login = "demo"; //Устанавливаем логин
 //private $_password = "www.ox2.ru"; //Устанавливаем пароль
    
    /**
 * Open the database connection
 */
 public function __construct(){
 // open database connection
 $connectionString = sprintf("mysql:host=%s;dbname=%s;charset=utf8",
 DBClientClass::DB_HOST,
 DBClientClass::DB_NAME);

 try {
 $this->conn = new PDO($connectionString,
 DBClientClass::DB_USER,
 DBClientClass::DB_PASSWORD);
 //for prior PHP 5.3.6
 $this->conn->exec("set names utf8");

 } catch (PDOException $pe) {
 die($pe->getMessage());
 }
 }

 /**
 * close the database connection
 */
 public function __destruct() {
 // close the database connection
 $this->conn = null;
 }

 /**
     * Метод возвращает отсортированный массив по ключу из двумерного
  */
    public function SortCol($array,$skey,$sortdir) {
        
        foreach($array as $key  => $arr)
         {
            if($skey=='')
            {
              $ar[$key]=$key;
            }
            else 
            {
              $ar[$key]=$arr[$skey];
            }
         }
         
         if($sortdir=='up' || $sortdir=='')
         {
          asort($ar);
         }
         else
         {
          arsort($ar);
         }
            return $ar;
            
        }
      
        /*
        * Метод возвращает преобразованную дату в российском формате
        */
    public function RusDate($westdate) {
        
            list($yyyy,$mm,$dd)=split('-',$westdate);
            $rusdate=$dd.'-'.$mm.'-'.$yyyy;
            return $rusdate;
            
        }  
        
}


/** 
 * Класс для авторизации
 */ 
class AuthClass extends DBClientClass
{
 
    
    /**
     * Проверяет, авторизован пользователь или нет
     * Возвращает true если авторизован, иначе false
     * @return boolean 
     */
    public function isAuth() {
        if (isset($_SESSION["is_auth"])) { //Если сессия существует
            return $_SESSION["is_auth"]; //Возвращаем значение переменной сессии is_auth (хранит true если авторизован, false если не авторизован)
        }
        else return false; //Пользователь не авторизован, т.к. переменная is_auth не создана
    }
    
    /**
     * Авторизация пользователя
     * @param string $login
     * @param string $passwors 
     */
    public function auth($login, $password) {
        
        $sql = "SELECT id_user,passwordhash FROM users WHERE login=:login LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':login',$login);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_user);
        $stmt->bindColumn(2, $passwordhash);
        
        $stmt->fetch(PDO::FETCH_BOUND);
        
        $hpass=md5($password);
        
        
        if (md5($password)==$passwordhash) { //Если логин и пароль введены правильно
                        
            $sql = "SELECT id_executor,rights,name FROM executors WHERE id_user=:id_user LIMIT 1";
            $stmt2 = $this->conn->prepare($sql);
            $stmt2->bindParam(':id_user',$id_user);
            $stmt2 -> execute();
            $stmt2->bindColumn(1, $id_executor);
            $stmt2->bindColumn(2, $rights);
            $stmt2->bindColumn(3, $name);
            $stmt2->fetch(PDO::FETCH_BOUND);
            
            $_SESSION["is_auth"] = true; //Делаем пользователя авторизованным
            $_SESSION["login0"] = $login; //Записываем в сессию логин пользователя
            $_SESSION["id_user0"] = $id_user; // Записываем в сессию идентификатор пользовалеля
            $_SESSION["id_executor0"] = $id_executor; // Записываем в сессию имя пользователя
            $_SESSION["name0"] = $name; // Записываем в сессию имя пользователя
            $_SESSION["rights0"] = $rights; // Записываем в сессию права пользователя
            
            
            return true;
        }
        else { //Логин и пароль не подошел
            $_SESSION["is_auth"] = false;
            return false; 
        }
    }
    
    /**
     * Метод возвращает логин авторизованного пользователя 
     */
    public function getLogin() {
        if ($this->isAuth()) { //Если пользователь авторизован
            return $_SESSION["login0"]; //Возвращаем логин, который записан в сессию
        }
    }
    
     /**
     * Метод возвращает имя авторизованного пользователя 
     */
    
    public function getName() {
        if ($this->isAuth()) { //Если пользователь авторизован
            return $_SESSION["name0"]; //Возвращаем имя, который записан в сессию
        }    
        
    }
     
    
      /**
     * Метод возвращает идентификатор авторизованного пользователя 
     */
    public function getID() {
        if ($this->isAuth()) { //Если пользователь авторизован
            return $_SESSION["id_user0"]; //Возвращаем имя, который записан в сессию
        }
    }
        
    public function out() {
        $_SESSION = array(); //Очищаем сессию
        session_destroy(); //Уничтожаем
    }
}

/** 
 * Класс для регистрации
 */ 
class RegClass extends DBClientClass
{
 
    /*
     * Метод проверяет зарегистрирован ли уже пользователь с таким же логином
     */
     public function ExistsLogin($login) 
     {
        $sql="SELECT COUNT(id_user) FROM users WHERE login= :login LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':login',$login);
        $stmt -> execute();
        $stmt->bindColumn(1, $count_id);
        $stmt->fetch(PDO::FETCH_BOUND);

        if($count_id>0)
        {
            return true; // логин уже существует
        }
        else
        {
            return false; // такого логина пока нет
        }    
      }
      
     /*
     * Добавление нового ползователя в БД
     */
     public function AddUser($login,$passwordhash,$name,$code) 
     {
         
        $sql="INSERT INTO users(login,passwordhash) VALUES(:login,:passwordhash)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':login',$login);
        $stmt->bindParam(':passwordhash',$passwordhash);
        $stmt->execute();
        
        $sql="SELECT id_user FROM users WHERE login= :login LIMIT 1";
        $stmt2 = $this->conn->prepare($sql);
        $stmt2->bindParam(':login',$login);
        $stmt2->execute();
        $stmt2->bindColumn(1, $id_user);
        $stmt2->fetch(PDO::FETCH_BOUND);
        
           
        $id_user1='';
        $sql="SELECT id_user FROM executors WHERE $id_user= :$id_user LIMIT 1";
        $stmt3 = $this->conn->prepare($sql);
        $stmt3->bindParam(':id_user',$id_user);
        $stmt3->execute();
        $stmt3->bindColumn(1, $id_user1);
        $stmt3->fetch(PDO::FETCH_BOUND);
        
        
        
        if($id_user1=='')
        {
         if($code==NULL)
         {   
          $rights=0;   
          $sql="INSERT INTO executors(id_user,rights,name) VALUES(:id_user,:rights,:name)";
          $stmt4 = $this->conn->prepare($sql);
          $stmt4->bindParam(':id_user',$id_user);
          $stmt4->bindParam(':rights',$rights);
          $stmt4->bindParam(':name',$name);
          $stmt4->execute();
          
          
         }
           else {
                 $sql = 'UPDATE executors SET id_user= :id_user WHERE code = :code';
                 $stmt5 = $this->conn->prepare($sql);
                 $stmt5->bindParam(':code',$code);
                 $stmt5->bindParam(':id_user',$id_user);
                 $stmt5 -> execute();
                }
        }
        
      }
      
     /*
     * Число пользователей с полными правами
     */
     public function CountAdminUser()
     {
         
        $sql="SELECT COUNT(rights) FROM executors WHERE rights=0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':rights',$rights);
        $stmt->execute();
        $stmt->bindColumn(1, $count_user);
        $stmt->fetch(PDO::FETCH_BOUND);
        return $count_user;
        
      }
   
     
        
}
  
/** 
 * Класс для исполнителей
 */ 
class ExecutorClass extends DBClientClass
{
    /*
     * Метод выводит список исполнителей
     */
     public function ListExecutors() 
     {
         
        //$sql="SELECT id_executor,id_user,rights,code,name FROM executors WHERE rights=0 OR rights=1";
         $sql="SELECT id_executor,id_user,rights,code,name,email,extnumber FROM executors WHERE rights <> 2 ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_executor);
        $stmt->bindColumn(2, $id_user);
        $stmt->bindColumn(3, $rights);
        $stmt->bindColumn(4, $code);
        $stmt->bindColumn(5, $name);
        $stmt->bindColumn(6, $email);
        $stmt->bindColumn(7, $extnumber);
        
        
        while($stmt->fetch(PDO::FETCH_BOUND))
        {
         $a[$id_executor]['name']=$name;
         $a[$id_executor]['id_user']=$id_user;
         $a[$id_executor]['rights']=$rights;
         $a[$id_executor]['code']=$code;
         $a[$id_executor]['email']=$email;
         $a[$id_executor]['extnumber']=$extnumber;
        }
        
        return $a;
        
      }
      
      
     /*
     * Метод проверяет зарегистрирован ли уже исполнитель с таким же именем
     */  
    
     public function ExistsExecutor($name) 
     {
        $sql="SELECT COUNT(id_executor) FROM executors WHERE name= :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name',$name);
        $stmt -> execute();
        $stmt->bindColumn(1, $count_sotr);
        $stmt->fetch(PDO::FETCH_BOUND);
        if($count_sotr > 0)
        {
            return true;
        }
        else
        {
            return false;         
        }    
     }
      
     /*
     * Метод для редактирования данных исполнителя
     */   
     public function UpdateExecutor($id_executor,$rights,$code,$name,$email,$extnumber) 
     {
        //$sql = "UPDATE executors SET name= :name, rights= :rights, code= :code, email= \":email\" WHERE id_executor = :id_executor";
        $sql = 'UPDATE executors SET name= :name, rights= :rights, code= :code, email= :email, extnumber= :extnumber WHERE id_executor = :id_executor';
        // $sql = 'UPDATE executors SET name= :name, rights= :rights, code= :code, email= '.'\''.':email'.'\''.' WHERE id_executor = :id_executor';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt->bindParam(':rights',$rights);
        $stmt->bindParam(':code',$code);
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':extnumber',$extnumber);
        $stmt -> execute();
        
        $sql2 = 'UPDATE executors SET email= :email WHERE id_executor = :id_executor';
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bindParam(':id_executor',$id_executor);
        $stmt2->bindParam(':email',$email);
        $stmt2 -> execute();
        
        return $stmt2 -> execute();
     }   
      
     /*
     * Метод для добавления нового исполнителя
     */ 
     public function AddExecutor($name,$rights,$code,$name,$email,$extnumber) 
     {
        $sql="INSERT INTO executors(name,rights,code,email,extnumber) VALUES(:name, :rights, :code, :email, :extnumber)";        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':rights',$rights);
        $stmt->bindParam(':code',$code);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':extnumber',$extnumber);
        return $stmt->execute();
        
      }
     
    /*  
    * Метод возвращает id иполнителя задачи
    */
    public function getExecutorID($id_user) {
        
       $sql="SELECT id_executor FROM executors WHERE id_user=:id_user";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_user',$id_user);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_executor);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $id_executor;
    }
     
    /*  
    * Метод возвращает id_executor из extnumber
    */
    public function getExecutorIdFromExtNum($extnumber) {
               
        
       $sql="SELECT id_executor FROM executors WHERE extnumber=:extnumber";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':extnumber',$extnumber);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_executor);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $id_executor;
    }  
      
   /*  
    * Метод возвращает id_user из extnumber
    */
    public function getUserIdFromExtNum($extnumber) {
               
        
       $sql="SELECT id_user FROM executors WHERE extnumber=:extnumber";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':extnumber',$extnumber);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_user);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $id_user;
    }   
    
}
 
/** 
 * Класс для авторов задач
 */ 
class AuthorClass extends DBClientClass
{
      
     /*
     * Метод для добавления нового автора
     */ 
     public function AddAuthor($authorname,$email) 
     {
        $sql="INSERT INTO authors(authorname,email) VALUES(:authorname, :email)";        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':authorname',$authorname);
        $stmt->bindParam(':email',$email);
        $stmt->execute();
        $lastid = $this->conn->lastInsertId();
        return $lastid;
        
      }
      
      /*
     * Метод выводит список авторов задач
     */
     public function ListАuthors() 
     {
         
         $sql="SELECT id_taskauthor,authorname,email FROM authors";
        
        $stmt = $this->conn->prepare($sql);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_taskauthor);
        $stmt->bindColumn(2, $authorname);
        $stmt->bindColumn(3, $email);
        
        
        while($stmt->fetch(PDO::FETCH_BOUND))
        {  
         $a[$id_taskauthor]['authorname']=$authorname;
         $a[$id_taskauthor]['email']=$email;
        }
        
        return $a;
        
      }
     
    /*
     * Метод для редактирования данных автора
     */   
     public function UpdateAuthor($id_taskauthor,$authorname,$email) 
     {
        $sql = 'UPDATE authors SET authorname = :authorname, email= :email WHERE id_taskauthor = :id_taskauthor';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_taskauthor',$id_taskauthor);
        $stmt->bindParam(':authorname',$authorname);
        $stmt->bindParam(':email',$email);
        $stmt -> execute();             
        return $stmt -> execute();
     }      
      
      
}

/** 
 * Класс для задач
 */ 
class TaskClass extends DBClientClass
{
    /*
     * Метод для добавления новой задачи
     */ 
     public function AddTask($id_taskauthor,$id_user,$id_executor,$taskname,$status,$priority,$comment,$authornotify,$usernotify,$begindatetime)
     {
        
        //$sql="INSERT INTO tasks(id_author,id_executor,taskname,begindate,enddate,status,priority,comment) VALUES(:id_author,:id_executor,:taskname,:begindate,:enddate,:status,:priority,:comment)";
        $sql="INSERT INTO tasks(id_taskauthor,id_user,id_executor,taskname,status,priority,comment,authornotify,usernotify,begindatetime) VALUES(:id_taskauthor,:id_user,:id_executor,:taskname,:status,:priority,:comment,:authornotify,:usernotify,:begindatetime);";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_taskauthor',$id_taskauthor);
        $stmt->bindParam(':id_user',$id_user);
        $stmt->bindParam(':id_executor',$id_executor,PDO::PARAM_INT);
        $stmt->bindParam(':taskname',$taskname,PDO::PARAM_LOB);        
        $stmt->bindParam(':status',$status,PDO::PARAM_INT);
        $stmt->bindParam(':priority',$priority,PDO::PARAM_INT);
        $stmt->bindParam(':comment',$comment,PDO::PARAM_LOB);
        $stmt->bindParam(':authornotify',$authornotify,PDO::PARAM_LOB);
        $stmt->bindParam(':usernotify',$usernotify,PDO::PARAM_LOB);
        $stmt->bindParam(':begindatetime',$begindatetime,PDO::PARAM_LOB);
       
        //$stmt->execute();
        
        
        if(!$stmt->execute()){
          //trigger_error('Error executing MySQL query: ' . $stmt->errorCode());
            echo 'Error executing MySQL query: ' . $stmt->errorCode();
            print_r($stmt->errorInfo());
            
        }
        
         $id_task = $this->conn->lastInsertId();
        
        //$sql2="INSERT INTO setnotify(id_task,authornotify,usernotify) VALUES(LAST_INSERT_ID(),:authornotify,FALSE)";
        //$sql2="INSERT INTO setnotify(id_task,authornotify,usernotify) VALUES(:id_task,:authornotify,FALSE)";
        
        //$stmt2 = $this->conn->prepare($sql2);
        //$stmt2->bindParam(':id_task',$id_task);
        //$stmt2->bindParam(':authornotify',$authornotify,PDO::PARAM_LOB);
        
        
        
        //if(!$stmt2->execute()){
        //    echo 'Error executing MySQL query: ' . $stmt2->errorCode();
        //     print_r($stmt2->errorInfo());            
        //  trigger_error('Error executing MySQL query: ' . $stmt2->error);
        //    echo 'Error executing MySQL query: ' . $stmt2->error;
        //}
             
        
        //return($stmt->execute());
        
        //$arr = $stmt2->errorInfo();
        //print_r($arr);
        
        //echo "\nPDOStatement::errorCode(): ";
        //print $stmt2->errorCode();
                
        //$stmt2->execute();
        
        return $id_task;
      }
      
      public function UpdateTask($id_task,$taskname,$editenddate,$status,$priority,$comment,$executedate)
     {
        
        list($dd,$mm,$yyyy)=split('-',$editenddate);
        $editenddate=$yyyy.'-'.$mm.'-'.$dd;
        
        list($dd,$mm,$yyyy)=split('-',$executedate);
        $executedate=$yyyy.'-'.$mm.'-'.$dd;
         
        $sql='UPDATE tasks SET taskname= :taskname, editenddate= :editenddate, status= :status, priority= :priority, comment= :comment, executedate = :executedate WHERE id_task = :id_task';
          
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_task',$id_task,PDO::PARAM_STR);
        $stmt->bindParam(':taskname',$taskname,PDO::PARAM_LOB);
        $stmt->bindParam(':editenddate',$editenddate,PDO::PARAM_STR);
        $stmt->bindParam(':status',$status,PDO::PARAM_INT);
        $stmt->bindParam(':priority',$priority,PDO::PARAM_INT);
        $stmt->bindParam(':comment',$comment,PDO::PARAM_LOB);
        $stmt->bindParam(':executedate',$executedate,PDO::PARAM_STR);

        
        
        //if(!$stmt->execute()){
         // trigger_error('Error executing MySQL query: ' . $stmt->error);
        //    echo 'Error executing MySQL query: ' . $stmt->error;
        //}
                
        $stmt->execute();
        
        //$arr = $stmt->errorInfo();
        //print_r($arr);
        
        //echo "\nPDOStatement::errorCode(): ";
        //print $stmt->errorCode();
        
        
        //return $stmt->execute();
      }
      
        
// Добавление цепочки событий по задаче
     public function addTaskChain($id_task,$type,$id)
     {            
         //если type=0 заявка создана вручную (id зто id_user кто ввёл заявку)
         //если type=1 событие по телефонному вызову
         //асли type=2 событие по e-mail
        $sql="INSERT INTO taskchain(id_task,type,id) VALUES(:id_task, :type, :id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_task',$id_task);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':id',$id);
        return $stmt->execute();
        
     } 
      
// Установка флага отправки уведомления (чтобы не отправлять уведомление повторно)
     public function SetEndNotify($id_task)
     {        
         
        $sql='UPDATE setnotify SET authornotify=2 WHERE id_task = :id_task';
          
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_task',$id_task,PDO::PARAM_STR);
          
        //if(!$stmt->execute()){
         // trigger_error('Error executing MySQL query: ' . $stmt->error);
        //    echo 'Error executing MySQL query: ' . $stmt->error;
        //}
                
        $stmt->execute();
        
        //$arr = $stmt->errorInfo();
        //print_r($arr);
        
        //echo "\nPDOStatement::errorCode(): ";
        //print $stmt->errorCode();
        
        
        //return $stmt->execute();
      } 
      
     /*
     * Метод выводит список задач
     */
     public function ListTasks($id_executor,$order,$statview,$begindate_b,$begindate_e) 
     {
        
         
         if($order=='DESC')
         {   
          $filter0=' ORDER BY id_task DESC';
         }
         else
         {
          $filter0='';
         }
         
         if($statview=='active')
         {
          if($id_executor==0)
          {    
           $filter1=' WHERE status=1 ';
          }
          else
          {
           $filter1=' AND status=1 ';  
          }    
         }
         elseif($statview=='closed')
         {
           if($id_executor==0)
           {    
            $filter1=' WHERE status=2 ';
           }
           else
           {
             $filter1=' AND status=1 ';   
           }    
         }    
         else
         {
          $filter1='';  
         }    
         
         list($dd,$mm,$yyyy)=split('-',$begindate_b);
        $begindate_b1=$yyyy.'-'.$mm.'-'.$dd;
        
        list($dd,$mm,$yyyy)=split('-',$begindate_e);
        $begindate_e1=$yyyy.'-'.$mm.'-'.$dd;
        
        
        if($begindate_b != '' && $begindate_e !='')
         {
            if($begindate_b != 'dd-mm-yyyy')
            {    
             if($filter1=='' && $id_executor==0)
             { 
                if($statview=='closed')
                { 
                 $filter2='WHERE editenddate >= "'.$begindate_b1.'" AND   editenddate <= "'.$begindate_e1.'"';
                }
                else
                {
                 $filter2='WHERE begindate >= "'.$begindate_b1.'" AND begindate <= "'.$begindate_e1.'"';   
                }    
             } 
             else
             {
                if($statview=='closed')
                {  
                  $filter2='AND  editenddate >= "'.$begindate_b1.'" AND  editenddate <= "'.$begindate_e1.'"';
                }
                else
                {
                  $filter2='AND begindate >= "'.$begindate_b1.'" AND begindate <= "'.$begindate_e1.'"';  
                }    
             }    
            }
            else
            {
               $filter2=''; 
            }   
         }
         else
         {
             $filter2='';
         }    
         
         if($id_executor==0)
         {
           $sql="SELECT id_task,id_executor,id_taskauthor,id_author,taskname,id_editor,editbegindate,editenddate,status,priority,comment,executedate FROM tasks $filter1 $filter2 $filter0";  
         }
         else
         {    
          $sql="SELECT id_task,id_executor,id_taskauthor,id_author,taskname,id_editor,editbegindate,editenddate,status,priority,comment,executedate FROM tasks WHERE id_executor=:id_executor $filter2 $filter1 $filter0";
         }
          
        // echo "FFFFFF $sql<br>";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_task);
        $stmt->bindColumn(2, $id_executor1);
        $stmt->bindColumn(3, $id_taskauthor);
        $stmt->bindColumn(4, $id_author);
        $stmt->bindColumn(5, $taskname);
        $stmt->bindColumn(6, $id_editor);
        $stmt->bindColumn(7, $begindate);
        $stmt->bindColumn(8, $enddate);
        $stmt->bindColumn(9, $status);
        $stmt->bindColumn(10, $priority);
        $stmt->bindColumn(11, $comment);
        $stmt->bindColumn(12, $executedate);
        
        while($stmt->fetch(PDO::FETCH_BOUND))
        {
         
         $a[$id_task]['id_executor1']=$id_executor1;   
         $a[$id_task]['id_taskauthor']=$id_taskauthor;  
         $a[$id_task]['id_author']=$id_author;
         $a[$id_task]['taskname']=$taskname;
         $a[$id_task]['id_editor']=$id_editor;
         $a[$id_task]['editbegindate']=$begindate;
         $a[$id_task]['editenddate']=$enddate;
         $a[$id_task]['status']=$status;
         $a[$id_task]['priority']=$priority;
         $a[$id_task]['comment']=$comment;
         $a[$id_task]['executedate']=$comment;
         
         
        }
        
        
        
        return $a;

      }
     
      public function ListNotify()
      {
        $sql2="SELECT id_task,authornotify,usernotify FROM setnotify";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2 -> execute();
        $stmt2->bindColumn(1, $id_task);
        $stmt2->bindColumn(2, $authornotify);
        $stmt2->bindColumn(3, $usernotify);
        
        while($stmt2->fetch(PDO::FETCH_BOUND))
        {
           $b[$id_task]['authornotify']=$authornotify;
           $b[$id_task]['usernotify']=$usernotify;
        } 
          
        return $b;
          
      }
      
      
    /*  
     * Метод возвращает количество задач с заданным статусом 
     */ 
     public function CountTasks($id_executor,$statusview,$begindate_b,$begindate_e) 
     {
         
        list($dd,$mm,$yyyy)=split('-',$begindate_b);
        $begindate_b1=$yyyy.'-'.$mm.'-'.$dd;
        
        list($dd,$mm,$yyyy)=split('-',$begindate_e);
        $begindate_e1=$yyyy.'-'.$mm.'-'.$dd;
         
         if($statusview!='all')
         {
            if($statusview=='active') 
             { 
              if($id_executor==0)
              {    
               $filter0=' WHERE status=1';
              } 
               else{$filter0=' AND status=1';}
             }
             elseif($statusview=='closed')
             { 
                 
              if($id_executor==0)
              {    
               $filter0=' WHERE status=2';
              }
              else{$filter0=' AND status=2'; }       
             }
         }
        else {
              $filter0='';
             }
            
         
          if($begindate_b != '' && $begindate_e != '')
         {
             if($id_executor==0 && $filter0=='')
             {
                if($begindate_b != 'dd-mm-yyyy')
                { 
                    
                 if($statview=='closed')
                 { 
                  $filter1='WHERE editenddate >= "'.$begindate_b1.'" AND  editenddate <= "'.$begindate_e1.'"';
                 }
                 else
                 {
                  $filter1='WHERE begindate >= "'.$begindate_b1.'" AND begindate <= "'.$begindate_e1.'"';   
                 }    
                    
                } 
                else
                {
                 $filter1='';  
                }    
             }
             else
             {
               if($begindate_b != 'dd-mm-yyyy')
               {   
                   
                 if($statview=='closed')
                 { 
                  $filter1='AND editenddate >= "'.$begindate_b1.'" AND  editenddate <= "'.$begindate_e1.'"';
                 }
                 else
                 {
                   $filter1=' AND begindate >= "'.$begindate_b1.'" AND begindate <= "'.$begindate_e1.'"';  
                 }     
                   
                   
               }
               else {
                     $filter1=''; 
                    }
             }    
         }
         else
         {
           $filter1='';  
         }    
             
         if($id_executor==0)
         {
           $sql='SELECT COUNT(*) FROM tasks'.$filter0.$filter1;  
         }
         else
         {    
           //$sql='SELECT COUNT(*) FROM tasks WHERE id_executor='.$id_executor.' AND status= '.$status;    
            $sql='SELECT COUNT(*) FROM tasks WHERE id_executor='.$id_executor.$filter0.$filter1;  
         }
         
       // echo "ZZZZZZZZZZZZZZ $sql<br>"; 
         
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt -> execute();
        $stmt->bindColumn(1, $ntasks);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $ntasks;
     }
     
    /*  
    * Метод возвращает ФИО кто добавил задачу
     */
    public function getAuthorName($id_author) {
        
       $sql="SELECT name FROM executors WHERE id_executor=:id_author";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_author',$id_author);
        $stmt -> execute();
        $stmt->bindColumn(1, $name);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $name;
    }
     
    
    /*  
    * Метод возвращает дату создания задачи
    */
    public function getTaskBeginDate($id_task) {
        
       $sql="SELECT begindate FROM tasks WHERE id_task=:id_task";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_task',$id_task);
        $stmt -> execute();
        $stmt->bindColumn(1, $begindate);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $begindate;
    }
    
    /*  
    * Метод возвращает идентификатор автора задачи
     */
    public function getTaskIDAuthor($id_task) {
        
       $sql="SELECT id_author FROM tasks WHERE id_task=:id_task";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_task',$id_task);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_author);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $id_author;
    }
    
     /*  
    * Метод возвращает идентификатор исполнителя задачи
     */
    public function getTaskIDExecutor($id_task) {
        
       $sql="SELECT id_executor FROM tasks WHERE id_task=:id_task";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_task',$id_task);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_executor);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $id_executor;
    }
    
    
    
    
    /*  
    * Метод возвращает ФИО кто автор задачи
     */
    public function getTaskAuthorName($id_taskauthor) {
        
       $sql="SELECT authorname FROM authors WHERE id_taskauthor=:id_taskauthor";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_taskauthor',$id_taskauthor);
        $stmt -> execute();
        $stmt->bindColumn(1, $authorname);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $authorname;
    }
    
    /*  
    * Метод возвращает ФИО кто исполнитель задачи
     */
    public function getTaskExecutorName($id_executor) {
        
       $sql="SELECT name FROM executors WHERE id_executor=:id_executor";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt -> execute();
        $stmt->bindColumn(1, $name);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $name;
    }
    
    /*  
    * Метод возвращает добавочный номер телефона пользователя (исполнителя)
     */
    public function getTaskExecutorExtNumber($id_executor) {
        
       $sql="SELECT extnumber FROM executors WHERE id_executor=:id_executor";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt -> execute();
        $stmt->bindColumn(1, $name);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $name;
    }
    
    
    /*  
    * Метод возвращает e-mail автора задачи
    */
    public function getTaskAuthorEmail($id_taskauthor) {
        
       $sql="SELECT email FROM authors WHERE id_taskauthor=:id_taskauthor";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_taskauthor',$id_taskauthor);
        $stmt -> execute();
        $stmt->bindColumn(1, $email);
        $stmt->fetch(PDO::FETCH_BOUND);       
        return $email;
    }
    
    
    
    /*  
    * Метод возвращает ФИО исполнителя задачи
     */
    public function getExecutorName($id_executor) {
        
       $sql="SELECT name FROM executors WHERE id_executor=:id_executor";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt -> execute();
        $stmt->bindColumn(1, $name);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $name;
    }
    
    /*  
    * Метод возвращает e-mail исполнителя задачи
    */
    public function getExecutorEmail($id_executor) {
        
       $sql="SELECT email FROM executors WHERE id_executor=:id_executor";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt -> execute();
        $stmt->bindColumn(1, $email);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        return $email;
    }
    
    
    
    
    
    /*  
    * Метод возвращает состояние задачи
     */
    public function ConditionTask($id_task) {
        
       $sql="SELECT  status,begindate,enddate,editbegindate,editenddate,executedate FROM tasks WHERE id_task=:id_task";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_task',$id_task);
        $stmt -> execute();
        $stmt->bindColumn(1, $status);
        $stmt->bindColumn(2, $begindate);
        $stmt->bindColumn(3, $enddate);
        $stmt->bindColumn(4, $editbegindate);
        $stmt->bindColumn(5, $editenddate);
        $stmt->bindColumn(6, $executedate);
        
        $stmt->fetch(PDO::FETCH_BOUND);
        
        $currentdate=date('Y-m-d');
        //echo "BBBBBBBBBBBB $id_task $status $currentdate $begindate $enddate $editbegindate $editenddate $executedate<br>";
        
        
        $d=DateTime::createFromFormat('Y-m-d',$begindate);
        $dbegin=strtotime($d->format('Y-m-d'));
        
        $d=DateTime::createFromFormat('Y-m-d',$enddate);
        $dend=strtotime($d->format('Y-m-d'));
        
        $d=DateTime::createFromFormat('Y-m-d',$editbegindate);
        $edbegin=strtotime($d->format('Y-m-d'));
        
        $d=DateTime::createFromFormat('Y-m-d',$editenddate);
        $edend=strtotime($d->format('Y-m-d'));
        
        $d=DateTime::createFromFormat('Y-m-d',$currentdate);
        $currentd=strtotime($d->format('Y-m-d'));
        
        if($executedate!=NULL)
        {
         $d=DateTime::createFromFormat('Y-m-d',$executedate);
         $execd=strtotime($d->format('Y-m-d'));
        }
        
 // Активная задача       
        if($status=='1')
        {  
         if(($currentd<=$edend) && ($currentd>=$edbegin) && ($edend>$dend))
         {
          // Задача продлённая
          $condition=6;
         } 
         elseif(($currentd<=$edend) && ($currentd>=$edbegin))
         {
        // Задача выполняется в сроках
          $condition=1;
         }
         elseif(($currentd>=$edend))
         {
        // Задача просрочена
          $condition=5;
         }
         elseif(($currentd<=$edend) && ($currentd<=$edbegin))
         {
           // Задача отложена  
            $condition=7; 
         }
             
        }
  
// Выполнена задача
        if($status=='2')
        { 
         if(($execd > $dend) || ($execd > $edend))
         {
           // Выполнена с отложенным сроком  
           $condition=3;  
         }
         elseif($execd <= $edend && $execd < $dend)
         {
        // Выполнена с опережением
          $condition=4;
         }
         elseif($execd==$edend)
         {
          // Выполнена в срок
          $condition=2;
         } 
         
         
             
        }
        
        if($status=='3')
         { 
          //Задача отменена   
           $condition=8;
         }
        
       // echo "ZZZZZZZZZ $id_task $currentd $dbegin $dend $edbegin $edend $execd<br>";
       // exit;
        return $condition;
        
       
    }
    
    
    
      
}


/** 
 * Класс для диспетчера звонков
 */ 
class CallsClass extends DBClientClass
{
    
     /*
     * Метод для добавления необработанного телефонного вызова (заявки)
     */ 
     public function AddCall($id_executor,$number,$type,$state,$tastauthorname) 
     {
        //$datetime = date_create()->format('Y-m-d H:i:s');
        $datetime = date_create()->format('YmdHis'); 
         
        
        echo "datetime=$datetime\n";
        
        $sql="INSERT INTO callslist(id_executor,datetime,number,type,state,tastauthorname) VALUES(:id_executor, :datetime, :number, :type, :state, :tastauthorname)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt->bindParam(':datetime',$datetime);
        $stmt->bindParam(':number',$number);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':state',$state);
        $stmt->bindParam(':tastauthorname',$tastauthorname);
        return $stmt->execute();
        
      }
      
     /*
     * Get last Call parametrs of executor
     */      
     public function getLastCallData($id_executor)
     {
         
        $sql="SELECT id_call,datetime,number,type,state,tastauthorname FROM callslist WHERE id_executor=:id_executor AND state=0 ORDER BY datetime DESC LIMIT 1";   
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt->execute();
        
        $stmt->bindColumn(1, $id_call);
        $stmt->bindColumn(2, $datetime);
        $stmt->bindColumn(3, $number);
        $stmt->bindColumn(4, $type);
        $stmt->bindColumn(5, $state);
        $stmt->bindColumn(6, $tastauthorname);
        
        $stmt->fetch(PDO::FETCH_BOUND);
        
        $a['id_call']=$id_call;
        $a['datetime']=$datetime;
        $a['number']=$number;
        $a['type']=$type;
        $a['state']=$state;
        $a['tastauthorname']=$tastauthorname;
        return $a;
        
     }
      
     
     
     
      /*
     * Get untreated Calls parametrs of executor
     */      
     public function getUntreatedCallsData($id_executor)
     {
         
        $sql="SELECT id_call,datetime,number,type,state,tastauthorname FROM callslist WHERE id_executor=:id_executor AND state=0 ORDER BY datetime DESC";   
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_executor',$id_executor);
        $stmt->execute();
        
        $stmt->bindColumn(1, $id_call);
        $stmt->bindColumn(2, $datetime);
        $stmt->bindColumn(3, $number);
        $stmt->bindColumn(4, $type);
        $stmt->bindColumn(5, $state);
        $stmt->bindColumn(6, $tastauthorname);
        
        $i=0;
        while($stmt->fetch(PDO::FETCH_BOUND))
        {    
         $i++;
         $a[$i]['id_call']=$id_call;
         $a[$i]['datetime']=$datetime;
         $a[$i]['number']=$number;
         $a[$i]['type']=$type;
         $a[$i]['state']=$state;
         $a[$i]['tastauthorname']=$tastauthorname;
        }
        
        return $a;
        
     }
     
     
      
                          
     
      /*
     * Метод для добавления телефонного номера
     */ 
     public function AddNumber($id_taskauthor,$number) 
     {
                
        $sql="INSERT INTO numbers(id_taskauthor,number) VALUES(:id_taskauthor, :number)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_taskauthor',$id_taskauthor);
        $stmt->bindParam(':number',$number);              
        return $stmt->execute();
        
      }
      
       /*
     * Метод для добавления телефонного номера
     */ 
     public function GetIdNumber($number) 
     {
                
        $sql="SELECT  id_taskauthor FROM numbers WHERE number=:number";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':number',$number);
        $stmt -> execute();
        $stmt->bindColumn(1, $id_taskauthor);       
        $stmt->fetch(PDO::FETCH_BOUND);
        return $id_taskauthor;
        
      }
      
      public function SetState($id_call,$state)
      {  
        $sql = 'UPDATE callslist SET state = :state WHERE id_call = :id_call';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_call',$id_call);
        $stmt->bindParam(':state',$state);      
        $stmt -> execute();
        return $stmt -> execute();
      }
      
      public function SetName($id_call,$tastauthorname)
      {
        $sql = 'UPDATE callslist SET tastauthorname = :tastauthorname WHERE id_call = :id_call';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_call',$id_call);
        $stmt->bindParam(':tastauthorname',$tastauthorname);      
        $stmt -> execute();
        return $stmt -> execute();
      }
      
}


require_once('/usr/share/php/libphp-phpmailer/class.phpmailer.php');

/** 
 * Класс для e-mail уведомления
 */ 
class EmailNotifClass extends PHPMailer
{
    
    var $priority = 3;
    var $to_name;
    var $to_email;
    var $From = null;
    var $FromName = null;
    var $Sender = null;
    
    
     
    /*  
    * Метов для e-mail уведомнения 
    */
    public function EmailSend($from_name,$from_email,$to_name,$to_email,$subject,$body) {
        
        global $site;
        
         $this->Host = $site['smtp_host'];
        $this->Port = $site['smtp_port'];
        if($site['smtp_username'] != '')
        {
         $this->SMTPAuth  = true;
         $this->Username  = $site['smtp_username'];
         $this->Password  =  $site['smtp_password'];
        }
        $this->Mailer = "smtp";
      
      
      $this->Priority = $this->priority;
      
      $this -> Encoding = '8bit';
      $this -> CharSet = 'utf-8';
        
      $this -> addReplyTo($from_email, $from_name);
      $this->setFrom($from_email, $from_name);  
          
               
       // if(!$this->FromName)
        //{
         //   $this-> FromName = $from_name;
        //}
      
        if(!$this->Sender)
        {
            $this->Sender = $from_email;
        }
        
        
        if($subject!='')
        {
            $this->Subject = $subject;
        }
        else
        {
            echo "<font color=\"$warn_color\"> Не указано тело сообщения!!!</font><br>";
            exit;
        }
                        
        
        if($body!='')
        {
            $this->Body = $body;
        }
        else
        {
            echo "<font color=\"$warn_color\"> Пустое письмо!!!</font><br>";
            exit;
        }

        
        
        
        $this->AddAddress($to_email, $to_name);
        if(!$this->Send())
        {
            echo "Не могу отослать письмо для $to_email!<br>";
        }
        else
        {
            echo "Для $to_email уведомление о выполнении отослано!<br>";
        }   

        $this->ClearAddresses();
        $this->ClearAttachments();

        unset($body);

    }
    
}

