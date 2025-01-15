<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	include("dbConnection.php");
	date_default_timezone_set("Asia/Kolkata");
	$date = date('Y-m-d');
	$time = date("h:i:s");
	$branchCode = $_SESSION['branchCode'];
	
	/*  ADD NEW CUSTOMER ( @ xeveryCustomer.php )  */
	if(isset($_POST['submitNC'])){
	    
	   	$customer = strtoupper(trim($_POST['cusname']));
		$contact = trim($_POST['cusmob']);
		$type = $_POST['customerType'];
		
		/* ======================== Check whether the customer has billed ======================== */	
		$status = "Begin";
		$remark = "";
// 		$pastDate = date('Y-m-d', strtotime('-20 day', strtotime(date('Y-m-d'))));
// 		$customerPast = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as bills,
// 		(SELECT COUNT(*) FROM trans a WHERE a.date BETWEEN '$pastDate' AND '$date' AND a.phone='$contact' AND a.status='Approved') AS past
// 		FROM trans
// 		WHERE phone='$contact' AND status='Approved'"));
// 		if($customerPast['bills'] > 3 || $customerPast['past'] > 0){
// 			$status = "Blocked"; 
// 			$remark = "Blocked";
// 		}
		/* ======================== End of Check whether the customer has billed ======================== */
		
		$extra = [];
		$extra["GrossW"] = '';
		$extra["bills"] = 0;
		$extra = json_encode($extra);
			
		$query = "INSERT INTO everycustomer(customer,contact,type,idnumber,branch,image,quotation,date,time,status,status_remark,remark,block_counter,extra,reg_type,agent,agent_time) VALUES ('$customer','$contact','$type','','$branchCode','','','$date','$time','$status','','$remark','0','$extra','BM','', '')";
		if(mysqli_query($con,$query)){
			header("location:xeveryCustomer.php");
		}
		else{
			echo "<script type='text/javascript'>alert('Error Storing Data!')</script>";
			echo "<script>setTimeout(\"location.href = 'xeveryCustomer.php';\",150);</script>";
		}
	}
	
	/*  WALKIN DATA ( @ xbranchEnquiry.php ) */
	if (isset($_POST['submitremarks'])) {
		
		//POSTED VALUES
		$id = $_POST['id'];
		$cusname = $_POST['cusname'];
		$mob = $_POST['cusmob'];
		$type = $_POST['typeGold'];
		$metal = $_POST['metal'];
		$gross = $_POST['gwt'];
		$net = $_POST['nwt'];
		$purity = $_POST['purity'];
		$ramt = (isset($_POST['ramt'])) ? $_POST['ramt'] : '';
		$havingG = ($type == 'physical') ? $_POST['havingG'] : "without";
		$remarks = $_POST['remarks'];
		$quotation = $_POST['quotation'];
		$bills = $_POST['bills'];
		$givenRate = $_POST['givenRate'];
		
		//UN INITIALIZED VARIABLES
		$a = '';
		$b = 0;
		
	    $sql = "INSERT INTO walkin(name, mobile, gold, havingG, metal, issue, gwt, nwt, purity, ramt, branchId, agent_id, followUp, comment, remarks, zonal_remarks, status, emp_type, date, indate, time, quotation, bills, quot_rate) VALUES('$cusname', '$mob', '$type', '$havingG', '$metal', '$a', '$gross', '$net', '$purity', '$ramt', '$branchCode', '$a', '$a', '$a', '$remarks', '$a', '$b', '$a', '$date', '$a', '$time', '$quotation', '$bills', '$givenRate');";
		$sql .= "UPDATE everycustomer SET status='Enquiry' WHERE id='$id'";
		
		if (mysqli_multi_query($con, $sql)) {
			echo "<script type='text/javascript'>alert('Customer Remarks Added Successfully!')</script>";
			echo "<script>setTimeout(\"location.href = 'xeveryCustomer.php';\",150);</script>";
		} 
		else {
			echo "<script type='text/javascript'>alert('Error Storing Data!')</script>";
			echo "<script>setTimeout(\"location.href = 'xeveryCustomer.php';\",150);</script>";
		}
	}
	
	
	/*  ADD NEW CUSTOMER DETAILS ( @ xaddCustomer.php )   */
	if(isset($_POST["submitCustomer"])){
		$customerId = $_POST['cusId'];
		$name = strtoupper($_POST['name']);
		$gender = $_POST['gender'];
		$dob = $_POST['day']."-".$_POST['month']."-".$_POST['year'];
		$mobile = $_POST['mobile'];
		
		$caline = $_POST['caline'];
		$clocality = $_POST['clocality'];
		$cland = $_POST['cland'];
		$cstate = $_POST['cstate'];
		$ccity = $_POST['ccity'];
		$cpin = $_POST['cpin'];
		
		$IDproof = $_POST['idProof'];
		$IDproofNum = $_POST['idProofNum'];
		$ADDproof = $_POST['addProof'];
		$ADDproofNum = $_POST['addProofNum'];
		
		$additionalPerson = $_POST['relation'];
		$additionalContact = $_POST['rcontact'];
		
		$type = $_POST['typeGold'];
		$x = '';
		$y = 0;
		if(isset($_POST['image']) && !empty($_POST['image'])){
			$cphoto = $_POST['image'];
			$datetime = date('Ymdhis');
			$folderPath = "CustomerImage/";
			$image_parts = explode(";base64,", $cphoto);
			//$image_type_aux = explode("image/", $image_parts[0]);
			//$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$fName = $customerId.$datetime. '.jpg';
			$fName1 = "CustomerImage/".$customerId.$datetime. '.jpg';
			$files = $folderPath . $fName;
			file_put_contents($files, $image_base64);
		}
// 		else if(!empty($_POST['everyCustomerImage'])){
// 			$fName1 = $_POST['everyCustomerImage'];
// 		}
		else{
			$fName1 = '';
		}
		$sql = "INSERT INTO customer(customerId,name,gender,dob,mobile,amobile,paline,pcity,pstate,ppin,pland,plocality,caline,ccity,cstate,cpin,cland,clocality,resident,idProof,addProof,idFile,addFile,idNumber,addNumber,date,customerImage,time,relation,rcontact,cusThump,custSign) VALUES ('$customerId','$name','$gender','$dob','$mobile','$x','$caline','$ccity','$cstate','$cpin','$cland','$clocality','$caline','$ccity','$cstate','$cpin','$cland','$clocality','$x','$IDproof','$ADDproof','$x','$x','$IDproofNum','$ADDproofNum','$date','$fName1','$time','$additionalPerson','$additionalContact','$x','$x')";
		
		if(mysqli_query($con,$sql)){
			echo header("location:xsuccess.php?type=".$type);
		}
		else{
			echo "<script>alert('Customer Registration Failed!')</script>";
			echo "<script>setTimeout(\"location.href = 'xeveryCustomer.php';\",150);</script>";
		}
	}
	
	/*  UPDATE EXISTING CUSTOMER DETAILS ( @ xexistingCustomer.php )  */
	if(isset($_POST["updateCustomer"])){
        $customerId = $_POST['cusId'];
		$mobile = $_POST['mobile'];
		$additionalPerson = $_POST['relation'];
		$additionalContact = $_POST['rcontact'];
		
		$caline = $_POST['caline'];
		$clocality = $_POST['clocality'];
		$cland = $_POST['cland'];
		$cstate = $_POST['cstate'];
		$ccity = $_POST['ccity'];
		$cpin = $_POST['cpin'];
		
		$IDproof = $_POST['idProof'];
		$IDproofNum = $_POST['idProofNum'];
		$ADDproof = $_POST['addProof'];
		$ADDproofNum = $_POST['addProofNum'];
		
		if(isset($_POST['image']) && !empty($_POST['image'])){
			$cphoto = $_POST['image'];
			$datetime = date('Ymdhis');
			$folderPath = "CustomerImage/";
			$image_parts = explode(";base64,", $cphoto);
			//$image_type_aux = explode("image/", $image_parts[0]);
			//$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$fName = $customerId.$datetime. '.jpg';
			$fName1 = "CustomerImage/".$customerId.$datetime. '.jpg';
			$files = $folderPath . $fName;
			file_put_contents($files, $image_base64);
		}
// 		else if(!empty($_POST['everyCustomerImage'])){
// 			$fName1 = $_POST['everyCustomerImage'];
// 		}
		else{
			$fName1 = '';
		}
		$type = $_POST['typeGold'];
		$sql = "UPDATE customer SET relation='$additionalPerson', rcontact='$additionalContact', idNumber='$IDproofNum', addNumber='$ADDproofNum', customerImage='$fName1', idProof='$IDproof', addProof='$ADDproof', caline='$caline', ccity='$ccity', cstate='$cstate', cpin='$cpin', cland='$cland', clocality='$clocality', paline='$caline', pcity='$ccity', pstate='$cstate', ppin='$cpin', pland='$cland', plocality='$clocality' WHERE mobile='$mobile'";
		
		if(mysqli_query($con,$sql)){
			echo header("location:xsuccess.php?type=".$type);
		}
		else{
			echo "<script>alert('Customer Registration Failed!')</script>";
			echo "<script>setTimeout(\"location.href = 'xeveryCustomer.php';\",150);</script>";
		}
	}
	
	/*  ADD NEW CUSTOMER - ZONAL ( @ addCustomerZonal.php )  */
	if(isset($_POST['zonalSubmitNC'])){
	    
	    $userType = $_POST['userType'];
	    $branchID = $_POST['branchID'];
		$cusname = strtoupper(trim($_POST['name']));
		$mob = $_POST['contact'];
		$type = $_POST['type'];	
		
		/* ======================== Check whether the customer has billed ======================== */	
		$status = 0;
		$remark = "";
		$pastDate = date('Y-m-d', strtotime('-20 day', strtotime(date('Y-m-d'))));
		$customerPast = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as bills,
		(SELECT COUNT(*) FROM trans a WHERE a.date BETWEEN '$pastDate' AND '$date' AND a.phone='$mob' AND a.status='Approved') AS past
		FROM trans
		WHERE phone='$mob' AND status='Approved'"));
		if($customerPast['bills'] > 3 || $customerPast['past'] > 0){
			$status = "Blocked";  
			$remark = "Blocked";
		}
		/* ======================== End of Check whether the customer has billed ======================== */	
			
		$extra = [];
		$extra["GrossW"] = $_POST['grossW'];
		$extra['itemCount'] = $_POST['itemCount'];
		$extra['Hallmark'] = $_POST['hallmark'];
		$extra['With'] = (isset($_POST['withMetal'])) ? $_POST['withMetal'] : 'without';
		$extra['RelAmount'] = (isset($_POST['relAmount'])) ? $_POST['relAmount'] : '';
		$extra['RelSlips'] = (isset($_POST['relSlips'])) ? $_POST['relSlips'] : '';
		$extra['Pledge'] = (isset($_POST['pledge'])) ? $_POST['pledge'] : 'no';
		$extra["bills"] = $customerPast['bills'];
		$extra = json_encode($extra);
				
		$idnumber = $_POST['remarks'];
		$fName1 = '';
		
		$inscon = "INSERT INTO everycustomer(customer,contact,type,idnumber,branch,image,quotation,date,time,status,status_remark,remark,block_counter,extra,reg_type,agent,agent_time) VALUES ('$cusname','$mob','$type','$idnumber','$branchID','','$fName1','$date','$time','$status','','$remark','0','$extra','$userType','','')";
		if (mysqli_query($con, $inscon)) {
			echo "<script>setTimeout(\"location.href = 'addCustomerZonal.php';\",150);</script>";
		} 
		else {
			echo "<script type='text/javascript'>alert('SOMETHING WENT WRONG,PLEASE TRY AGAIN.')</script>";
			echo "<script>setTimeout(\"location.href = 'addCustomerZonal.php';\",150);</script>";
		}
		
	}
?>
