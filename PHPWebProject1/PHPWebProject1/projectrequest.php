<?php
include "dbConfig.php";
global $conn;
$name = $_COOKIE['username'];
$msg = "";
$msg = $name;
echo "<script type='text/javascript'>alert('$msg');</script>";
$requestdatetime = date("Y-m-d h:i:sa");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nameofproject             = ($_POST["nameofproject"]);
    $completiondatetime         = ($_POST["completiondate"]); //had md5
    $stlupload = [];
    $material = [];
    $quantity = [];
    $partnotes = [];
    $partIDs = [];
    //$qouteupload           = ($_POST["qouteupload"]);
    $generalnotes = ($_POST["generalnotes"]);

    $i = 0;
    while (!empty($_POST["stlupload" . $i]))
    {

        $stlupload[$i] = ($_POST["stlupload" . $i]);
        ++$i;
    }

    $i = 0;
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

    $completiondate = date("Y-m-d h:i:sa",strtotime($completiondate));
    //"10:30pm April 15 2014"



    //if ($nameofproject == '' || $completiondatetime == '' || $stlupload[0] == '' || $material[0] == '') {
    //    $msg = "You must enter all required fields";
     //   echo "<script type='text/javascript'>alert('$msg');</script>";
    //} else {
        for ($i = 0; $i != count($stlupload);$i++)
        {
            $sql = "insert into Parts (PartName,Material,PartNotes,Quantity)values('$stlupload[$i]','$material[$i]','$partnotes[$i]','$quantity[$i]')";
            $conn->query($sql);
            $sql = "SELECT ID FROM Parts WHERE PartName = '$stlupload[$i]' AND Material = '$material[$i]' AND PartNotes = '$partnotes[$i]' AND Quantity = '$quantity[$i]'";
            $query = $conn->query($sql);
            $currentID = $query->fetch(PDO::FETCH_ASSOC);
            $partIDs[$i] = $currentID['ID'];

            //$msg = $i;
            //echo "<script type='text/javascript'>alert('$msg');</script>";
            //header("Refresh:0; url=projectrequest.html");
        }
        $IDString = implode("|",$partIDs);

        $sql = "insert into Projects (ProjectName,Requestor,DateTimeRequest,DateTimeDesired,Status,GeneralNotes,PartIDs)values('$nameofproject','$name','$requestdatetime','$completiondatetime','1','$generalnotes','$IDString')";
        $conn->query($sql);
        $msg = 'A Request was Successfully submitted';
        echo "<script type='text/javascript'>alert('$msg');</script>";
        header("Refresh:0; url=studentview.html");


 //   }
}
?>