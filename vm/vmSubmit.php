<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	date_default_timezone_set("Asia/Kolkata");
	include("dbConnection.php");
	
	/* ------------------------------------  PASSWORD CHANGE >> zbmhoHome1.php  -------------------------------------  */
	if (isset($_POST['vmPasswordChange'])) {
		$Password = $_POST['password'];
		$empId = $_POST['employeeId'];
		$sql = "UPDATE users SET password='$Password' WHERE employeeId='$empId'";
		if (mysqli_query($con, $sql)) {
			echo "<script type='text/javascript'>alert('YOUR PASSWORD HAS BEEN CHANGED')</script>";
			echo "<script>setTimeout(\"location.href = 'zbmhoHome1.php';\",150);</script>";
		}
		else {
			echo "<script type='text/javascript'>alert('SOMETHING WENT WRONG,PLEASE TRY AGAIN.')</script>";
			echo "<script>setTimeout(\"location.href = 'zbmhoHome1.php';\",150);</script>";
		}
	}
	
	/* ------------------------------------  NEW CUSTOMER >> zbmhoHome1.php  -------------------------------------  */
	
	if (isset($_POST['VMsubmitNCHidden'])) {
	
	    $branchID = $_POST['branchID'];
		$cusname = strtoupper(trim($_POST['name']));
		$mob = $_POST['contact'];
		$type = $_POST['type'];	
			
		$extra = [];
		$extra["GrossW"] = $_POST['grossW'];
		$extra['itemCount'] = $_POST['itemCount'];
		$extra['Hallmark'] = $_POST['hallmark'];
		$extra['With'] = (isset($_POST['withMetal'])) ? $_POST['withMetal'] : 'without';
		$extra['RelAmount'] = (isset($_POST['relAmount'])) ? $_POST['relAmount'] : '';
		$extra['RelSlips'] = (isset($_POST['relSlips'])) ? $_POST['relSlips'] : '';
		$extra['Pledge'] = (isset($_POST['pledge'])) ? $_POST['pledge'] : 'no';
				
		$idnumber = $_POST['remarks'];
		$date = date('Y-m-d');
		$time = date("h:i:s");
		
		/* ======================== Check whether the customer has billed ======================== */
		$status = 0;
		$remark = "";
		$pastDate = date('Y-m-d', strtotime('-20 day', strtotime(date('Y-m-d'))));
		
		$customerPast = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as bills,
		(SELECT COUNT(*) FROM trans a WHERE a.date BETWEEN '$pastDate' AND '$date' AND a.phone='$mob' AND a.status='Approved') AS past
		FROM trans
		WHERE phone='$mob' AND status='Approved'"));
		if($customerPast['bills'] >= 3 || $customerPast['past'] > 0){
			$status = "Blocked";  
			$remark = "Blocked";
		}
		
		$extra["bills"] = $customerPast['bills'];
		$extra = json_encode($extra);
		/* ======================== End of Check whether the customer has billed ======================== */
		
		
		$fName1 = '';
		$inscon = "INSERT INTO everycustomer(customer,contact,type,idnumber,branch,image,quotation,date,time,status,status_remark,remark,block_counter,extra,reg_type,agent,agent_time) VALUES ('$cusname','$mob','$type','$idnumber','$branchID','','$fName1','$date','$time','$status','','$remark','0','$extra','VM','','$time')";
		if (mysqli_query($con, $inscon)) {
			echo "<script>setTimeout(\"location.href = 'zbmhoHome1.php';\",150);</script>";
		} 
		else {
			echo "<script type='text/javascript'>alert('SOMETHING WENT WRONG,PLEASE TRY AGAIN.')</script>";
			echo "<script>setTimeout(\"location.href = 'zbmhoHome1.php';\",150);</script>";
		}
	}
	
	/* ------------------------------------  CUSTOMER WRONG / DOUBLE ENTRY >> xeveryCustomer1.php  -------------------------------------  */
	if(isset($_POST['customerStatusChange'])){
		$id = $_POST['id'];
		$status = 'Wrong Entry';
		$sql = "UPDATE everycustomer SET status='$status' WHERE Id='$id'";
		if (mysqli_query($con, $sql)) {
			echo "<script>setTimeout(\"location.href = 'zbmhoHome1.php';\",150);</script>";
		} 
		else {
			echo "<script type='text/javascript'>alert('SOMETHING WENT WRONG,PLEASE TRY AGAIN.')</script>";
			echo "<script>setTimeout(\"location.href = 'zbmhoHome1.php';\",150);</script>";
		}
	}
	
	/* ------------------------------------  UPDATE REGISTERED CUSTOMER >> updateRegistered.php  -------------------------------------  */
	if(isset($_POST['updateRegisteredCustomer'])){

		$time = date("h:i:s");
	    	  
		$id = $_POST['id'];
			
		$extra = [];
		$extra["GrossW"] = $_POST['grossW'];
		$extra['itemCount'] = $_POST['itemCount'];
		$extra['Hallmark'] = $_POST['hallmark'];
		$extra['With'] = (isset($_POST['withMetal'])) ? $_POST['withMetal'] : 'without';
		$extra['RelAmount'] = (isset($_POST['relAmount'])) ? $_POST['relAmount'] : '';
		$extra['RelSlips'] = (isset($_POST['relSlips'])) ? $_POST['relSlips'] : '';
		$extra['Pledge'] = (isset($_POST['pledge'])) ? $_POST['pledge'] : 'no';
		$extra['Language'] = (isset($_POST['language'])) ? $_POST['language'] : 'English';
				
		$idnumber = $_POST['remarks'];
		
		
		/* ======================== Check whether the customer has billed ======================== */
		$status = 0;
		$remark = "";
		$contact = $_POST['contact'];
		
// 		$date = date('Y-m-d');
// 		$pastDate = date('Y-m-d', strtotime('-20 day', strtotime(date('Y-m-d'))));
		
// 		$customerPast = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as bills,
// 		(SELECT COUNT(*) FROM trans a WHERE a.date BETWEEN '$pastDate' AND '$date' AND a.phone='$contact' AND a.status='Approved') AS past
// 		FROM trans
// 		WHERE phone='$contact' AND status='Approved'"));
// 		if($customerPast['bills'] >= 3 || $customerPast['past'] > 0){
// 			$status = "Blocked";  
// 			$remark = "Blocked";
// 		}	
		$extra["bills"] = 0;
		$extra = json_encode($extra);
		/* ======================== End of Check whether the customer has billed ======================== */
		
		
		$inscon = "UPDATE everycustomer SET
		idNumber='$idnumber',
		status='$status',
		extra='$extra',
		agent='$_SESSION[employeeId]',
		remark='$remark',
		agent_time='$time'
		WHERE id='$id'";
		
		if (mysqli_query($con, $inscon)) {
			echo "<script>setTimeout(\"location.href = 'zbmhoHome1.php';\",150);</script>";
		} 
		else {
			echo "<script type='text/javascript'>alert('SOMETHING WENT WRONG,PLEASE TRY AGAIN.')</script>";			
			echo "<script>setTimeout(\"location.href = 'updateRegistered.php?id=".$id."';\",150);</script>";
		}
		
	}
?>
