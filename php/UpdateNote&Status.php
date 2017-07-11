<?php
// purpose: saves notes entered by admins on a project and updates the status along with any corresponding settings
include "../config/dbConfig.php";
global $conn;
require '../vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
$s3config = require('../config/s3Config.php');
   //s3 upload
$s3 = S3Client::factory([
'key' => $s3config['s3']['key'],
'secret' => $s3config['s3']['secret']
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $projectID = $_COOKIE['ProjectID'];
    $username = $_COOKIE['username'];
    date_default_timezone_set("America/New_York");
    $datetime = date("Y-m-d H:i:sa");
    $note = ($_POST["newNote"]);
    if($note != "")
    {
    $sql = "insert into Logs (ProjectID,User,TimeStamp,Text)values('$projectID','$username','$datetime','$note')";
    $conn->query($sql);
    }

    $newStatus = ($_POST["statusDropdown"]);
    $newStatusIndex = ($_POST["realStatus"]);
    $oldStatus = $_COOKIE['statusofproject'];
    $answer = strcmp($newStatus,$oldStatus);
    if($answer != 0)
    {
    $note = "Status was updated to "."$newStatus";
    $sql = "UPDATE `maker`.`Projects` SET `Status`='$newStatusIndex' WHERE `ID`='$projectID'";
    $conn->query($sql);
    $sql = "insert into Logs (ProjectID,User,TimeStamp,Text)values('$projectID','$username','$datetime','$note')";
    $conn->query($sql);
    }

    if($newStatus == "Paid"){
       preg_match('/(?<=\$)\d+(\.\d+)?\b/', $_POST["newNote"], $money);

       // add income to the gen stat
       $sql = $conn->prepare("UPDATE `maker`.`Users` SET `Paid`=`Paid` + ? WHERE `Username`=?");
       $sql->execute(array($money[0],$_COOKIE['requestor']));

      // add income to the gen stat
      $sql = $conn->prepare("UPDATE Stats SET `Income`=`Income` + '$money[0]' order by BeginDate desc limit 1"); // updates entry with most recent begindate
      $sql->execute();
    }

    // a sudo project delete if status changed to history
    // only deletes files not file info...still up to requestor to do that
    if($newStatus == "History")
    {
      // finished with this project add +1 to stats
      $sql = $conn->prepare("UPDATE Stats SET `TotalProjects`=`TotalProjects` + 1 order by BeginDate desc limit 1"); // updates entry with most recent begindate
      $sql->execute();

      $sql = "SELECT Projects.ProjectName FROM Projects WHERE `ID`='$projectID'; ";
      $query = $conn->query($sql);
      $nameofproject = $query->fetchAll(PDO::FETCH_ASSOC)['0']['ProjectName'];


      $sql = "SELECT Parts.PartName FROM Parts WHERE Parts.ProjectID = $projectID && Parts.FileExtension != 'pdf'; ";
      $query = $conn->query($sql);
      $parts = $query->fetchAll(PDO::FETCH_ASSOC);

      foreach($parts as $key => $name)
      {
        $name=$name['PartName'];
      	try
      		{
      			$key = "Requests/$username/$nameofproject/$name";
            //$msg = "$key";
            //echo "<script type='text/javascript'>alert('$msg');</script>";
      			$s3->deleteObject(array(
      				'Bucket' => $s3config['s3']['bucket'],
      				'Key' => $key,
      			));
      		}
      		catch(S3Exception $e)
      		{
      			die("There was an error killing parts.");
      		}
      }

        //$note = "Project was deleted (moved to history) by the admin, everything is the same but the files have been sent to oblivion";
        //$sql = "insert into Logs (ProjectID,User,TimeStamp,Text)values('$projectID','$username','$datetime','$note')";
        //$conn->query($sql);
    }
		header("Refresh:0; url=/pages/manage.php");
}
?>
