<?php
// purpose: finds the appropriate part in s3 and mysql and delete all data on it
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

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $partID = $_POST["text"];
	//$msg = $partID;
	$username = $_COOKIE['username'];
	$nameofproject = $_COOKIE['nameofproject'];

	$sql = "select PartName from Parts where ID='$partID'";
	$query = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);
	$name = $query['PartName'];

	try
		{
			$key = "Requests/$username/$nameofproject/$name";

			$s3->deleteObject(array(
				'Bucket' => $s3config['s3']['bucket'],
				'Key' => $key,
			));

			$sql = "delete from Parts where ID='$partID'";
			$query = $conn->query($sql);

		}
		catch(S3Exception $e)
		{
			die("There was an error deleting that file.");
		}

	//echo "<script type='text/javascript'>alert('$msg');</script>";
	header("Refresh:0; url=/pages/manage.php");
}
?>
