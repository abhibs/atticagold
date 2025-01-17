<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

include("../dbConnection.php");

$date = date('Y-m-d');
$time = date("h:i:s");

if (isset($_REQUEST["quotSubmit"])) {
	$contact = $_REQUEST['quotContact'];
	$rate = $_REQUEST['quotRate'];
	$quantity = $_REQUEST['quotQuantity'];
	$payment = $_REQUEST['payment'];
	$status = 'Pending';
	$date = $date;
	$time = $time;

	// Insert the new profile if the contact doesn't exist
	$insert_quot = "INSERT INTO buyer_quot(contact,rate,quantity,payment,status,date,time) VALUES('$contact','$rate','$quantity','$payment','$status','$date','$time')";

	if (mysqli_query($con, $insert_quot)) {
		echo "<script>alert('New Quot added successfully');</script>";
		echo "<script>setTimeout(\"location.href = 'dashboard.php';\",150);</script>";
	} else {
		echo "<script>alert('Failed to add new buyer');</script>";
		echo "<script>setTimeout(\"location.href = 'dashboard.php';\",150);</script>";
	}
}
