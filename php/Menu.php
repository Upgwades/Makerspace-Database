<?php
// purpose: provides a unified script for presenting the appropriate menu at the top of a page

include "../config/dbConfig.php";
global $conn;
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

  $superAdminNav = '<nav class=" navbar navbar-inverse" style="background-color: #492f92;">
  <div class="container-fluid ">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a style="color: #ffffff;" class="navbar-brand" href="#">RAD Makerspace</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav links"  >
          <li><a style="color: #ffffff;" href="/pages/home.php">Home</a></li>
          <li><a style="color: #ffffff;" href="/pages/projects.php">Projects</a></li>
          <li><a style="color: #ffffff;" href="/pages/request.php">Request</a></li>
          <li><a style="color: #ffffff;" href="/pages/stats.php">Stats</a></li>
          <li><a style="color: #ffffff;" href="/pages/resources.php">Resources</a></li>
          <li><a style="color: #ffffff;" href="/pages/feedback.php">Feedback</a></li>
          <li><a style="color: #ffffff;" href="/pages/about.php">About</a></li>
          <li><a style="color: #ffffff;" href="/pages/accountsettings.php">Account Settings</a></li>
          <li><a style="color: #ffffff;" href="/pages/databasesettings.php">Database Settings</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a style="color: #ffffff;" href="/php/Logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>';


  // '<ul class="topnav navbar navbar-expand-lg navbar-dark bg-dark fixed-top navbar-nav ml-auto" id="adminNav">
  // <div style="float:left"><li><a class="navbar-brand" href="#">RAD Makerspace</a></li></div>
  // <div style="float:right">
  //   <li class="nav-item active"><a class="nav-link" href="/pages/home.php">Home</a></li>
  //   <li class="nav-item"><a class="nav-link" href="/pages/projects.php">Projects</a></li>
  //   <!--<li><a class="nav-link" href="#printers">Printers</a></li>-->
  //   <li class="nav-item"><a class="nav-link" href="/pages/request.php">Request</a></li>
  //   <li class="nav-item"><a class="nav-link" href="/pages/resources.php">Resources</a></li>
  //   <li class="nav-item"><a class="nav-link" href="/pages/feedback.php">Feedback</a></li>
  //   <li class="nav-item"><a class="nav-link" href="/pages/about.php">About</a></li>
  //   <li class="nav-item"><a class="nav-link" href="/pages/stats.php">Stats</a></li>
  //   <li class="nav-item"><a class="nav-link" href="/pages/accountsettings.php">Account Settings</a></li>
  //   <li class="nav-item"><a class="nav-link" href="/pages/databasesettings.php">Database Settings</a></li>
  //   <li class="nav-item"><a class="nav-link" href="/php/Logout.php">Logout</a></li></div>
  // </ul>';

  $adminNav = '<nav class=" navbar navbar-inverse" style="background-color: #492f92;">
  <div class="container-fluid ">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a style="color: #ffffff;" class="navbar-brand" href="#">RAD Makerspace</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav links"  >
          <li><a style="color: #ffffff;" href="/pages/home.php">Home</a></li>
          <li><a style="color: #ffffff;" href="/pages/projects.php">Projects</a></li>
          <li><a style="color: #ffffff;" href="/pages/request.php">Request</a></li>
          <li><a style="color: #ffffff;" href="/pages/stats.php">Stats</a></li>
          <li><a style="color: #ffffff;" href="/pages/resources.php">Resources</a></li>
          <li><a style="color: #ffffff;" href="/pages/feedback.php">Feedback</a></li>
          <li><a style="color: #ffffff;" href="/pages/about.php">About</a></li>
          <li><a style="color: #ffffff;" href="/pages/accountsettings.php">Account Settings</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a style="color: #ffffff;" href="/php/Logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>';

  //  '<ul class="topnav" id="adminNav">
  //   <li><a href="/pages/home.php">Home</a></li>
  //   <li><a href="/pages/projects.php">Projects</a></li>
  //   <!--<li><a href="#printers">Printers</a></li>-->
  //   <li><a href="/pages/request.php">Request</a></li>
  //   <li><a href="/pages/resources.php">Resources</a></li>
  //   <li><a href="/pages/feedback.php">Feedback</a></li>
  //   <li><a href="/pages/about.php">About</a></li>
  //   <li><a href="/pages/stats.php">Stats</a></li>
  //   <li><a href="/pages/accountsettings.php">Account Settings</a></li>
  //   <li><a href="/php/Logout.php">Logout</a></li>
  // </ul>';

  $studentNav = '<nav class=" navbar navbar-inverse" style="background-color: #492f92;">
  <div class="container-fluid ">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a style="color: #ffffff;" class="navbar-brand" href="#">RAD Makerspace</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav links"  >
          <li><a style="color: #ffffff;" href="/pages/home.php">Home</a></li>
          <li><a style="color: #ffffff;" href="/pages/request.php">Request</a></li>
          <li><a style="color: #ffffff;" href="/pages/resources.php">Resources</a></li>
          <li><a style="color: #ffffff;" href="/pages/feedback.php">Feedback</a></li>
          <li><a style="color: #ffffff;" href="/pages/about.php">About</a></li>
          <li><a style="color: #ffffff;" href="/pages/accountsettings.php">Account Settings</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a style="color: #ffffff;" href="/php/Logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>';

  // '<ul class="topnav" id="studentNave">
  //   <li><a href="/pages/home.php">Home</a></li>
  //   <li><a href="/pages/request.php">Request</a></li>
  //   <li><a href="/pages/resources.php">Resources</a></li>
  //   <li><a href="/pages/feedback.php">Feedback</a></li>
  //   <li><a href="/pages/about.php">About</a></li>
  //   <li><a href="/pages/accountsettings.php">Account Settings</a></li>
  //   <li><a href="/php/Logout.php">Logout</a></li>
  // </ul>';

  //admin check
  $usertype = require ("../php/AdminCheck.php");

  if(strcmp($usertype,'SUPER-Admin') == 0){
    echo $superAdminNav;
  }
  else if(strcmp($usertype,'Admin') == 0){
    echo $adminNav;
  }
  else
  {
    echo $studentNav;
  }

}
?>
