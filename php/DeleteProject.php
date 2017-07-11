<?php
// purpose: deletes a project and parts data (from the requestors view) and updates stats to correspond with a finished project
include "../config/dbConfig.php";
global $conn;
require '../vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
$s3config = require('../config/s3Config.php');
   //s3
$s3 = S3Client::factory([
'key' => $s3config['s3']['key'],
'secret' => $s3config['s3']['secret']
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$username = $_COOKIE['username'];
	$userID = $_COOKIE['userID'];
  $projectID = $_POST["text"];

  date_default_timezone_set("America/New_York");
  $datetime = date("Y-m-d H:i:sa");

  $sql = "UPDATE `maker`.`Projects` SET `Deleted`='1', `Status`='7' WHERE `ID`='$projectID'";
  $conn->query($sql);

	$sql = "UPDATE `maker`.`Users` SET `NumOfPastRequests`=`NumOfPastRequests` + 1, `NumOfActiveRequests`=`NumOfActiveRequests` - 1 WHERE `ID`='$userID'";
  $conn->query($sql);

  $sql = "SELECT Projects.ProjectName FROM Projects WHERE `ID`='$projectID'; ";
  $query = $conn->query($sql);
  $nameofproject = $query->fetchAll(PDO::FETCH_ASSOC)['0']['ProjectName'];

  $sql = "SELECT Parts.PartName FROM Parts WHERE Parts.ProjectID = $projectID && Parts.FileExtension != 'pdf'; ";
  $query = $conn->query($sql);
  $parts = $query->fetchAll(PDO::FETCH_ASSOC);

  foreach($parts as $key => $name)
  {
				$name=$name['PartName'];
  			$key = "Requests/$username/$nameofproject/$name";

  			$s3->deleteObject(array(
  				'Bucket' => $s3config['s3']['bucket'],
  				'Key' => $key,
  			));

  }
    $sql = "delete from Parts WHERE Parts.ProjectID = $projectID && Parts.FileExtension != 'pdf'";
    $query = $conn->query($sql);

    $note = "Project was deleted by the requestor";
    $sql = "insert into Logs (ProjectID,User,TimeStamp,Text)values('$projectID','Makerspace Bot','$datetime','$note')";
    $conn->query($sql);


header("Refresh:0; url=/pages/home.php");
}
?>
