<?php
// purpose: create a new user if provided valid inputs

include "../config/dbConfig.php";
global $conn;
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST["nameReg"];
    $password = ($_POST["passwordReg"]);//had md5
    $confirm_password = ($_POST["passwordReg2"]);
    $email = ($_POST["emailReg"]);
    $status = ($_POST["statusReg"]);
    date_default_timezone_set("America/New_York");
    $datetime = date("Y-m-d H:i:sa");

    $answer = strcmp($password,$confirm_password);
    if($answer == 0)
    {
        $msg = "Passwords don't match";
        echo "<script type='text/javascript'>alert('$msg');</script>";
        header("Refresh:0; url=/index.php");
    }
    if ($name == '' || $password == '' || $confirm_password == '' || $email == '' || $status == '')
    {
        $msg = "You must enter all fields";
        echo "<script type='text/javascript'>alert('$msg');</script>";
        header("Refresh:0; url=/index.php");
    }
    else
    {
      $sql = $conn->prepare("SELECT * FROM Users WHERE (Username = ?) OR (Email = ?)");
      $query = $sql->execute(array($name,$email));

      if($sql->rowCount() > 0)
      {
          $msg = "Username or email already exists";
          echo "<script type='text/javascript'>alert('$msg');</script>";
          header("Refresh:0; url=/index.php");
      }

        else
        {
          $options = [
                'cost' => 8,
                    ];

            $password = password_hash($password, PASSWORD_BCRYPT, $options);

            $sql = $conn->prepare("insert into Users (Username,Password,Email,Status,DateJoined)values(?,?,?,?,?)");
            $sql->execute(array($name,$password,$email,$status,$datetime));

            header("Refresh:0; url=/index.php");
        }
    }
}
?>
