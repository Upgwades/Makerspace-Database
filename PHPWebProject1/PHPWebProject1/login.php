<?php
include "dbConfig.php";
global $conn;
session_start();
date_default_timezone_set("America/New_York");
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST["name"];
    $password = ($_POST["password"]);//had md5
	 if ($name == '' || $password == '')
     {
         $msg = "You must enter all fields";
         echo "<script type='text/javascript'>alert('$msg');</script>";
         header("Refresh:0; url=loginpage.html");
    }
     else
     {
        $sql = "SELECT * FROM Users WHERE Username = '$name' AND Password = '$password'";
        $query = $conn->query($sql);

        if ($query === false)
        {
            echo "Could not successfully run query ($sql) from DB: " . mysql_error();
            exit;
        }

        if ($query->fetchColumn() > 0)
        {
            $sql = "SELECT Usertype FROM Users WHERE Username = '$name' AND Password = '$password'";
            $query = $conn->query($sql);
            $usertype = $query->fetch(PDO::FETCH_ASSOC);

            $answer = strcmp((string)$usertype['Usertype'],'Admin');

            //$msg = $answer;
            //echo "<script type='text/javascript'>alert('$msg');</script>";

            $cookie_name = 'username';
            $cookie_value = $name;
            setcookie($cookie_name, $cookie_value);
            if($answer == 0)
            {
                header("Refresh:0; url=adminview.html");
            }
            else
            {
                header("Refresh:0; url=studentview.html");
            }
        }
        else
        {
            $msg = "Username and password do not match";
            echo "<script type='text/javascript'>alert('$msg');</script>";
            header("Refresh:0; url=loginpage.html");
        }


    }
}
?>


