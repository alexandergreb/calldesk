<?php

$mysqlhost='localhost';
$dbname='calldesk';
$dbuser='calldeskuser';
$dbpass='calldesk123';

// Connect to MySQL by root
$link0 = mysql_connect('localhost', 'root', '');
if (!$link0) {
    die('Could not connect: ' . mysql_error());
}

// Make my_db the current database
$db_selected = mysql_select_db($dbname, $link0);

if (!$db_selected) {
  // If we couldn't, then it either doesn't exist, or we can't see it.
  $sql = 'CREATE DATABASE '.$dbname;

  if (mysql_query($sql, $link0)) {
      echo "Database ".$dbname." created successfully\n";
     if(mysql_query("GRANT ALL ON ".$dbname.".* to  ".$dbuser." identified by '".$dbpass."'",$link0))
      {
       echo "User ".$dbuser." created successfully\n";
      }
      else
      {
       echo 'Error creating user: ' . mysql_error() . "\n";
      }
  } else {
      echo 'Error creating database: ' . mysql_error() . "\n";
  }

}

mysql_close($link0);

/*
 $link = mysqli_connect($mysqlhost, $dbuser, $dbpass);
 if (mysqli_connect_errno($link))
 {
  die('Fail to connect to MySQL: ' . mysqli_connect_error());
 }
*/

 $link = new mysqli($mysqlhost, $dbuser, $dbpass, $dbname);
 if ($link->connect_error)
 {
  die('Connect Error ('.$link->connect_errno.') '.$link->connect_error);
 }


//Creating table `tasks`

$querycheck='SELECT 1 FROM tasks';
//$query_result=$link->query($querycheck);
//$query_result=$link->query('SELECT 1 FROM links');

$link->query('SELECT 1 FROM tasks');

if($link->errno)
{
// die('Select Error ('.$link->errno.')'.$link->error."\n");
// If table 'links' not exists

 $query= "CREATE TABLE `tasks` (
 	  id_task INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 	  taskname BLOB,
 	  id_taskauthor INT(5) NOT NULL,
 	  id_user INT(3) NOT NULL,
 	  id_editor INT(3),
          id_executor INT(3) NOT NULL,          
          executedate DATE,
 	  status TINYINT(1) NOT NULL,
 	  priority TINYINT(1),
 	  comment BLOB,
          authornotify TINYINT(1),
 	  usernotify TINYINT(1),         
          begindatetime DATETIME NOT NULL,
          enddatetime DATETIME
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

if($link->query($query)==TRUE)
 {
  printf("Table TASKS successfully created\n");
 }
 else
 {
  die('Could not create table TASKS: ('.$link->errno.')'.$link->error);
 }

}

//Creating table `users`

$querycheck='SELECT 1 FROM users';

$link->query('SELECT 1 FROM users');

if($link->errno)
{
// If table 'users' not exists

 $query= "CREATE TABLE `users` (
 	  id_user INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 	  login varchar(30),
 	  passwordhash varchar(32)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

if($link->query($query)==TRUE)
 {
  printf("Table USERS successfully created\n");
 }
 else
 {
  die('Could not create table USERS: ('.$link->errno.')'.$link->error);
 }

}


//Creating table `executors`

$querycheck='SELECT 1 FROM executors';

$link->query('SELECT 1 FROM executors');

if($link->errno)
{
// If table 'executors' not exists

 $query= "CREATE TABLE `executors` (
 	  id_executor INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          id_user INT(3),
          extnumber INT(3),
          rights INT(1) NOT NULL,
          code VARCHAR(4),
 	  name VARCHAR(100),
 	  email VARCHAR(30)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

if($link->query($query)==TRUE)
 {
  printf("Table EXECUTORS successfully created\n");
 }
 else
 {
  die('Could not create table EXECUTORS: ('.$link->errno.')'.$link->error);
 }

}

//Creating table `authors`

$querycheck='SELECT 1 FROM authors';

$link->query('SELECT 1 FROM authors');

if($link->errno)
{
// If table 'authors' not exists

 $query= "CREATE TABLE `authors` (
 	  id_taskauthor INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 	  authorname VARCHAR(100),
 	  email VARCHAR(30)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

if($link->query($query)==TRUE)
 {
  printf("Table AUTHORS successfully created\n");
 }
 else
 {
  die('Could not create table AUTHORS: ('.$link->errno.')'.$link->error);
 }

}


/*
//Creating table `setnotify`

$querycheck='SELECT 1 FROM setnotify';

$link->query('SELECT 1 FROM setnotify');

if($link->errno)
{

 $query= "CREATE TABLE `setnotify` (
 	  id_task INT(11) NOT NULL PRIMARY KEY,
 	  authornotify INT(1) NOT NULL,
 	  usernotify INT(1) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

if($link->query($query)==TRUE)
 {
  printf("Table SETNOTIFY successfully created\n");
 }
 else
 {
  die('Could not create table SETNOTIFY: ('.$link->errno.')'.$link->error);
 }

}
*/

//Creating table `numbers`

$querycheck='SELECT 1 FROM numbers';

$link->query('SELECT 1 FROM numbers');

if($link->errno)
{
// If table 'numbers' not exists

 $query= "CREATE TABLE `numbers` (
          id_taskauthor INT(5) NOT NULL,
          number VARCHAR(20) NOT NULL PRIMARY KEY      
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

if($link->query($query)==TRUE)
 {
  printf("Table NUMBERS successfully created\n");
 }
 else
 {
  die('Could not create table NUMBERS: ('.$link->errno.')'.$link->error);
 }

}

//Creating table `callslist`

$querycheck='SELECT 1 FROM callslist';

$link->query('SELECT 1 FROM callslist');

if($link->errno)
{
// If table 'callslist' not exists
 $query= "CREATE TABLE `callslist` (
          id_call INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          id_executor INT(3) NOT NULL,
          datetime DATETIME NOT NULL,
          number VARCHAR(20) NOT NULL,
          tastauthorname VARCHAR(100),
          type VARCHAR(7) NOT NULL,
          state INT(1) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

if($link->query($query)==TRUE)
 {
  printf("Table CALLSLIST successfully created\n");
 }
 else
 {
  die('Could not create table CALLSLIST: ('.$link->errno.')'.$link->error);
 }

}



//Creating table `taskschain`

$querycheck='SELECT 1 FROM taskchain';

$link->query('SELECT 1 FROM taskchain');

if($link->errno)
{
// If table 'taskchain' not exists
 $query= "CREATE TABLE `taskchain` (
          id_task INT(11) NOT NULL,
          type INT(1),
          id INT(11)
//          id_call INT(11),
//          id_email INT(11)          
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

if($link->query($query)==TRUE)
 {
  printf("Table TASKCHAIN successfully created\n");
 }
 else
 {
  die('Could not create table TASKCHAIN: ('.$link->errno.')'.$link->error);
 }

}


?>

