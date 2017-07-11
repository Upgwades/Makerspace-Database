<?php
// purpose: downloads all .stls in zip format
include "../config/dbConfig.php";
global $conn;
require '../vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
$s3config = require('../config/s3Config.php');

//s3 download
$s3 = S3Client::factory([
'key' => $s3config['s3']['key'],
'secret' => $s3config['s3']['secret']
]);

$ProjectID = $_COOKIE['ProjectID'];
$nameofproject = $_COOKIE['nameofproject'];
$sql = $conn->prepare("SELECT PartName, Address From Parts WHERE ProjectID=? && FileExtension=?");
$sql->execute(array($ProjectID,"stl"));
$partData = $sql->fetchAll(PDO::FETCH_ASSOC);

$zipname = $nameofproject.'.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);

  foreach($partData as $part){


  try
  {
  $key = $part['Address'];
  //echo "<script type='text/javascript'>alert('$key');</script>";
  $result = $s3->getObject(array(
      'Bucket' => $s3config['s3']['bucket'],
      'Key'    => $key
  ));

  }
  catch(S3Exception $e)
  {

  die("There was an error downloading that file.");

  }
  $fp = fopen($part['PartName'], 'w');
  fwrite($fp, $result['Body']);

  $zip->addFile($part['PartName']);
  fclose($fp);
}


$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$zipname);
//header('Content-Length: ' . filesize($zipname));
readfile($zipname);
foreach($partData as $part){
  unlink($part['PartName']);
}
unlink($zipname);
?>
