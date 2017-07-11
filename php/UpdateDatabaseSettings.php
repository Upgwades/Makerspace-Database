<?php
// purpose: allows super admin to update settings like material available, admin status, and resources page;
// also exports certain sql tables and optionally clears the database
include "../config/dbConfig.php";
global $conn;
$msg = "";

//Super admin check
$usertype = require ("../php/AdminCheck.php");

if (!(strcmp($usertype,'SUPER-Admin') == 0)){
header("Refresh:0; url=/pages/home.php");
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  date_default_timezone_set("America/New_York");
  $currentdatetime = date("Y-m-d H:i:sa");

    if(isset($_POST["Stats"])){
      downloadTable(array("Projects","Parts","Logs","Stats"),FALSE);
    }

    else if(isset($_POST['Clear'])){
      downloadTable(array("Projects","Parts","Logs","Stats"),TRUE);

      // cutoff current general stat entry and start a new one
      $sql = $conn->prepare("UPDATE Stats SET EndDate=? order by BeginDate desc limit 1"); // puts the null EndDate at top and appends it
      $sql->execute(array($currentdatetime));

      $sql = $conn->prepare("INSERT into Stats (BeginDate) values (?)");
      $sql->execute(array($currentdatetime));

      // clear tables
      $sql = $conn->prepare("Truncate Table Projects");
      $sql->execute();

      $sql = $conn->prepare("Truncate Table Parts");
      $sql->execute();

      $sql = $conn->prepare("Truncate Table Logs");
      $sql->execute();
      //header("Refresh:0; url=stats.php");
  }
  else{
    $AdminRadio = $_POST["AdminRadio"];
    $UsertypeEmail = $_POST["UserTypeInput"];
    $MaterialRadio = $_POST["MaterialRadio"];
    $RemoveMaterial = $_POST["MaterialSelect"];
    $AddMaterial = $_POST["MaterialInput"];
    $Resources = $_FILES["ResourcesInput"];

    // Changing admin status
    if($AdminRadio != "None" && $UsertypeEmail != ""){
      if($AdminRadio == "Admin"){
        $sql = $conn->prepare("UPDATE Users SET Usertype=? WHERE Email = ?");
        $sql->execute(array("Admin",$UsertypeEmail));
      }
      else if($AdminRadio == "NON-Admin"){
        $sql = $conn->prepare("UPDATE Users SET Usertype=? WHERE Email = ?");
        $sql->execute(array("NON-Admin",$UsertypeEmail));
      }
    }

    // Changing Material Available
    if($MaterialRadio != "None"){
      if($MaterialRadio == "Add"){
        $sql = $conn->prepare("insert into Materials (MaterialType)values(?)");
        $sql->execute(array($AddMaterial));
      }
      else if($MaterialRadio == "Delete"){
        $sql = $conn->prepare("DELETE FROM Materials WHERE MaterialType = ? ");
        $sql->execute(array($RemoveMaterial));
      }
    }

    // Changing Resources page
    if($Resources['name'] != ""){

      $tmp_name = $Resources['tmp_name'];
      $body = file_get_contents($_FILES["ResourcesInput"]['tmp_name'], true);
      $fh = fopen('../pages/resources.php', 'w');
      fwrite($fh, $body);
      fclose($fh);
    }
    header("Refresh:0; url=/pages/databasesettings.php");
  }



}

// input array of table names
function downloadTable($tables,$archive){

  date_default_timezone_set("America/New_York");
  $currentdatetime = date("Y-m-d--H-i-s");
  $zipname = 'tables--'.$currentdatetime.'.zip';
  $zip = new ZipArchive;
  $zip->open("..\\archive\\".$zipname, ZipArchive::CREATE);

  foreach($tables as $table){
    $fp = fopen($table.'.csv', 'w');
    $sql = "SELECT * FROM ".$table;
    $query = $GLOBALS['conn']->query($sql);
    $query = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach($query as &$array){
      $array["\"ID\""] = $array["ID"]; // added because excel misidentifies a file if it starts with the following ID
      unset($array["ID"]);
    }

    fputcsv($fp,array_keys($query[0])); // headers

    foreach($query as $array){ // iterate through each row
      fputcsv($fp,$array);
    }
    $zip->addFile($table.'.csv');
    fclose($fp);

  }

  $zip->close();
  ob_clean();
  ob_end_flush();
  header('Content-Type: application/zip');
  header('Content-disposition: attachment; filename='.$zipname);
  header('Content-Length: ' . filesize("..\\archive\\".$zipname));
  readfile("..\\archive\\".$zipname);
  foreach($tables as $table){
    unlink($table.'.csv');
  }
  if(!$archive){
    unlink("..\\archive\\".$zipname);
  }

}
?>
