<?php
// purpose: allow changes to users basic self entered data: password, username, emailReg
// note: likely obsolete when AD intergration established
include "../config/dbConfig.php";
global $conn;
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $userID = $_COOKIE['userID'];
    $name = $_POST["nameReg"];
    $password = ($_POST["passwordReg"]);//had md5
    $confirm_password = ($_POST["passwordReg2"]);
    $email = ($_POST["emailReg"]);

    $answer = strcmp($password,$confirm_password);
    if($answer == 0)
    {
        $msg = "Passwords don't match";
        echo "<script type='text/javascript'>alert('$msg');</script>";
        header("Refresh:0; url=/pages/accountsettings.php");
    }
    if ($name == '' || $password == '' || $confirm_password == '' || $email == '')
    {
        $msg = "You must enter all fields";
        echo "<script type='text/javascript'>alert('$msg');</script>";
        header("Refresh:0; url=/pages/accountsettings.php");
    }
    else
    {
        $sql = $conn->prepare("SELECT * FROM Users WHERE (Username = ?) OR (Email = ?)");
        $query = $sql->execute(array($name,$email));

        if($sql->rowCount() > 1)
        {
            $msg = "Username or email already exists";
            echo "<script type='text/javascript'>alert('$msg');</script>";
            header("Refresh:0; url=/pages/accountsettings.php");
        }

        else
        {
          $options = [
                'cost' => 8,
                    ];

            $password = password_hash($password, PASSWORD_BCRYPT, $options);

            $sql = $conn->prepare("UPDATE Users SET Username=?, Password=?, Email=? WHERE ID = ?");
            $sql->execute(array($name,$password,$email,$userID));

            header("Refresh:0; url=/php/logout.php");
        }
    }
}
?>
