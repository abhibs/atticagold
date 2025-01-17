<?php
session_start();
include("../dbConnection.php");

// $data = ["message" => "Error"];


if (isset($_REQUEST["loginBuyer"])) {
	$contact = $_REQUEST['loginMobile'];
	$password = $_REQUEST['loginPassword'];


	// Query to check if the contact and password match
	$get_existing = "SELECT name, contact FROM buyer_profile WHERE contact = '$contact' AND password = '$password'";

	// Execute the query using mysqli_query()
	$result = mysqli_query($con, $get_existing);

	if ($result) {
		// Check if a record is returned
		if (mysqli_num_rows($result) > 0) {
			// Fetch the result row
			$existing_data = mysqli_fetch_assoc($result);
			$name = $existing_data['name'];
			$contact_result = $existing_data['contact'];

			// Store session data for logged-in buyer (optional)
			session_start();
			$_SESSION['buyer_mobile'] = $contact_result;
			$_SESSION['buyer_name'] = $name;

			// Redirect to dashboard
			echo "<script>alert('Buyer Login successfully');</script>";
			echo "<script>setTimeout(\"location.href = 'dashboard.php';\",150);</script>";
			exit(); // Make sure no further code runs after the redirect
		} else {
			// No matching buyer found
			// echo "No buyer found with this contact and password.";
			echo "<script>alert('mobile number and password not matched.');</script>";
			echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
		}
	}
}
