<!DOCTYPE html>
<html>
<head>
<title>RAD Makerspace - Database Settings</title>
<link rel="icon" href="/img/Goomerbot Logo3.png">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="/js/functions.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>
<body>
  <div id="Nav"></div>
<center><h3>Database Settings</h3>
</br>
<?php
include "../config/dbConfig.php";
global $conn;

//Super admin check
$usertype = require ("../php/AdminCheck.php");
if (!(strcmp($usertype,'SUPER-Admin') == 0))
{
header("Refresh:0; url=/pages/home.php");
}

?>
<div class="container">
  <form name="updatedbsettings" action="/php/UpdateDatabaseSettings.php" method="post" enctype="multipart/form-data" >
<table cellspacing="10" id="dbsettings" class="table-condensed table-hover table">
  <thead>
  </thead>
  <tbody>
    <tr><th>Change Admin Status</th><td><label><input type="radio" name="AdminRadio" id="AdminRadio" value="None" checked="checked"/>None</label>
    <label><input type="radio" name="AdminRadio" id="AdminRadio" value="Admin"  />Admin</label></br>
    <label><input type="radio" name="AdminRadio" id="AdminRadio" value="NON-Admin"/>NON-Admin</label></br></td>
    <td><input class="inp-text" name="UserTypeInput" id="UserTypeInput" type="text" placeholder="Enter an email." style="display:none;"/></td></tr>
    <tr><th>Edit Available Materials</th>
    <td ><label><input type="radio" name="MaterialRadio" id="MaterialRadio" value="None" checked="checked" />None</label>
    <label><input type="radio" name="MaterialRadio" id="MaterialRadio" value="Add" />Add</label></br>
    <label><input type="radio" name="MaterialRadio" id="MaterialRadio" value="Delete" />Delete</label></br></td><td>
    <input class="inp-text" name="MaterialInput" id="MaterialInput"  type="text" style="display:none;" placeholder="Enter a material."/>
    <select name="MaterialSelect" id="MaterialSelect" style="display:none;">
    <?php $query = $conn->query('select MaterialType from Materials;');
    $materials = $query->fetchAll();
    $i = 0;
    while($i != $query->rowCount()):?>
    <option><?php echo $materials[$i]["MaterialType"]; ?></option>
    <?php $i++; endwhile;?>
    </select>
      </td></tr>
    <tr><th>Upload New Resources Page</th><td align="center"><input type="file" accept=".php" name="ResourcesInput" id="ResourcesInput" /></br></td>
    <td><strong>Note</strong> due to the php nature of this site,</br> change the ".html" of the new resources page to ".php".</td></tr>
      <tr><td width="100%" colspan="4" align="right">
      <input class="btn btn-sample" type="submit" name="Update" value="Update"></input></td></tr>
  </tbody>
</table>

      </form>
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
