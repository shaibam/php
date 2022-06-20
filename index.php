<?php
$username="udoimzgcuyxfd";
$password="lfapts0vjgny";
$database="dbnnichahvjtu4";
$mysqli = new mysqli("localhost", $username, $password, $database);
$mysqli->select_db($database) or die( "Unable to select database");
$query="INSERT metadata (id,url,account,status) VALUES (1,'some_url','account','status')";
$mysqli->query("$query");
$mysqli->close();
?>