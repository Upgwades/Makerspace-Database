<!DOCTYPE html>
<html>
<head>
<title>RAD Makerspace - Account Settings</title>
<link rel="icon" href="/img/Goomerbot Logo3.png">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="/js/functions.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<style type="text/css">
.table-row{
cursor:pointer;
}
</style>
</head>
<body>
  <div id="Nav"></div>
<center><h3>Account Settings</h3>
</br>
<?php
include "../config/dbConfig.php";
global $conn;
$Username = $_COOKIE['username'];
$sql = $conn->prepare("SELECT Username, Email FROM Users WHERE Username = ?");
$sql->execute(array($Username));
$query = $sql->fetch(PDO::FETCH_ASSOC);


?>
<div class="container">
<table cellspacing="10" id="myTable" class="table table-condensed">
<thead>
  <tr>
    <th>Username</th>
    <th>Email</th>
    <th>New Password</th>
    <th>Confirm Password</th>
  </tr>
  </thead>
  <tbody>
    <tr>
      <form name="UpdateAccountSettings" action="/php/UpdateAccount.php" method="post" >
      <td><input type="text" name="nameReg" id="nameReg" maxlength="45" value="<?php echo $query['Username']; ?>"/></td>
      <td><input type="text" name="emailReg" id="emailReg" maxlength="45" value="<?php echo $query['Email']; ?>"/></td>
      <td><input type="password" name="passwordReg" id="passwordReg" maxlength="45" placeholder="password"/></td>
      <td><input type="password" name="passwordReg2" id="passwordReg2" maxlength="45" placeholder="confirm password"/></td>
    </tr>
    <tr>
      <td width="100%" colspan="4" align="right"><input class="btn btn-sample" type="submit" value="Update"></input></td>
    </form>
    </tr>
  </tbody>
</table>
</div>
<nav class="navbar navbar-default navbar-fixed-bottom">
  <footer>
    <p>Developer: William J Irwin <img src="/img/logoOriginal.jpg" width="50" height="50"></p>
    <p>Contact information: <a href="mailto:william.j.irwin10@gmail.com">
    william.j.irwin10@gmail.com</a>.</p>
    </footer>
</nav>
</body>
</html>
