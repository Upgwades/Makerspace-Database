<?php

define ("DB_HOST", "maker.chodijpbfvkc.us-east-1.rds.amazonaws.com:3306"); // set database host

define ("DB_USER", "maker"); // set database user

define ("DB_PASS","willi000"); // set database password

define ("DB_NAME","maker"); // set database name

try
{
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".maker, DB_USER, DB_PASS);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}
//$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");

//$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");

?>