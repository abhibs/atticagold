<?php
	error_reporting(E_ERROR | E_PARSE);
	session_start();
	$type = $_SESSION['usertype'];
	if($type == 'Branch'){
		include("header.php");
		include("menu.php");
	}
	else{
		include("logout.php");
	}
	include("dbConnection.php");
?>
<style>
	#wrapper h3{
	text-transform:uppercase;
	font-weight:600;
	font-size: 18px;
	}
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{
	background-color:#fffafa;
	}
	.text-success{
	color:#123C69;
	text-transform:uppercase;
	font-weight:800;
	font-size: 12px;
	margin: 0px 0px 0px;
	}
	.btn-primary{
	background-color:#123C69;
	}
	#wrapper .panel-body{
    box-shadow: 10px 15px 15px #999;
	border: none;
	background-color: #f5f5f5;
	border-radius:3px;	
	padding: 20px;
	}
</style>
<div id="wrapper">
	<div class="row content">
		
		<div class="col-lg-12">
			<div class="hpanel">
				<div class="panel-heading">
					<h3 class="font-light m-b-xs text-success" >
						<i style="color:#990000;" class="fa fa-pencil-square-o"></i><b> Customer Register Form</b>
					</h3>
				</div>
				<div class="panel-body">
					<form method="POST" action="submitRegister.php" id="registrationForm">
						<input type="hidden" name="branchId" value="<?php echo $_SESSION['branchCode']; ?>" >
						<div class="form-group col-lg-12">
							<label class="text-success">Customer Name</label>
							<input type="text" class="form-control" id="customerName" name="customerName" placeholder="Customer Name" required autocomplete="off">
						</div>
						<div class="form-group col-lg-6">
							<label class="text-success">Mobile Number</label>
							<input type="number" class="form-control" id="customerMobile" name="customerMobile" placeholder="Mobile Number" required maxlength="10" pattern="[0-9]{10}"
							oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autocomplete="off">
						</div>
						<div class="form-group col-lg-6">
							<label class="text-success">Gross Weight</label>
							<input type="number" class="form-control" id="customerGrossW" name="customerGrossW" placeholder="Gross Weight" required min="0" max="5000" step="0.001" autocomplete="off">
						</div>
						<div class="form-group col-lg-6">
							<label class="text-success">Type</label>
							<select name="customerType" id="customerType" class="form-control" aria-label="Default select example" required>
								<option selected="true" disabled="disabled" value="">Type</option>
								<option value="physical">Physical</option>
								<option value="release">Release</option>
							</select>
						</div>
						<div class="form-group col-lg-6">
							<label class="text-success">Preferred Language</label>
							<select name="language" id="language" class="form-control" required>
								<option selected="true" disabled="disabled" value="">Language</option>
								<option value="English">English</option>
								<option value="Hindi">Hindi</option>
								<option value="Kannada">Kannada</option>
								<option value="Tamil">Tamil</option>
								<option value="Telugu">Telugu</option>
							</select>
						</div>
						<div class="form-group col-lg-12 text-right" style="margin-top: 20px;">
							<button type="submit" class="btn btn-primary" name="HoRegistrationSubmit" style="width: 200px">Register</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
	</div>
<?php include("footer.php"); ?>