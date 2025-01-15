<?php
	session_start();
    include("dbConnection.php");    
	
    // EDIT BRANCH ( @ editBranch.php )
    if(isset($_POST["editBranch"])) {
	    
		$branchId = $_POST['branchId'];
		$colName = $_POST['colName'];
		$colValue = $_POST['colValue'];
		$sql = "UPDATE branch SET ".$colName."='".$colValue."' WHERE branchId='$branchId'";
		
		if(mysqli_query($con,$sql)){
			echo '1';
		}
		else{
			echo '0';
		}
	}
	
	// EDIT BILL DATA ( @ editTrans.php 
	if(isset($_POST["editTrans"])){
		if( isset($_POST["transId"]) && $_POST["transId"]!=''){
			
			$transId = $_POST['transId'];
			$colName = $_POST['colName'];
			$colValue = $_POST['colValue'];
			$sql = "UPDATE trans SET ".$colName."='".$colValue."' WHERE id='$transId'";
			
			if(mysqli_query($con,$sql)){
				echo '1';
			}
			else{
				echo '0';
			}
			
		}
	}
	// EDIT PLEDGE DATA  ( @ editpledge.php
	if(isset($_POST["editPledge"])){
		if(isset($_POST["pledgeId"]) && $_POST["pledgeId"]!=''){
			$pledgeId=$_POST['pledgeId'];
			$colName=$_POST['colName'];
			$colValue=$_POST['colValue'];
			$sql="UPDATE pledge_bill SET ".$colName."='".$colValue."' WHERE id='$pledgeId'";

			if(mysqli_query($con,$sql)){
				echo '1';
			}
			else{
				echo '0';
			}
		}
	}
	
	// UPDATE TRANS DATA ( @ searchGoldSendData.php 
	if(isset($_POST["updateGoldSendData"])){
		
		if(isset($_POST['trans_id']) && $_POST['trans_id']!=''){
			$id = $_POST['trans_id'];
			$branchId = $_POST['branchId'];
			$sta = $_POST['sta'];
			$staDate = $_POST['staDate'];
			$sql = "UPDATE trans SET sta='$sta', staDate='$staDate' WHERE id='$id'";
			if(mysqli_query($con,$sql)){
				echo header("location:searchGoldSendData.php?branchId=".$branchId);
			}
			else{
				echo "<script>alert('Error Occurred')</script>";
				echo header("location:searchGoldSendData.php?branchId=".$branchId);
			}
		}
	}
	
	// REJECT WALKING DATA @ searchWalkinData.php 
	if(isset($_POST["editWalkinReject"])){
		$row_id = $_POST['row_id'];
		$sql = "UPDATE walkin SET issue='Rejected', status=1 WHERE id='$row_id'";
		if(mysqli_query($con,$sql)){
		    echo '1';
		}
		else{
			echo '0';
		}
	}
	
	// DELETE WALKING DATA @ searchWalkinData.php 
	if(isset($_POST["editWalkinDelete"])){
		$row_id = $_POST['row_id'];
		$sql = "DELETE FROM walkin WHERE id='$row_id'";
		if(mysqli_query($con,$sql)){
		    echo '1';
		}
		else{
			echo '0';
		}
	}
	
	// ACCEPT / REJECT GOLD BUYER DATA 
	if(isset($_POST["updateBuyerQuotData"])){
	
		$id = $_POST['id'];
		$status = $_POST['status'];
		
		$sql = "UPDATE buyer_quot SET status='$status' WHERE id='$id'";
		if(mysqli_query($con,$sql)){
		   echo json_encode(["message"=>"success"]);
		}
		else{
			echo json_encode(["message"=>"error"]);
		}
	}
	
	// ZONAL-ASSIGN-BRANCH @assignZonalBranch.php
	if(isset($_POST['updateZonalBranch'])){
		$branchId = $_POST['branchId'];
		$zonalEmpId = $_POST['zonalEmpId'];

		$sql = "UPDATE branch SET ezviz_vc='$zonalEmpId' WHERE branchId='$branchId'";
		if(mysqli_query($con,$sql)){
		   echo json_encode(["message"=>"success"]);
		}
		else{
			echo json_encode(["message"=>"error"]);
		}
	}

	
?>
