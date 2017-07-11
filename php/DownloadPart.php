<?php
// purpose: downloads part clicked on from table
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

$partID = $_GET['id'];

$sql = "SELECT PartName, Address
From Parts
WHERE Parts.ID = $partID; ";
$query = $conn->query($sql);
$parts = $query->fetchAll(PDO::FETCH_ASSOC);
$partName = $parts['0']['PartName'];
$partAddress = $parts['0']['Address'];

try
{
$key = $partAddress;

$result = $s3->getObjectUrl($s3config['s3']['bucket'], $key);
echo $result;

}
catch(S3Exception $e)
{

die("There was an error downloading that file.");

}
?>
