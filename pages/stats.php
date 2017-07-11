<!DOCTYPE html>
<html>
<head>
<title>RAD Makerspace - View Requests</title>
<link rel="icon" href="/img/Goomerbot Logo3.png">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="/js/jquery.tablesorter.js"></script>
<script src="/js/functions.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function($) {

//
    $(".table-row").click(function() {
        $id = $(this).data("href").split("=")[1];
        //localStorage("ProjectID",$id);
        window.document.location = $(this).data("href");
    });
});
$(document).ready(function()
    {
        $("#UserTable").tablesorter().DataTable();
        $("#StatsTable").tablesorter().DataTable();
        $("#ArchiveTable").tablesorter().DataTable();
    }
);
</script>
<style type="text/css">
.table-row{
cursor:pointer;
}
.table-header:hover{
cursor:pointer;
color:black;
text-decoration:underline;
}
</style>
</head>
<body>
  <div id="Nav"></div>
<center><h3>Stats</h3>
  <div class="container">
</br>
<?php
include "../config/dbConfig.php";
global $conn;

//admin check
$usertype = require ("../php/AdminCheck.php");
if (strcmp($usertype,'NON-Admin') == 0)
{
header("Refresh:0; url=/pages/home.php");
}

$sql = "SELECT Username, Email, Status, Usertype, NumOfPastRequests, NumOfActiveRequests, NumOfItemsPrinted, Paid, Score From Users";
$query = $conn->query($sql);
$users = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT BeginDate, EndDate, TotalProjects, TotalPrints, AverageProjectTime, Income From Stats";
$query = $conn->query($sql);
$stats = $query->fetchAll(PDO::FETCH_ASSOC);

$dir = "..\\archive\\";
$archiveFiles = array_diff(scandir($dir), array('..', '.'));
?>

  <form name="updatedbsettings" action="/php/UpdateDatabaseSettings.php" method="post" enctype="multipart/form-data" >
  </br>
<h3 style="text-align: left;">Users</h3>
<table cellspacing="10" id="UserTable" class="table table-bordered table-condensed table-striped table-hover">
<thead>
  <tr >
    <th class='table-header'><?php echo implode('</th><th '.'class="table-header">', array_keys(current($users))); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php $i=0; ?>
  <?php foreach ($users as $row): array_map('htmlentities', $row); ?>
    <tr><td><?php echo implode('</td><td>', $row); ?></td></tr>
<?php endforeach; ?>
  </tbody>
</table>
</br>
<h3 style="text-align: left;">General</h3>
<table cellspacing="10" id="StatsTable" class="table table-bordered table-condensed table-striped table-hover">
<thead>
  <tr >
    <th class='table-header'><?php echo implode('</th><th '.'class="table-header">', array_keys(current($stats))); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php $i=0; ?>
  <?php foreach ($stats as $row): array_map('htmlentities', $row); ?>
    <tr><td><?php echo implode('</td><td>', $row); ?></td></tr>
<?php endforeach; ?>
  </tbody>
</table>
<input class="btn btn-sample" type="submit" name="Stats" id="Stats" value="Export DB Tables"></input>
<button class="btn btn-sample" data-toggle="modal" type="button" data-target="#ConfirmationModal" >Clear Database
<span class="glyphicon  glyphicon-trash"></span></button>
</br>
</br>
<h3 style="text-align: left;">Archives</h3>
<table cellspacing="10" id="ArchiveTable" class="table table-bordered table-condensed table-striped table-hover">
<thead>
  <tr >
    <th class='table-header'>Filename</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($archiveFiles as $file): ?>
    <tr class='table-row' data-href="../archive/<?php echo $file;?>">
      <td><?php echo $file; ?></td>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>

<div id="ConfirmationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Clear Database</h4>
            </div>
            <div class="modal-body" id="modal-body-contents" align="center">
              <div style="container">
                <p align="left">
                Are you sure you wish to clear the database? Clearing the database will remove all the following info:</br></br>
                All projects info</br>
                All files info</br>
                All log info </br></br>
                Please refresh the page after clicking Confirm.
              </b>
            </div>
            </br>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-sample" value="Confirm" id="Clear" name="Clear" />
            </div>
        </div>
    </div>
</div>
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
