<?php

include("../dbConnection.php");
date_default_timezone_set("Asia/Kolkata");

// $data = ["message" => "Error"];
$dial_code = "+91";
$date = date('Y-m-d');
$time = date("h:i:s");


if (isset($_REQUEST["registerBuyer"])) {
	$name = $_REQUEST['regName'];
	$contact = $_REQUEST['regMobile'];
	$dial_code = $dial_code;
	$company = $_REQUEST['regCompany'];
	$address_line1 = $_REQUEST['regAddress1'];
	$address_line2 = $_REQUEST['regAddress2'];
	$city = $_REQUEST['regCity'];
	$state = $_REQUEST['regState'];
	$country = $_REQUEST['regCountry'];
	$zip = $_REQUEST['regZip'];
	$date = $date;
	$time = $time;
	$password = $_REQUEST['regPassword'];

	// Check if the contact number already exists in the database
	$check_contact = "SELECT COUNT(*) FROM buyer_profile WHERE contact = '$contact'";
	$result = mysqli_query($con, $check_contact);
	$row = mysqli_fetch_row($result);

	if ($row[0] > 0) {
		// Contact already exists
		echo "<script>alert('This contact number is already registered. Please use a different contact number.');</script>";
		echo "<script>setTimeout(\"location.href = 'register.php';\",150);</script>";
	} else {
		// Insert the new profile if the contact doesn't exist
		$insert_profile = "INSERT INTO buyer_profile(name, contact, dial_code, company, address_line1, address_line2, city, state, country, zip, date, time, password) VALUES ('$name', '$contact', '$dial_code', '$company', '$address_line1', '$address_line2', '$city', '$state', '$country', '$zip', '$date', '$time', '$password')";

		if (mysqli_query($con, $insert_profile)) {
			echo "<script>alert('New Buyer added successfully');</script>";
			echo "<script>setTimeout(\"location.href = 'dashboard.php';\",150);</script>";
		} else {
			echo "<script>alert('Failed to add new buyer');</script>";
			echo "<script>setTimeout(\"location.href = 'register.php';\",150);</script>";
		}
	}
}
