<?php
// purpose: uploads part to aws under the relevant project
include "../config/dbConfig.php";
global $conn;
$username = $_COOKIE['username'];
$nameofproject = $_COOKIE['nameofproject'];
$ProjectID = $_COOKIE['ProjectID'];
//$msg = $ProjectID;
//echo "<script type='text/javascript'>alert('$msg');</script>";
require '../vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
$s3config = require('../config/s3Config.php');
   //s3 upload
$s3 = S3Client::factory([
'key' => $s3config['s3']['key'],
'secret' => $s3config['s3']['secret']
]);

date_default_timezone_set("America/New_York");
$requestdatetime = date("Y-m-d H:i:sa");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contentstext = [];
	$stlupload = [];
    $material = [];
    $quantity = [];
    $partnotes = [];
    $partIDs = [];

	if($_FILES != array_unique($_FILES))
	{
		$msg = "Duplicate files are not permitted.";
		echo "<script type='text/javascript'>alert('$msg');</script>";
		header("Refresh:0; url=/pages/manage.php");
	}

	$i = 0;
    while (!empty($_POST["contentstext" . $i]))
    {

        $contentstext[$i] = ($_POST["contentstext" . $i]);
        ++$i;
    }

    $i = 0;
    while (!empty($_FILES["stlupload" . $i]['name']))
    {

        $stlupload[$i] = ($_FILES["stlupload" . $i]['name']);
        ++$i;

    }

    $i = 0;
	//$msg = $contentstext[0];
	//echo "<script type='text/javascript'>alert('$msg');</script>";
    while (!empty($_POST["material" . $i]))
    {

        $material[$i] = ($_POST["material" . $i]);
        ++$i;
    }

    $i = 0;
    while (!empty($_POST["quantityupdown" . $i]))
    {

        $quantity[$i] = ($_POST["quantityupdown" . $i]);
        ++$i;
    }

    $i = 0;
    while (!empty($_POST["notes" . $i]))
    {

        $partnotes[$i] = ($_POST["notes" . $i]);
        ++$i;
    }
	//$msg = count($stlupload). count($quantity). count($partnotes). count($material);
	//echo "<script type='text/javascript'>alert('$msg');</script>";


    //if ($nameofproject == '' || $completiondatetime == '' || $stlupload[0] == '' || $material[0] == '') {
    //    $msg = "You must enter all required fields";
     //   echo "<script type='text/javascript'>alert('$msg');</script>";
    //} else {

		//$msg = $projectID;
		//echo "<script type='text/javascript'>alert('$msg');</script>";
		for ($i = 0; $i != count($stlupload);$i++)
				{

					$sql = "insert into Parts (PartName,Material,PartNotes,Quantity,ProjectID,Contents)values('$stlupload[$i]','$material[$i]','$partnotes[$i]','$quantity[$i]','$ProjectID','$contentstext[$i]')";
					$conn->query($sql);
					//$sql = "SELECT ID FROM Parts WHERE PartName = '$stlupload[$i]' AND Material = '$material[$i]' AND PartNotes = '$partnotes[$i]' AND Quantity = '$quantity[$i]'";
					//$query = $conn->query($sql);
					//$currentID = $query->fetch(PDO::FETCH_ASSOC);
					//$partIDs[$i] = $currentID['ID'];

				}

for ($i = 0; $i != count($stlupload);$i++)
        {
		if(isset($_FILES['stlupload'."$i"]))
		{
			$file = $_FILES["stlupload".$i];

			// File details
			$name = $file['name'];
			//$tmp_name = $file['tmp_name'];

			$extension = explode('.',$name);
			$extension = strtolower(end($extension));


			// Temp details
			//$key = md5(uniqid());
			//$tmp_file_name = "{$key}.{$extension}";
			//$tmp_file_path = "{$tmp_file_name}";
			//$msg = $name;
			//echo "<script type='text/javascript'>alert('$msg');</script>";
			// Move the file
			//move_uploaded_file($tmp_name, $tmp_file_path);

		try
		{
			$key = "Requests/$username/$nameofproject/$name";

			$s3->putObject(array(
				'Bucket' => $s3config['s3']['bucket'],
				'Key' => $key,
				'Body' => $file,
				'ACL' => 'public-read'
			));

			// Remove the file
			//unlink($tmp_file_path);


			$sql = "Update Parts Set Address = '$key' ,FileExtension = '$extension' Where PartName = '$name' AND ProjectID = '$ProjectID'";
			$query = $conn->query($sql);

		}
		catch(S3Exception $e)
		{
			// Remove the file
			//unlink($tmp_file_path);

			die("There was an error uploading that file.");
		}
		}
		}
		header("Refresh:0; url=/pages/manage.php");
}
?>
