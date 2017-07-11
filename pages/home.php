<html>

<head>
    <title>RAD Makerspace - Home</title>
    <link rel="icon" href="/img/Goomerbot Logo3.png">
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/js/jquery.tablesorter.js"></script>
    <script src="/js/functions.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>


</head>

<body>
        <div id="Nav"></div>
        <center>
            <h3>My Projects</h3>
            </br>
            <p>
                <strong>Note</strong> Check your email regularly for questions from us.</br>
            </p>
        <div class="container">
            <div class="panel-group" id="accordion">



                    <?php
include "../config/dbConfig.php";;
global $conn;
$Username = $_COOKIE['username'];
$sql = "SELECT ID
From Projects
WHERE Projects.Requestor = '$Username' && Projects.Deleted != 1
ORDER BY Projects.Status ASC; ";
$query = $conn->query($sql);
$projectIDArray = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($projectIDArray as $ID):

$ProjectID = $ID['ID'];
$sql = "SELECT ProjectName, Type, Units, `ProjectStatus's`.Status, DateTimeRequest, DateTimeDesired
From Projects
Inner JOIN `ProjectStatus's` ON Projects.Status = `ProjectStatus's`.ID
WHERE Projects.ID = '$ProjectID'; ";
$query = $conn->query($sql);
$currentprojects = $query->fetchAll(PDO::FETCH_ASSOC);
$nameofproject = $currentprojects['0']['ProjectName'];
$statusofProject = $currentprojects['0']['Status'];
//$cookie_name = 'nameofproject';
//$cookie_value = $nameofproject;
//setcookie($cookie_name, $cookie_value,0,'/');
?>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title" align="left">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $ProjectID; ?>"><?php echo $nameofproject . " - " . $statusofProject; ?></a>
        </h4>
                            </div>

                            <div id="collapse<?php echo $ProjectID; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table cellspacing="10" id="infoTable" class="tablesorter table table-bordered table-condensed table-striped table-hover">
                                        <caption>
                                            <h3>Details</h3></caption>
                                        <thead>
                                            <tr>
                                                <td><?php echo implode('</td><td>', array_keys(current($currentprojects))); ?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=0; ?>
                                            <?php foreach ($currentprojects as $row): array_map('htmlentities', $row); ?>
                                            <tr>
                                                <td><?php echo implode('</td><td>', $row); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php
$sql = "SELECT Text, User, TimeStamp From Logs WHERE Logs.ProjectID = $ProjectID; ";
$query = $conn->query($sql);
$notes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

                                        <?php
$sql = "SELECT Parts.ID, PartName, Material, `PartNotes`, Quantity
From Parts
WHERE Parts.ProjectID = $ProjectID && (Parts.FileExtension = 'stl' || Parts.FileExtension = 'pdf'); ";
$query = $conn->query($sql);
$parts = $query->fetchAll(PDO::FETCH_ASSOC);
?>
                                            <?php try{ if($parts == FALSE)throw new PartsException("This project has no parts"); ?>
                                            <table cellspacing="10" id="partsTable" class="tablesorter table table-bordered table-condensed table-striped table-hover">
                                                <caption>
                                                    <h3>Parts</h3></caption>
                                                <thead>
                                                    <tr>
                                                        <td><?php echo implode('</td><td>', array_keys(current($parts))); ?></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=0; ?>
                                                    <?php foreach ($parts as $row): array_map('htmlentities', $row); ?>
                                                    <tr>
                                                        <td><?php echo implode('</td><td>', $row); ?></td>
                                                    </tr>
                                                    <?php $i++;?>
                                                  <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php } catch (PartsException $e){echo $e->noParts();}?>
                                          </br>
                                              <button class="btn btn-sample editproject" data-toggle="modal" data-target="#myModal" >Edit Project
                                              <span class="glyphicon  glyphicon-edit"></span>
                                              </button>
                                                <form name="deleteproject" action="/php/DeleteProject.php" style="display:inline;" method="post">
                                                    <button type="submit" class="btn btn-sample" name="deleteproject">Delete Project
</button>
                                                    <textarea name="text" id="text" style="display:none;"><?php echo $ProjectID;?> </textarea>
                                                </form>



                                            </br></br>
                                            If you have any questions contact:
                                            <a href="mailto:sjohnson@flpoly.org?Subject=3D%20Printing%20Question">Scott Johnson</a> - Lab Manager or
                                            <a href="mailto:makerspace@flpoly.org?Subject=3D%20Printing%20Question">Makerspace</a> - The official email account for the RAD makerspace</br>
                                            </br>
                                </div>

                            </div>
                        </div>

                        </br>
                        <?php endforeach; ?>

            </div>

        </div>

        <nav class="navbar navbar-default navbar-fixed-bottom">
          <footer>
            <p>Developer: William J Irwin <img src="/img/logoOriginal.jpg" width="50" height="50"></p>
            <p>Contact information: <a href="mailto:william.j.irwin10@gmail.com">
            william.j.irwin10@gmail.com</a>.</p>
            </footer>
        </nav>

        <!--Modal-->
