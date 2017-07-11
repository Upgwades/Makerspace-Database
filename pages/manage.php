<!DOCTYPE html>
<html>
<head>
    <title>RAD Makerspace - Manage Requests</title>
    <link rel="icon" href="/img/Goomerbot Logo3.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/js/jquery.tablesorter.js"></script>
    <script src="/js/functions.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div id="Nav"></div>
    <center>
        <h3>Manage Requests</h3>

        <?php
// init stuff
include "../config/dbConfig.php";
global $conn;

//admin check
$usertype = require ("../php/AdminCheck.php");
if (strcmp($usertype,'NON-Admin') == 0)
{
header("Refresh:0; url=/pages/home.php");
}

// grab id from cookies and figure out its corresponding project
$ProjectID = $_COOKIE['ProjectID'];
$sql = "SELECT ProjectName, Requestor, Type, Units, `ProjectStatus's`.Status, DateTimeRequest, DateTimeDesired
From Projects
Inner JOIN `ProjectStatus's` ON Projects.Status = `ProjectStatus's`.ID
WHERE Projects.ID = $ProjectID; ";
$query = $conn->query($sql);
$currentprojects = $query->fetchAll(PDO::FETCH_ASSOC);

// need these values in many places including external pages
$nameofproject = $currentprojects['0']['ProjectName'];
$statusofProject = $currentprojects['0']['Status'];
$cookie_name = 'nameofproject';
$cookie_value = $nameofproject;
setcookie($cookie_name, $cookie_value,0,'/');
$cookie_name = 'statusofproject';
$cookie_value = $statusofProject;
setcookie($cookie_name, $cookie_value,0,'/');

// code to implement the email link
$requestor = $currentprojects['0']['Requestor'];
$sql = "SELECT Email From Users WHERE Username = '$requestor'; ";
$email = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC)['0']['Email'];
$currentprojects['0']['Requestor'] = "<a href=mailto:".$email."?Subject=Request-%20".implode('%20',explode(' ',$nameofproject)).">".$requestor."</a>";

// save the name of the requestor
$cookie_name = 'requestor';
$cookie_value = $requestor;
setcookie($cookie_name, $cookie_value,0,'/');
?>
            <div class="container">
                <table cellspacing="10" id="myTable" class="tablesorter table table-bordered table-condensed table-striped table-hover">
                    <caption>
                        <h3>Details</h3></caption>
                    <thead>
                        <tr>
                            <th>
                                <?php echo implode('</th><th>', array_keys(current($currentprojects))); ?></th>
                        </tr>
                    </thead>
                    <tbody >
                        <?php $i=0; ?>
                        <?php foreach ($currentprojects as $row): array_map('htmlentities', $row); ?>
                        <tr>
                            <td>
                                <?php echo implode('</td><td>', $row); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php
$sql = "SELECT Text, User, TimeStamp From Logs WHERE Logs.ProjectID = $ProjectID; ";
$query = $conn->query($sql);
$notes = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
                    <table cellspacing="10" class=" table-condensed table-hover">
                        <caption>
                            <h3>Log</h3></caption>
                        <tbody>
                            <tr>
                                <td colspan="3" height="100%"><textarea readonly="true" name="ProjectNotes" id="ProjectNotes" rows="5"><?php $i=0;
        foreach ($notes as $row): echo implode('  -  ', $row)."\n\n";endforeach; ?>
      </textarea></td>
                            </tr>
                            <tr>
                                <form name="UploadNoteForm" action="/php/UpdateNote&Status.php" method="post">
                                    <td align="center" valign="middle"><select name="statusDropdown" id="statusDropdown" onclick="update()">
        <?php
        $sql = "SELECT Status FROM maker.`ProjectStatus's` WHERE ID != 9 ORDER BY ID;";
        $query = $conn->query($sql);
        $StatusArray = $query->fetchAll();
        $i = 0;
        while($i != $query->rowCount()):?>
        	<option <?php if($StatusArray[$i]["Status"] == $statusofProject){echo "selected";}?>><?php echo $StatusArray[$i]["Status"];?></option>
        	<?php $i++; endwhile;?>
        </select><textarea name="realStatus" id="realStatus" style="display:none;"></textarea></td>
                                    <td width="100%"><textarea name="newNote" id="newNote" rows="2" placeholder="Type your note in here"></textarea></td>
                                    <td width="100%" valign="middle"><input class="btn btn-sample" type="submit" value="Submit" id="submitNote"></input>
                                    </td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
</div>

