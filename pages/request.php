<!DOCTYPE html>
<html>
<head>
<title>RAD Makerspace - Make a Request</title>
<link rel="icon" href="/img/Goomerbot Logo3.png">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="/js/jquery.tablesorter.js"></script>
<script src="/js/functions.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>
<body>
  <div id="Nav">
  </div>
    <center><h3>Project Request</h3>
      <div class="container">
    <form name="form" action="/php/ProcessRequest.php" method="post"  spellcheck="true" enctype="multipart/form-data" >
    <table cellspacing="10" class="table-condensed table-hover">
      <caption><h3>Info</h3></caption>
      <thead>
        <tr>
        <th>Project Name</th>
        <th>Desired Completion Date</th>
        <th>Part Units</th>
        <th><strong>Type of Project</strong></th>
        <th><strong>Print Request Form</strong></th>
        <th><strong>General Notes</strong></th>
        </tr>
      </thead>
      <tbody>
        <tr>
        <td><input class="inp-text" name="nameofproject" id="nameofproject" type="text"/></td>
        <td><input class="inp-text" name="completiondate" id="completiondate" type="datetime-local"/></td>
        <td>
        <label>
        <input type="radio" name="units" id="units" value="millimeters" checked="checked"/> millimeters
        </label>
        </br>
        <label>
        <input type="radio" name="units" id="units" value="inches"/> inches
        </label>
      </td>
      <td>
      <label>
      <input type="radio" name="type" id="type" value="Personal" checked="checked"/> Personal
      </label>
      </br>
      <label>
      <input type="radio" name="type" id="type" value="Club/RSO"/> Club/RSO
      </label>
      </br>
      <label>
      <input type="radio" name="type" id="type" value="Class Project"/> Class Project
      </label>
      </br>
      <label>
      <input type="radio" name="type" id="type" value="Research Project"/> Research Project
      </label>
    </td>
    <td align="center"><input type="file" accept=".pdf" name="qouteupload" id="qouteupload" /></br></td>
    <td colspan="5" align="center"><textarea class="form-control" rows="3" id="generalnotes" placeholder="Anything you want us to know for your project?"
      name="generalnotes"></textarea></td>
        </tr>
      </tbody>
    </table>
  </br>
    <table cellspacing="10" style="display:none;" id="uploadtable" class=" test table table-condensed table-hover">
      <caption><h3>Upload Parts</h3></caption>
   		<tbody>
      <thead>
        <tr>
          <th><strong>File</strong></th>
          <th><strong>Material/Color</strong></th>
          <th><strong>Quantity</strong></th>
          <th><strong>Part Notes</strong></th>
          <th><strong>Delete</strong></th>
        </tr>
        </thead>
        <tbody>
        <tr id="row0">
          <td ><input type="file" name="stlupload0" accept=".stl" id="stlupload0" /></td>
          <td >
            <select name="material0" id="material0">
            <?php
            include "../config/dbConfig.php";
            global $conn;
            $query = $conn->query('select MaterialType from Materials;');
            $materials = $query->fetchAll();
            $i = 0;
            while($i != $query->rowCount()):?>
              <option><?php echo $materials[$i]["MaterialType"];?></option>
              <?php $i++;?>
            <?php endwhile;?>
            </select></td>
          <td><input type="number" size='' style='width:100%' name="quantityupdown0" id="quantityupdown0" value="1" onkeydown="return false"/></td>
          <td ><textarea name="notes0" display="inline-block" id="notes0" rows="1" placeholder="Anything about this part?"></textarea></td>
          <td >
            <button type="button" name="delete_part0" id="delete_part0" class="btn btn-sample" onclick="remove(this.id)">
            <span class="glyphicon glyphicon-minus"></span>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  <p align="center">
    <h3 id="tempLabel">Upload Parts</h3>
  <button type="button" id="add_part" class="btn btn-sample" onclick="duplicate()">
    <span class="glyphicon glyphicon-plus"></span>
  </button>
  </p>
</br></br>
  <table cellspacing="10" class="table-condensed table-hover">
      <thead>
      </thead>
    <tbody>
      <tr>
        <td align="center" colspan="5"><input type="submit" class="btn btn-sample" value="Submit" alt="Submit" title="Submit" />
        <input  type="button" class="btn btn-sample" value="Cancel" alt="Cancel" title="Cancel" onclick="location.href='/pages/home.php';"/>
        </td>
      </tr>
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
