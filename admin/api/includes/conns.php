<?php
$host = "localhost";
$username = "showbiz_user1";
$password = "123xyz456";
$database = "showbiz_huduma";

//connection to the database
//
$conn = mysql_connect($host, $username, $password)
  or die("Couldn't connect to mysql Server on $host ". mysql_error());

//select a database to work with
$db_select = mysql_select_db($database, $conn)
  or die("Couldn't open database $database");
?>