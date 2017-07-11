<?php
// purpose: handles data entry/updates for both new projects as well as edits to existing ones
include "../config/dbConfig.php";
global $conn;
date_default_timezone_set("America/New_York");


require '../vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
$s3config = require('../config/s3Config.php');
   //s3 upload
$s3 = S3Client::factory([
'key' => $s3config['s3']['key'],
'secret' => $s3config['s3']['secret']
]);

    $nameofproject = ($_POST["nameofproject"]);
    $username = $_COOKIE['username'];
    $userID = $_COOKIE['userID'];
    $requestdatetime = date("Y-m-d H:i:sa");
    $completiondatetime = date("Y-m-d h:i:sa",strtotime(($_POST["completiondate"])));
    $stlupload = [];
    $material = [];
    $quantity = [];
    $partnotes = [];
    $partIDs = [];
    $generalnotes = ($_POST["generalnotes"]);
    $type = ($_POST["type"]);
    $units = ($_POST["units"]);
    $error = false;
    $edit = false;
    $nameChange = false;
    $text = "";
    $note = "";

    //echo var_dump($_FILES);

    if(strpos($_SERVER['HTTP_REFERER'], "home")){ // true if request came from the homepage in which case this request is an edit
    $edit = true;

    // get the project ID so we can manhandle it, bc we dont have original info of this entry we must use a html supplied value of the project id
    $projectID = $_POST['projectID'];

    }

    // does this user already have a project with this name
    if($edit){
      $sql = $conn->prepare("SELECT * FROM Projects WHERE (Requestor = ?) AND (ProjectName = ?) AND (ID != ?)");
      $query = $sql->execute(array($username,$nameofproject,$projectID));
    }
    else {
      $sql = $conn->prepare("SELECT * FROM Projects WHERE (Requestor = ?) AND (ProjectName = ?)");
      $query = $sql->execute(array($username,$nameofproject));
    }

    if($sql->rowCount() > 0 )
    {
        $msg = "Please enter a unique project title.";
        echo "<script type='text/javascript'>alert('$msg');</script>";
        $error = true;
    }

    // does project have a name
    if($nameofproject == "")
    {
      $msg = "Please enter a project title.";
      echo "<script type='text/javascript'>alert('$msg');</script>";
      $error = true;
    }

    // check for any .stl
    if(empty($_FILES["stlupload0"]['name']) && !$edit)
    {
      $msg = "At least one 3d file must be submitted with a request.";
      echo "<script type='text/javascript'>alert('$msg');</script>";
      $error = true;
    }

    // check for non .stl input
    $i = 0;
    while (!empty($_FILES["stlupload".$i]['name']))
    {

      $file = $_FILES["stlupload".$i];
      $name = $file['name'];
      $extension = explode('.',$name);
      $extension = strtolower(end($extension));
      if($extension != "stl")
      {
        $msg = "Only .stl's are accepted.";
        echo "<script type='text/javascript'>alert('$msg');</script>";
        $error = true;
      }
      ++$i;

    }

    // check for non .pdf input
    $file = $_FILES["qouteupload"];
    $name = $file['name'];
    $extension = explode('.',$name);
    $extension = strtolower(end($extension));
    if($extension != "pdf" && $name != "")
    {
      $msg = "The print request form must be a .pdf.";
      echo "<script type='text/javascript'>alert('$msg');</script>";
      $error = true;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if($error != false && $edit != true) // this request has failed, dont waste your cycles processing this trash request
    {
      header("Refresh:0; url=/pages/request.php");
    }
    else if($error != false && $edit != false) // same as last if but if its also an edit send it back to the homepage
    {
      header("Refresh:0; url=/pages/home.php");
    }
    else  // no errors run normal
    {
      header("Refresh:0; url=/pages/home.php");

    $i = 0;
    while (!empty($_FILES["stlupload" . $i]['name']))
    {

        $stlupload[$i] = ($_FILES["stlupload" . $i]['name']);
        $material[$i] = ($_POST["material" . $i]);
        $quantity[$i] = ($_POST["quantityupdown" . $i]);
        $partnotes[$i] = ($_POST["notes" . $i]);
        ++$i;


    }

    // get that project info into dat database
    if (!$edit)
    {

      $sql = $conn->prepare("insert into Projects (ProjectName,Requestor,DateTimeRequest,DateTimeDesired,Status,Type,Units)values(?,?,?,?,?,?,?)");
      $sql->execute(array($nameofproject,$username,$requestdatetime,$completiondatetime,"1",$type,$units));

      // +1 tick to num of active requests
      $sql = $conn->prepare("UPDATE `maker`.`Users` SET `NumOfActiveRequests`=`NumOfActiveRequests` + ? WHERE `ID`=?");
      $sql->execute(array("1",$userID));

      // get the project ID so we can manhandle it
      $sql = $conn->prepare("SELECT ID FROM Projects WHERE ProjectName = ? AND Requestor = ? AND DateTimeRequest = ?");
      $sql->execute(array($nameofproject, $username, $requestdatetime));
      $query = $sql->fetch(PDO::FETCH_ASSOC);
      $projectID = $query['ID'];

      // birth certificate for the project to go into the log
      $sql = $conn->prepare("insert into Logs (ProjectID,User,TimeStamp,Text)values(?,?,?,?)");
      $sql->execute(array($projectID,'Makerspace Bot',$requestdatetime,'Requested'));

      // inserts the requestors general notes to the log
      if(!($generalnotes == ""))
      {
        $sql = $conn->prepare("insert into Logs (ProjectID,User,TimeStamp,Text)values(?,?,?,?)");
        $sql->execute(array($projectID,$username,$requestdatetime,$generalnotes));
      }
    }
    else // this is an edit so handle the project info as an update
    {
      // currently it doesnt leaving a note saying the type request date and units were updated but i dont really care

      $sql = $conn->prepare("SELECT * FROM Projects WHERE `ID`=?");
      $sql->execute(array($projectID));
      $query = $sql->fetch(PDO::FETCH_ASSOC);
      $ProjectNameOriginal = $query['ProjectName'];

      if($nameofproject != $ProjectNameOriginal){
        $nameChange = true;
        $note = "Title Edited";
        $sql = $conn->prepare("insert into Logs (ProjectID,User,TimeStamp,Text)values(?,?,?,?)");
        $sql->execute(array($projectID,'Makerspace Bot',$requestdatetime,$note));
      }

      $sql = $conn->prepare("UPDATE Projects SET ProjectName=?,Requestor=?,DateTimeDesired=?,Status=?,Type=?,Units=? WHERE `ID`=?");
      $sql->execute(array($nameofproject,$username,$completiondatetime,"1",$type,$units,$projectID));


      // inserts the requestors general notes to the log
      if(!($generalnotes == ""))
      {
        $note = "General Notes Added: ". $generalnotes;
        $sql = $conn->prepare("insert into Logs (ProjectID,User,TimeStamp,Text)values(?,?,?,?)");
        $sql->execute(array($projectID,'Makerspace Bot',$requestdatetime,$note));
      }

      // handle file edit json array data in mysql
      $partsArray=json_decode($_POST['partsArray']);
      foreach ($partsArray as $value) {
        if($value[0]=="Edit"){
          $partName = $value[1];
          $selection = $value[2];
          $note = "An edit was made to part ".$partName.". Modification: ".$selection;
          $sql = $conn->prepare("insert into Logs (ProjectID,User,TimeStamp,Text)values(?,?,?,?)");
          $sql->execute(array($projectID,'Makerspace Bot',$requestdatetime,$note));
          if($selection == "Material/Color")
          {
            $extra = $value[3];
            $sql = $conn->prepare("UPDATE Parts SET Material=? WHERE ProjectID=? && Partname=?");
            $sql->execute(array($extra,$projectID,$partName));
          }
          elseif ($selection == "Quantity") {
            $extra = $value[3];
            $sql = $conn->prepare("UPDATE Parts SET Quantity=? WHERE ProjectID=? && Partname=?");
            $sql->execute(array($extra,$projectID,$partName));
          }
          elseif ($selection == "Part Notes") {
            $extra = $value[3];
            $sql = $conn->prepare("UPDATE Parts SET PartNotes=? WHERE ProjectID=? && Partname=?");
            $sql->execute(array($extra,$projectID,$partName));
          }
          else {
            $sql = $conn->prepare("DELETE FROM Parts WHERE ProjectID=? && Partname=?");
            $sql->execute(array($projectID,$partName));
          }



        }
      }
      //var_dump($array);
      //echo "<script type='text/javascript'>alert('$test');</script>";

    }

    // upload info for new stl's
		for ($i = 0; $i != count($stlupload);$i++)
				{

					$sql = $conn->prepare("insert into Parts (PartName,Material,PartNotes,Quantity,ProjectID)values(?,?,?,?,?)");
					$sql->execute(array($stlupload[$i],$material[$i],$partnotes[$i],$quantity[$i],$projectID));

				}

      // name has changed so the folder in s3 needs to change too
      if($nameChange){
        // just a general prefix that will help the iterator grab all relevant file keys
        $oldKey = "Requests/$username/$ProjectNameOriginal/";

        $objects = $s3->getIterator('ListObjects', array(
          "Bucket" => $s3config['s3']['bucket'],
          "Prefix" => $oldKey
        ));

        foreach ($objects as $object) {
          // the key specific to the part
          $oldKey = $object['Key'];

          $newKey = explode('/',$object['Key']);
          $newKey[array_search($ProjectNameOriginal, $newKey)] = $nameofproject; // swap the project names
          $newKey = implode('/',$newKey); // reassemble

          $s3->copyObject(array(
            'Bucket' => $s3config['s3']['bucket'],
            'Key' => $newKey,
            'CopySource' => "{$s3config['s3']['bucket']}/{$oldKey}",
            'MetadataDirective' => 'REPLACE',
            'ACL' => 'public-read'
          ));



          $s3->deleteObject(array(
            'Bucket' => $s3config['s3']['bucket'],
            'Key' => $oldKey
          ));

          // update the key in sql bery bery important
          $sql = $conn->prepare("Update Parts Set Address =? Where Address = ? AND ProjectID = ?");
          $sql->execute(array($newKey,$oldKey,$projectID));
        }




      }


    // upload quote deets
    if(!($_FILES["qouteupload"]['name'] == ""))
        {
          $file = $_FILES["qouteupload"];

    			// File details
    			$name = $file['name'];
          $text = $text ." ". $name;
    			$extension = explode('.',$name);
    			$extension = strtolower(end($extension));

    			$key = "Requests/$username/$nameofproject/$name";

    			$s3->putObject(array(
    				'Bucket' => $s3config['s3']['bucket'],
    				'Key' => $key,
    				'Body' => fopen($tmp_name = $file['tmp_name'], 'rb'),
    				'ACL' => 'public-read'
    			));

          $sql = $conn->prepare("insert into Parts (PartName,Material,PartNotes,Quantity,ProjectID,FileExtension,Address)values(?,?,?,?,?,?,?)");
          $sql->execute(array($name,' ',' ','0',$projectID,$extension,$key));


        }

        // upload stls to s3
for ($i = 0; $i != count($stlupload);$i++)
        {
		if(isset($_FILES['stlupload'."$i"]))
		{

			$file = $_FILES["stlupload".$i];

			// File details
			$name = $file['name'];
      $text = $text ." ". $name;
			$tmp_name = $file['tmp_name'];

			$extension = explode('.',$name);
			$extension = strtolower(end($extension));


			// Temp details
			$key = md5(uniqid());
			$tmp_file_name = "{$key}.{$extension}";
			$tmp_file_path = "{$tmp_file_name}";

			// Move the file
			move_uploaded_file($tmp_name, $tmp_file_path);

		try
		{
			$key = "Requests/$username/$nameofproject/$name";

			$s3->putObject(array(
				'Bucket' => $s3config['s3']['bucket'],
				'Key' => $key,
				'Body' => fopen($tmp_file_path, 'rb'),
				'ACL' => 'public-read'
			));

			// Remove the file
			unlink($tmp_file_path);


			$sql = $conn->prepare("Update Parts Set Address = ? ,FileExtension = ? Where PartName = ? AND ProjectID = ?");
      $sql->execute(array($key,$extension,$name,$projectID));
			//$query = $conn->query($sql);

		}
		catch(S3Exception $e)
		{
			// Remove the file
			unlink($tmp_file_path);

			die("There was an error uploading that file.");
		}
		}
		}

    if($edit && $text != ""){
      $note = "The files".$text. " were added by requestor";
      $sql = $conn->prepare("insert into Logs (ProjectID,User,TimeStamp,Text)values(?,?,?,?)");
      $sql->execute(array($projectID,'Makerspace Bot',$requestdatetime,$note));
    }



    // $answer = strcmp((string)$_COOKIE['Usertype'],'Admin');
    // if($answer == 0)
    // {
    //     header("Refresh:0; url=adminview.html");
    // }
    // else
    // {
    //     header("Refresh:0; url=studentview.html");
    // }
}
}
?>