<form name="editform" id="editform" action="/php/ProcessRequest.php" method="post"  spellcheck="true" enctype="multipart/form-data" >
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Project</h4>
                    </div>
                    <div class="modal-body" id="modal-body-contents" align="center">
                      <div style="container">
                        <textarea id="projectID" name="projectID" style="display:none;"></textarea>
                      <table cellspacing="10" id="currentinfoTable" class="table-condensed table-hover table">
                        <caption><h3>Info</h3></caption>
                        <thead>
                        </thead>
                        <tbody>
                          <tr><th>Project Name</th><td><input class="inp-text" name="nameofproject" id="nameofproject" type="text"/></td></tr>
                          <tr><th>Desired Completion Date</th><td><input class="inp-text" name="completiondate" id="completiondate" type="datetime-local"/></td></tr>
                          <tr><th>Part Units</th><td><label>
                          <input type="radio" name="units" id="units" value="millimeters" checked="checked"/>millimeters</label>
                          </br>
                          <label>
                          <input type="radio" name="units" id="units" value="inches"/>inches</label></td>
                        </tr>
                        <tr>
                          <th>Type of Project</th><td>
                        <label>
                        <input type="radio" name="type" id="type" value="Personal" checked="checked"/>Personal</label>
                        </br>
                        <label>
                        <input type="radio" name="type" id="type" value="Club/RSO"/>Club/RSO</label>
                        </br>
                        <label>
                        <input type="radio" name="type" id="type" value="Class Project"/>Class Project</label>
                        </br>
                        <label>
                        <input type="radio" name="type" id="type" value="Research Project"/>Research Project</label>
                      </td>
                      </tr>
                      <tr><th>Print Request Form</th><td align="center"><input type="file" accept=".pdf" name="qouteupload" id="qouteupload" /></br><td></tr>
                      <tr><th>General Notes</th><td colspan="5" align="center"><textarea class="form-control" rows="3" id="generalnotes" placeholder="Anything you want us to know for your project?"
                        name="generalnotes"></textarea></td></tr>
                        </tbody>
                      </table>
                    </br>
                    <table cellspacing="10" id="currentpartstable"  class="test table table-condensed table-hover">
                        <caption>
                            <h3>Current Parts</h3></caption>
                        <thead>
                            <tr>

                            </tr>
                        </thead>
                        <tbody>
                            <tr id="currentparts0" style="display:block;">
                              <td><select name="operation" id="operation">
                                <option>None</option>
                                <option>Add</option>
                                <option>Edit</option>
                              </td>
                              <td><select name="files" id="files" style="display:none;">
                              </td>
                              <td><select name="edit" id="edit" style="display:none;">
                                <!--<option>Replace</option>-->
                                <option>Material/Color</option>
                                <option>Quantity</option>
                                <option>Part Notes</option>
                                <option>Delete</option>
                              </td>
                              <td><select name="option" id="option" style="display:none;"></td>
                                <td>
                                <!--<input type="file" name="stlupload" id="stlupload" style="width: 105px;display:none;" />-->
                                    <select name="material" id="material" style="display:none;">
                                    <?php	$query = $conn->query('select MaterialType from Materials;');
                                    $materials = $query->fetchAll();
                                    $i = 0;
                                    while($i != $query->rowCount()):?>
                                      <option><?php echo $materials[$i]["MaterialType"]; ?></option>
                                      <?php $i++; endwhile;?>
                                    </select>
                                <input type="number" style="width: 60px;display:none;" name="quantityupdown" id="quantityupdown" value="1" onkeydown="return false" />
                                <textarea name="notes" style="display:none;" display="inline-block" id="notes" rows="1" > </textarea>
                              </td>
                            </tr>
                        </tbody>
                    </table>
                    </br>
                        <table cellspacing="10"  style="display:none;" id="uploadtable" class="test table table-condensed table-hover">
                            <tbody>
                                <tr id="add">
                                    <td><input type="file" name="stlupload" id="stlupload" style="width: 105px" /></td>
                                    <td>
                                        <select name="material" id="material">
        <?php	$query = $conn->query('select MaterialType from Materials;');
        $materials = $query->fetchAll();
        $i = 0;
        while($i != $query->rowCount()):?>
          <option><?php echo $materials[$i]["MaterialType"]; ?></option>
          <?php $i++; endwhile;?>
        </select></td>
                                    <td><input type="number" style="width: 60px" name="quantityupdown" id="quantityupdown" value="1" onkeydown="return false" /></td>
                                    <td><textarea name="notes" id="notes" rows="1"></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </br>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-sample saveproject" value="Save Changes" id="Save Changes"  />
                        <!--<input type="submit" class="btn btn-sample" id="modalSave" data-dismiss="modal">Submit</input>-->
                    </div>
                </div>
            </div>
        </div>
      </form>
</body>
</html>
