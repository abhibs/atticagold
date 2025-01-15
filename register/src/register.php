<?php

	error_reporting(E_ERROR | E_PARSE);
	date_default_timezone_set("Asia/Kolkata");
	include("dbConnection.php");

	$name = $_POST['name'];
	$mobile = $_POST['mobile'];
	$branch = $_POST['branchId'];
	$type = $_POST['type'];

	$extra = [];
	$extra["GrossW"] = isset($_POST['grossW']) ? $_POST['grossW'] : 0;
	// $extra["Language"] = $_POST['language'];
	$extra = json_encode($extra);

	if(isset($_POST['imageFile']) && !empty($_POST['imageFile'])){
		$datetime = date('Ymdhis');

		$cphoto = $_POST['imageFile'];
		$image_parts = explode(";base64,", $cphoto);
		$image_base64 = base64_decode($image_parts[1]);

		$folderPath = "../../EveryCustomerImage/";
		$imageName = $mobile.$datetime. '.jpg';
		$files = $folderPath . $imageName;
		file_put_contents($files, $image_base64);
	}
	else{
		$imageName = '';
	}

	$date = date("Y-m-d");
	$time = date("h:i:s");
	$status = "Begin";
	$reg_type = "CUST";

	$everyCustomerQuery = "INSERT INTO everycustomer(customer, contact, type, idnumber, branch, image, quotation, date, time, status, status_remark, remark, block_counter, extra, reg_type, agent) VALUES ('$name', '$mobile', '$type', '', '$branch', '$imageName', '', '$date', '$time', '$status', '', '', '0', '$extra', '$reg_type', '')";
	if(mysqli_query($con, $everyCustomerQuery)){
		$data = ["status"=>true];
	}
	else{
		$data = ["status"=>false];
	}
	echo json_encode($data);
?>
