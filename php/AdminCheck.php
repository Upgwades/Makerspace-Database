<?php
//	purpose: check admin status...return string text of either Admin, Super-Admin, or NON-Admin depending on which the user is

//	grab database connection stuff and the username
//include "../config/dbConfig.php";
//global $conn;
$userID = $_COOKIE['userID'];

//	run a query to find the usertype of that user
$sql = "SELECT Usertype FROM Users WHERE ID = '$userID'";
$query = $conn->query($sql);
$usertype = $query->fetch(PDO::FETCH_ASSOC);

//	compare retrieved info to string version
$answer = strcmp((string)$usertype['Usertype'],'Admin');

// is user an admin
if($answer == 0)
	{
		return ("Admin");
	}

// try again for super admin
$answer = strcmp((string)$usertype['Usertype'],'SUPER-Admin');

if ($answer == 0)
	{
		return ("SUPER-Admin");
	}
// definately not an admin
else
	{
		return ("NON-Admin");
	}

?>
