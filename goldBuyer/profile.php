<?php

session_start();
date_default_timezone_set("Asia/Kolkata");

if (
	isset($_SESSION['buyer_mobile']) && $_SESSION['buyer_mobile'] != '' &&
	isset($_SESSION['buyer_name']) && $_SESSION['buyer_name'] != ''
) {

	include("header.php");
	include("dbConnection.php");

	$profile = $con->prepare("SELECT * FROM buyer_profile WHERE contact = :uid");
	$profile->execute([":uid" => $_SESSION['buyer_mobile']]);
	$profile_data = $profile->fetch();

?>


<?php
	include("footer.php");
} else {
	header('Location: index.php');
	die();
}
?>