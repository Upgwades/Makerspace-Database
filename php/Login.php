<?php
// purpose: handles success/failed login attempts, upon success sets up important cookies
include "../config/dbConfig.php";
global $conn;
session_start();
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST["name"];
    $password = ($_POST["password"]);//had md5
	 if ($name == '' || $password == '')
     {
         $msg = "You must enter all fields";
         echo "<script type='text/javascript'>alert('$password');</script>";
         header("Refresh:0; url=/index.php");
    }
     else
     {
        //$sql = "SELECT * FROM Users WHERE (Username = '$name' AND Password = '$password') OR (Email = '$name' AND Password = '$password')";
        $sql = $conn->prepare("SELECT * FROM Users WHERE (Username = ?) OR (Email = ?);");
        $sql->execute(array($name,$name));
        $user = $sql->fetchAll(PDO::FETCH_ASSOC);

        //$options = [
        //      'cost' => 8,
        //          ];

        //echo $p['0']['Password']."\n";
        //echo password_hash($password, PASSWORD_BCRYPT, $options);

        if ($sql === false)
        {
            echo "Could not successfully run query ($sql) from DB: " . mysql_error();
            exit;
        }

        if (password_verify($password, $user['0']['Password']))
        {
            //$sql = "SELECT Usertype, Username FROM Users WHERE (Username = '$name' AND Password = '$password) OR (Email = '$name' AND Password = '$password')";
            //$query = $conn->query($sql);
            //$userInfo = $query->fetch(PDO::FETCH_ASSOC);

            //$answer = strcmp((string)$userInfo['Usertype'],'Admin');
            $cookie_name = 'Usertype';
            $cookie_value = $user['0']['Usertype'];
            setcookie($cookie_name, $cookie_value,0,'/');

            $cookie_name = 'username';
            $cookie_value = $user['0']['Username'];
            setcookie($cookie_name, $cookie_value,0,'/');

            $cookie_name = 'userID';
            $cookie_value = $user['0']['ID'];
            setcookie($cookie_name, $cookie_value,0,'/');

            //$msg = $userInfo['Username'];
            //echo "<script type='text/javascript'>alert('$msg');</script>";

            // if($answer == 0)
            // {
            //     header("Refresh:0; url=adminview.php");
            // }
            // else
            // {
            //     header("Refresh:0; url=studentview.php");
            // }
            header("Refresh:0; url=/pages/home.php");
        }
        else
        {
            $msg = "Username and password do not match";
            echo "<script type='text/javascript'>alert('$msg');</script>";
            header("Refresh:0; url=/index.php");
        }


    }
}
?>
