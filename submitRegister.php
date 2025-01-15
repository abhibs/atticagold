<?php 
	error_reporting(E_ERROR | E_PARSE);
	date_default_timezone_set("Asia/Kolkata");
	include("dbConnection.php");
	
	if(isset($_POST['HoRegistrationSubmit'])){
		$branch = $_POST['branchId'];
		
		$name = $_POST['customerName'];
		$mobile = $_POST['customerMobile'];	
		$type = $_POST['customerType'];
		
		$extra = [];
		$extra["GrossW"] = $_POST['customerGrossW'];
		$extra["Language"] = $_POST['language'];
		$extra = json_encode($extra);
		
		$date = date("Y-m-d");
		$time = date("h:i:s");
		
		$everyCustomerQuery = "INSERT INTO everycustomer(customer, contact, type, idnumber, branch, image, quotation, date, time, status, status_remark, remark, block_counter, extra, reg_type, agent) VALUES ('$name', '$mobile', '$type', '', '$branch', '', '', '$date', '$time', 'Begin', '', '', '0', '$extra', 'BM','')";
		
		if (mysqli_query($con, $everyCustomerQuery)) {
			echo "<script type='text/javascript'>alert('Customer Registered Successfully.')</script>";
			echo "<script>setTimeout(\"location.href = 'registerBranch.php';\",150);</script>";
		}
		else {
			echo "<script type='text/javascript'>alert('SOMETHING WENT WRONG,PLEASE TRY AGAIN.')</script>";
			echo "<script>setTimeout(\"location.href = 'registerBranch.php';\",150);</script>";
		}
	}
?>