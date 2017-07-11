<?php
// purpose: increment/decrement various stats and notes when a new thing is printed/fails
include "../config/dbConfig.php";
global $conn;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $partID = $_POST["text"];
	$username = $_COOKIE['username'];
  $projectID = $_COOKIE['ProjectID'];
  date_default_timezone_set("America/New_York");
  $datetime = date("Y-m-d H:i:sa");

  $sql = "select PartName from Parts where ID='$partID'";
	$query = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);
	$name = $query['PartName'];

  if(isset($_POST['increment']))
  {

    $sql = "UPDATE `maker`.`Parts` SET `Printed`= `Printed` + '1' WHERE `ID`='$partID'";
    $conn->query($sql);

    $note = "File ". $name ." is printing";
    $sql = "insert into Logs (ProjectID,User,TimeStamp,Text)values('$projectID','$username','$datetime','$note')";
    $conn->query($sql);

    // add parts being printed to general stats
    $sql = "SELECT Contents
    From Parts
    WHERE (ProjectID = '$projectID') && (PartName = '$name') && (FileExtension = 'gcode' || FileExtension = 'makerbot'); ";
    $query = $conn->query($sql);
    $contents = $query->fetchAll(PDO::FETCH_ASSOC);
    $contents = explode(",",$contents[0]["Contents"]);
    $quantity = 0;
    foreach($contents as $part){
      $quantity = $quantity + substr($part,0,1);
    }

    $sql = $conn->prepare("UPDATE Stats SET `TotalPrints`=`TotalPrints` + $quantity order by BeginDate desc limit 1"); // updates entry with most recent begindate
    $sql->execute();

    $sql = $conn->prepare("UPDATE `maker`.`Users` SET `NumOfItemsPrinted`=`NumOfItemsPrinted` + ? WHERE `Username`=?");
    $sql->execute(array($quantity,$_COOKIE['requestor']));
  }
  else
  {

    $sql = "UPDATE `maker`.`Parts` SET `Printed`= `Printed` - '1' WHERE `ID`='$partID'";
    $conn->query($sql);

    $note = "File ". $name ." failed";
    $sql = "insert into Logs (ProjectID,User,TimeStamp,Text)values('$projectID','$username','$datetime','$note')";
    $conn->query($sql);

    // commented out bc parts that fail should still count as prints
    // subtract parts that failed from general stats
    // $sql = "SELECT Contents
    // From Parts
    // WHERE (ProjectID = '$projectID') && (PartName = '$name') && (FileExtension = 'gcode' || FileExtension = 'makerbot'); ";
    // $query = $conn->query($sql);
    // $contents = $query->fetchAll(PDO::FETCH_ASSOC);
    // $contents = explode(",",$contents[0]["Contents"]);
    // $quantity = 0;
    // foreach($contents as $part){
    //   $quantity = $quantity + substr($part,0,1);
    // }

    // $sql = $conn->prepare("UPDATE Stats SET `TotalPrints`=`TotalPrints` - $quantity order by BeginDate desc limit 1"); // updates entry with most recent begindate
    // $sql->execute();
  }

	header("Refresh:0; url=/pages/manage.php");
}
?>
