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
// function to only display 25 and sorted by date requested
$(document).ready(function() {
    $('#myTable').DataTable( {
  "iDisplayLength": 25,
  "aaSorting": [6,'desc']
}  );

} );


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
<center><h3>View Requests</h3>
<div class="container">
<?php
include "../config/dbConfig.php";
global $conn;

//admin check
$usertype = require ("../php/AdminCheck.php");
if (strcmp($usertype,'NON-Admin') == 0)
{
header("Refresh:0; url=/pages/home.php");
}

$sql = "SELECT Projects.ID, ProjectName, Requestor, Type, Units, `ProjectStatus's`.Status, DateTimeRequest, DateTimeDesired
From Projects
Inner JOIN `ProjectStatus's` ON Projects.Status = `ProjectStatus's`.ID; ";
$query = $conn->query($sql);
$activeprojects = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<table cellspacing="10" id="myTable" class="table table-bordered table-condensed table-striped table-hover">
<thead>
  <tr >
    <th class='table-header'><?php echo implode('</th><th '.'class="table-header">', array_keys(current($activeprojects))); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php $i=0; ?>
  <?php foreach ($activeprojects as $row): array_map('htmlentities', $row); ?>
    <tr class='table-row' data-href="/php/ProjectSelected.php?id=<?php echo $activeprojects[$i]['ID'];$i++;?>">
      <td><?php echo implode('</td><td>', $row); ?></td>
    </tr>
<?php endforeach; ?>
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