<?php
$sql = "SELECT Parts.ID, PartName, Material, `PartNotes`, Quantity
From Parts
WHERE Parts.ProjectID = $ProjectID && (Parts.FileExtension = 'stl' || Parts.FileExtension = 'pdf'); ";
$query = $conn->query($sql);
$parts = $query->fetchAll(PDO::FETCH_ASSOC);
try{ if($parts == FALSE)throw new PartsException("This project has no parts"); ?>
<div class="container">
                        <table cellspacing="10" id="partsTable" class="tablesorter table table-bordered table-condensed table-striped table-hover">
                            <caption>
                                <h3>Parts</h3></caption>
                            <thead>
                                <tr>
                                    <th>
                                        <?php echo implode('</th><th>', array_keys(current($parts))); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; ?>
                                <?php foreach ($parts as $row): array_map('htmlentities', $row); $downloadString='class="table-row-download" data-href="'. $parts[$i]['ID'];?>
                                <tr>
                                    <td <?php ?>>
                                        <?php echo implode('</td><td '. $downloadString .'">', $row); ?></td>
                                </tr>
                                <?php $i++;?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
</div>
                    <p align="center">
                      <form name="downloadAll" action="/php/DownloadAll.php" method="post" enctype="multipart/form-data" >
                        <input type="submit" value="Download All" class="btn btn-sample"  name="Download All" value="Download All" id="Download All" />
                          </form>
                    </p><?php } catch (PartsException $e){echo $e->noParts();}?>
                            <?php
  $sql = "SELECT Parts.ID, PartName, Material, `PartNotes`, Quantity, Printed
  From Parts
  WHERE Parts.ProjectID = $ProjectID && (Parts.FileExtension = 'gcode' || Parts.FileExtension = 'makerbot'); ";
  $query = $conn->query($sql);
  $parts = $query->fetchAll(PDO::FETCH_ASSOC);
  try{ if($parts == FALSE)throw new PartsException("This project has no sliced parts");
  $sql = "SELECT Parts.Contents
  From Parts
  WHERE Parts.ProjectID = $ProjectID && (Parts.FileExtension = 'gcode' || Parts.FileExtension = 'makerbot'); ";
  $query = $conn->query($sql);
  $contents = $query->fetchAll(PDO::FETCH_ASSOC);?>
  <div class="container">
      <table id="myTable" class="  table table-bordered table-condensed table-striped table-hover ">
          <caption><h3>Sliced Files</h3></caption>
                                <thead>
                                    <tr>
                                        <th>
                                            <?php echo implode('</th><th>', array_keys(current($parts))); ?></th>
                                        <th>File Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=0; $html = '</br>';?>
                                    <?php foreach ($parts as $row): array_map('htmlentities', $row); $downloadString='class="table-row-download" data-href="'. $parts[$i]['ID'];?>
                                    <tr>
                                        <td>
                                            <?php echo implode('</td><td '. $downloadString .'">', $row);?> </td>
                                        <td>
                                            <button class="btn btn-sample" data-toggle="popover" data-trigger="hover" title="This file contains:" data-html="true" data-content="<?php echo str_replace(" , ","<br />",$contents[$i]['Contents']);?>">
                                            <span class="glyphicon  glyphicon-th-list"></span>
                                            </button>

                                            <form name="PrintCount" action="/php/UpdatePrintCount.php" style="display:inline;" method="post">
                                                <button type="submit" class="btn btn-sample" name="increment">
      <span class="glyphicon glyphicon-plus"></span>
      </button>
                                                <button type="submit" class="btn btn-sample" name="decrement">
      <span class="glyphicon glyphicon-minus"></span>
      </button>
                                                <textarea name="text" id="text" style="display:none;"><?php echo $parts[$i]['ID'];?> </textarea>
                                            </form>

                                            <form name="frmdelete" action="/php/DeletePart.php" style="display:inline;" method="post">
                                                <button type="submit" class="btn btn-sample" name="delete_PreparedPart" id="delete">
      <span class="glyphicon glyphicon-remove"></span>
      </button>
                                                <textarea name="text" id="text" style="display:none;"><?php echo $parts[$i]['ID'];?> </textarea>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php $i++;?>
                                    <?php endforeach;?>
                                </tbody>
                        </table>
</div>
                        <?php } catch (PartsException $e){echo $e->noParts();}?>
<div class="container">
                        <iframe id="my_iframe" style="display:none;"></iframe>
                        </br>
                        <form name="frmupload" action="/php/UploadPart.php" method="post" enctype="multipart/form-data">
                            <table cellspacing="10" style="display:none;" id="uploadtable" class="test table table-condensed table-hover">
                                <caption>
                                    <h3>Upload</h3></caption>
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
                                        <td style="display:none;"><textarea name="contentstext0" style="display:none;" id="contentstext0" rows="1"></textarea></td>
                                        <td><input type="file" name="stlupload0" id="stlupload0" /></td>
                                        <td>
                                            <select name="material0" id="material0">
						<?php	$query = $conn->query('select MaterialType from Materials;');
						$materials = $query->fetchAll();
						$i = 0;
						while($i != $query->rowCount()):?>
							<option><?php echo $materials[$i]["MaterialType"]; ?></option>
							<?php $i++; endwhile;?>
						</select></td>
                                        <td><input type="number" size='' style='width:100%' name="quantityupdown0" id="quantityupdown0" value="1" onkeydown="return false" /></td>
                                        <td><textarea name="notes0" display="inline-block" id="notes0" rows="1"> </textarea></td>
                                        <td>
                                            <button type="button" name="delete_part0" id="delete_part0" class="btn btn-sample" onclick="remove(this.id)">
            <span class="glyphicon glyphicon-minus"></span>
            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p align="center">
                                <h3 id="tempLabel">Upload Sliced Parts</h3>
                                <button type="button" id="add_part" class="btn btn-sample" onclick="duplicate()">
    <span class="glyphicon glyphicon-plus"></span>
  </button>
                                <input type="button" value="Upload All" class="btn btn-sample" data-toggle="modal" data-target="#myModal" name="contents" id="contents" />
                            </p>
                            <!-- Modal -->
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Contents of Parts</h4>
                                        </div>
                                        <div class="modal-body" id="modal-body-contents">
                                        </div>
                                        <div class="modal-footer">
                                            <input type="submit" class="btn btn-sample" value="Submit" alt="Submit" title="Submit" id="modalSave" />
                                            <!--<input type="submit" class="btn btn-sample" id="modalSave" data-dismiss="modal">Submit</input>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
</div>
                    </br>
                    </br>
                    </br>
                    <nav class="navbar navbar-default navbar-fixed-bottom">
                      <footer>
                        <p>Developer: William J Irwin <img src="/img/logoOriginal.jpg" width="50" height="50"></p>
                        <p>Contact information: <a href="mailto:william.j.irwin10@gmail.com">
                        william.j.irwin10@gmail.com</a>.</p>
                        </footer>
                    </nav>
</body>
</html>
