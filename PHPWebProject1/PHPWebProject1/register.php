<?php include "dbConfig.php";
global $conn;
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST["name"];
    $password = ($_POST["password"]);//had md5
    $confirm_password = ($_POST["confirm_password"]);
    $email = ($_POST["email"]);
    $status = ($_POST["status"]);

    $answer = strcmp($password,$confirm_password);
    if($answer == 0)
    {
        $msg = "Passwords don't match";
        echo "<script type='text/javascript'>alert('$msg');</script>";
        header("Refresh:0; url=registerpage.html");
    }
    if ($name == '' || $password == '' || $confirm_password == '' || $email == '' || $status == '')
    {
        $msg = "You must enter all fields";
        echo "<script type='text/javascript'>alert('$msg');</script>";
        header("Refresh:0; url=registerpage.html");
    }
    else
    {
        $sql = "SELECT * FROM Users WHERE Username = '$name'";
        $query = $conn->query($sql);

        if($query->fetchColumn() > 0)
        {
            $msg = "User already exists";
            echo "<script type='text/javascript'>alert('$msg');</script>";
            header("Refresh:0; url=registerpage.html");
        }

        else
        {
            $sql = "insert into Users (Username,Password,Email,Status)values('$name','$password','$email','$status')";
            $conn->query($sql);
            header("Refresh:0; url=studentview.html");
        }
    }
}
?>