<?php
	session_start();
	include("dbConnection.php");
	$date = date('Y-m-d');
	$files = [
	"Branch"=>"xeveryCustomer.php",
	"Master"=>"dashboard.php",
	
	"AccHead"=>"acHdDashboard.php",
	"Accounts"=>"dashboardAcc.php",
	"Accounts IMPS"=>"xapprovePhysicalIMPS.php",
	"Expense Team"=>"expenseApproval.php",
	
	"Zonal"=>"dashboardZonal.php",
	"HR"=>"blockAttend.php",

	"ApprovalTeam"=>"xviewTransaction.php",
	
	"Software"=>"branchInfo.php",
	"ITMaster"=>"expenseApprovalIT.php",
	
	"IssueHead"=>"issues.php",
	"Issuecall"=>"enquiryReport.php",
	"Call Centre"=>"issues.php",
	"Agent"=>"sms.php",
	
	"Assets"=>"inventory_stock.php",
	"Legal"=>"xBillList.php",
	"VM-AD"=>"zviewvm.php",
	"SocialMedia"=>"job.php",
	"Leads"=>"import1.php"
	];
	$ipsAllowed = ['103.139.158.138', '14.97.4.86', '202.83.19.248', '14.195.245.74'];
	$ipRestrict = ['IssueHead', 'Issuecall', 'Call Centre', 'ApprovalTeam', 'Agent', 'VM-AD', 'Leads', 'Legal'];
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if(isset($_POST["username"]) && ($_POST["username"] != "") && isset($_POST["employeeid"]) && ($_POST["employeeid"] != "" && $_POST['loginotp']=='OTP Validated')){
		
		$type = mysqli_fetch_assoc(mysqli_query($con, "SELECT type FROM users WHERE username = '$_POST[username]' LIMIT 1"));
		
		if($type['type'] == 'Branch'){
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$DEVICE = (stripos($useragent, "iPod") || stripos($useragent, "iPad") || stripos($useragent, "iPhone") || stripos($useragent, "Android") || stripos($useragent, "iOS"));
			if(!$DEVICE){
				$userSQL = mysqli_query($con, "SELECT * FROM users WHERE username='$_POST[username]' AND employeeId='$_POST[employeeid]' LIMIT 1");
				$userCount = mysqli_num_rows($userSQL);
				$userData = mysqli_fetch_assoc($userSQL);
				if($userCount > 0){
					$_SESSION['login_username'] = $userData['username'];
					$_SESSION['usertype'] = $userData['type'];
					$_SESSION['userid'] = $userData['id'];
					$_SESSION['employeeId'] = $userData['employeeId'];
					$_SESSION['branchCode'] = $userData['branch'];
					header("location:".$files['Branch']);
				}
				else{
					echo "<script type='text/javascript'>alert('Invalid Credentials!')</script>";
					echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
				}
			}
			else{
				echo "<script type='text/javascript'>alert('Mobile Access Restricted!')</script>";
				echo "<script>setTimeout(\"location.href = 'https://atticagoldcompany.com';\",150);</script>";
			}
		}
		else if($type['type'] == 'Zonal'){
			
			$userSQL = mysqli_query($con, "SELECT * FROM users WHERE username='$_POST[username]' AND password='$_POST[employeeid]' LIMIT 1");
			$userCount = mysqli_num_rows($userSQL);
			$userData = mysqli_fetch_assoc($userSQL);
			if($userCount > 0 && $userData['date'] == $date){
				$_SESSION['login_username'] = $userData['username'];
				$_SESSION['usertype'] = $userData['type'];
				$_SESSION['userid'] = $userData['id'];
				$_SESSION['employeeId'] = $userData['employeeId'];
				$_SESSION['branchCode'] = $userData['branch'];
				header("location:".$files['Zonal']);
			}
			else{
				echo "<script type='text/javascript'>alert('Access Denied!')</script>";
				echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
			}
		}
		else if($type['type'] == 'Call Centre' && array_search($ip, $ipsAllowed) === false){
			
			$userSQL = mysqli_query($con, "SELECT * FROM users WHERE username='$_POST[username]' AND password='$_POST[employeeid]' LIMIT 1");
			$userCount = mysqli_num_rows($userSQL);
			$userData = mysqli_fetch_assoc($userSQL);
			if($userCount > 0){
				$_SESSION['login_username'] = $userData['username'];
				$_SESSION['usertype'] = $userData['type'];
				$_SESSION['userid'] = $userData['id'];
				$_SESSION['employeeId'] = $userData['employeeId'];
				$_SESSION['branchCode'] = $userData['branch'];
				header("location:".$files['Call Centre']);
			}
			else{
				echo "<script type='text/javascript'>alert('Access Denied!')</script>";
				echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
			}
		}
		else{
			echo "<script type='text/javascript'>alert('You Are not Valid User!')</script>";
			echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
		}
	}                                                                       
	else if((isset($_POST["username"])) && ($_POST["username"] != "") && (isset($_POST["password"])) && ($_POST["password"] != "")) {
		$userSQL = mysqli_query($con, "SELECT * FROM users WHERE username='$_POST[username]' AND password='$_POST[password]' LIMIT 1");
		$userCount = mysqli_num_rows($userSQL);
		$userData = mysqli_fetch_assoc($userSQL);
		
		if(array_search($userData['type'], $ipRestrict) === false || (array_search($userData['type'], $ipRestrict) !== false && array_search($ip, $ipsAllowed) !== false)){
			
			if($userCount > 0 && $userData['type'] != 'Zonal' && $userData['type'] != 'Branch' && $userData['type'] != 'Call Centre'){
				if(array_key_exists($userData['type'], $files)){
					$_SESSION['login_username'] = $userData['username'];
					$_SESSION['usertype'] = $userData['type'];
					$_SESSION['userid'] = $userData['id'];
					$_SESSION['employeeId'] = $userData['employeeId'];
					$_SESSION['branchCode'] = $userData['branch'];
					
					header("location:".$files[$userData['type']]);
				}
				else{
					echo "<script type='text/javascript'>alert('You Are not Valid User!')</script>";
					echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
				}
			}
			else{
				echo "<script type='text/javascript'>alert('Invalid Credentials!')</script>";
				echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
			}
		}
		else{
			echo "<script type='text/javascript'>alert('IP Restricted!')</script>";
			echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
		}
	}
	else{
		echo "<script type='text/javascript'>alert('You Are not Valid User!')</script>";
		echo "<script>setTimeout(\"location.href = 'index.php';\",150);</script>";
	}
?>
