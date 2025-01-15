<?php 
	error_reporting(E_ERROR | E_PARSE);
	date_default_timezone_set("Asia/Kolkata");
	include("dbConnection.php");
	
	$branchId = $_GET['branchId'];
	$branchQuery = mysqli_query($con, "SELECT branchName 
	FROM branch 
	WHERE branchId='$branchId' LIMIT 1");
	$branchData = mysqli_fetch_assoc($branchQuery);
	$count = mysqli_num_rows($branchQuery);
	
	$data = [];
	if($count > 0){
		$data = ["status"=>true, "branchName"=>$branchData['branchName']];
	}
	else{
		$data = ["status"=>false, "branchName"=>null];
	}	
	echo json_encode($data);
?>