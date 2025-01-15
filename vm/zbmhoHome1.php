<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	$type = $_SESSION['usertype'];
	if ($type == 'VM-HO') {
		include("headervc.php");
		include("menuvc.php");
	}
	else {
		include("logout.php");
	}
	$date = date('Y-m-d');
	$empId = $_SESSION['employeeId'];
	
	$vmBranchList = mysqli_fetch_assoc(mysqli_query($con,"SELECT agentId,branch FROM vmagent WHERE agentId='$empId'"));
	$branches = explode(",", $vmBranchList['branch']);
	
	$branchData = [];
	$branchSQL = mysqli_query($con,"SELECT branchId,branchName,meet  
	FROM branch 
	WHERE branchId IN ('$branches[0]','$branches[1]','$branches[2]','$branches[3]','$branches[4]') AND branchId != '' ");
	while($row = mysqli_fetch_assoc($branchSQL)){
		$branchData[] = $row;
	}
	
	$customer = [];
	$customerDetails = mysqli_query($con, "SELECT branch,customer, concat('XXXXXX', right(contact, 4)) as contact 
	FROM everycustomer 
	WHERE branch IN ('$branches[0]','$branches[1]','$branches[2]','$branches[3]','$branches[4]') AND branch!='' AND date='$date' AND status IN ('0','Blocked')");
	while($row = mysqli_fetch_assoc($customerDetails)){
		$customer[] = $row;
	}
?>
<style>
	.list-cust{
	list-style-type: square;
	margin: 0;
	padding-left: 15px;
	}
	.li-cust{
	padding-bottom: 15px;
	}
	form h5{
	color: #123C69;
	text-transform: uppercase;
	font-size: 12px;
	}
</style>

<!--   MODAL - PASSWORD CHANGE   -->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content ">
			<div class="color-line"></div>
			<span class="fa fa-close modaldesign" data-dismiss="modal"></span>
			<div class="modal-header" style="background-color: #123C69;color: #f0f8ff;">
				<h4>EMP ID : <?php echo  $empId; ?></h4>
			</div>
			<div class="modal-body">
				<form action="vmSubmit.php" method="POST">
					<input type="hidden" name="employeeId" value="<?php echo $empId; ?>">
					<div class="form-group" style="margin-top:30px">
						<label class="col-sm-3 control-label text-success">Enter New Password</label>
						<div class="col-sm-9">
							<input style="font-weight:500;" type="text" class="form-control" name="password" value="" placeholder="PASSWORD" required>
							<span class="help-block m-b-none font-bold">DO NOT USE YOUR PERSONAL INFORMATION</span>
						</div>
					</div>
					<button style="margin-top:20px;" class="btn btn-success" name="vmPasswordChange">CHANGE</button>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="wrapper">
	<div class="row content">
		
		<div class="col-lg-5">
			<div class="hpanel">
				<div class="panel-heading hbuilt" style="margin-bottom:10px">
					<h3 class="text-success text-center">EMP ID : <span style="color:#990000"><b><?php echo $empId; ?></b></span></h3>
				</div>
				<div class="panel-body">
					<form name="add-new-customer" method="POST" class="form-horizontal" action="vmSubmit.php" onsubmit="vmSubmitNC.disabled = true; return true;">
						<input type="hidden" name="VMsubmitNCHidden" value="true">
						
						<div class="col-sm-12">
							<select class="form-control m-b" name="branchID" required>
								<option selected="true" disabled="disabled" value="">Select Branch</option>
								<?php
									foreach ($branchData as $key=>$value) {
										echo '<option  value="' . $value['branchId'] . '">' . $value['branchName'] . '</option>';
									}
								?>
							</select>
						</div>
						
						<div class="col-sm-6">
							<h5>Name</h5>
							<input type="text" name="name" id="name" placeholder="Customer Name" class="form-control" autocomplete="off" required>
						</div>
						<div class="col-sm-6">
							<h5>Mobile</h5>
							<input type="text" name="contact" id="mobile" placeholder="Contact Number" maxlength="10" class="form-control" autocomplete="off" required>
						</div>
						
						<label class="col-sm-12 control-label"><br></label>
						<div class="col-sm-4">
							<select class="form-control m-b" name="type" id="billType" required>
								<option selected="true" disabled="disabled" value="">Type</option>
								<option value="physical">Physical</option>
								<option value="release">Release</option>
							</select>
						</div>
						<div id="typeData"></div>
						
						<label class="col-sm-12 control-label"></label>
						<div class="col-sm-4">
							<h5>No of Ornaments</h5>
							<input type="text" name="itemCount" placeholder="Count" required class="form-control" autocomplete="off">
						</div>
						<div class="col-sm-4">
							<h5>Gross Weight</h5>
							<input type="text" name="grossW" placeholder="Gross W" required class="form-control" autocomplete="off">
						</div>
						<div class="col-sm-4">
							<h5>Hallmark</h5>
							<select class="form-control m-b" name="hallmark" required>
								<option selected="true" disabled="disabled" value="">Select</option>
								<option value="yes">Hallmark</option>
								<option value="no">Non Hallmark</option>
							</select>
						</div>
						<label class="col-sm-12 control-label"></label>
						<div class="col-sm-12">
							<textarea name="remarks" placeholder="Remarks" class="form-control" autocomplete="off"></textarea>
						</div>
						
						<label class="col-sm-12 control-label"><br></label>
						<div class="col-sm-4">
							<button type="button" id="send-otp-btn" class="btn btn-success btn-block"><i class="fa_Icon fa fa-paper-plane"></i> OTP</button>
							<i id="otp-timer-display">Timer : <span id="otp-timer-count"></span></i>
						</div>
						<div class="col-sm-4">
							<input type="text" placeholder="OTP" class="form-control" maxlength="6" required name="otp" id="xotp">
						</div>
						<div class="col-sm-4">
							<button class="btn btn-success btn-block" id="updateCustomer" name="vmSubmitNC" type="submit"><span class="fa_Icon fa fa-save"></span> SUBMIT</button>
						</div>
						
					</form>
				</div>
			</div>
		</div>
		
		<div class="col-lg-7">
			<div class="hpanel">
				<div class="panel-heading hbuilt text-center">
					<h3 class="text-success">ASSIGNED BRANCHES</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" style="border-collapse: separate;border-spacing: 0 15px;">
							<thead>
								<tr class="theadRow">
									<th>Branch ID</th>
									<th>Branch Name</th>
									<th>Customer</th>
									<th class='text-center'><a href="https://sfu.mirotalk.com/" target="__BLANK" style="color: #fff">Meet</a></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($branchData as $key=>$value) {
										echo "<tr style='background-color: #ECECEC;'>";
										echo "<td>".$value['branchId']."</td>";
										echo "<td class='text-success'><a title='Access Branch' href='xeveryCustomer1.php?mn=" . base64_encode($value['branchId'])."'>".$value['branchName']."</a></td>";
										echo "<td><ul class='list-cust'>";
										foreach($customer as $key=>$cval){
											if($value['branchId'] == $cval['branch']){
												echo "<li class='li-cust'><span style='margin-right:30px;'>".$cval['customer']."</span>".$cval['contact']."</li>";
											}
										}
										echo "</ul></td>";
										if($value['meet'] == ''){
											echo "<td></td>";
										}
										else{
											echo "<td class='text-center'><a href='".$value['meet']."' target='_blank'><span class='fa_Icon fa fa-video-camera'><a></td>";
										}	
										echo "</tr>";
									}
									$branchData = null;
									$customer = null;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- <button type="button" class="btn btn-success" style="float:right;margin-bottom:20px" data-toggle="modal" data-target=".bd-example-modal-sm">RESET PASSWORD</button>-->
		</div>
		
		<div style="clear:both"></div>
	</div>
	<script src="scripts/AGPLotp.js"></script>
	<script>
		const type = document.getElementById("billType");
		const typeData = document.getElementById('typeData');
		
		const physicalData = "<div class='col-sm-8 text-center'><div class='radio radio-info radio-inline'><input type='radio' value='with' name='withMetal' checked=''><label> With Gold </label></div><div class='radio radio-info radio-inline'><input type='radio' value='without' name='withMetal'><label> Without Gold </label></div></div><div class='col-sm-8 text-center' style='margin-bottom: 20px; margin-top: 10px;'><div class='radio radio-info radio-inline'><input type='radio' value='no' name='pledge' checked=''><label> Billing </label></div><div class='radio radio-info radio-inline'><input type='radio' value='yes' name='pledge'><label> Pledge </label></div></div>";
		
		const releaseData = "<div class='col-sm-4'><select class='form-control m-b' name='relSlips' required><option selected='true' disabled='disabled' value=''>Rel Slips</option><option value='yes'>With Slips</option><option value='no'>Without Slips</option></select></div><div class='col-sm-4'><input type='text' name='relAmount' placeholder='Rel Amount' class='form-control' autocomplete='off' required></div>";
		
		type.addEventListener('change', (e)=>{
			let billType = type.value;
			if(billType == 'physical'){
				typeData.innerHTML = physicalData;
			}
			else if(billType == 'release'){
				typeData.innerHTML = releaseData;
			}
		});
	</script>
	<script>
		$(document).ready(function() {
			// DISABLE / ENABLE SUBMIT BUTTON
			$('#updateCustomer').attr("disabled", true);
			$("#xotp").keyup(function() {
				var data = $('#xotp').val();
				var count = data.toString().length;
				if(count==6){
					var req = $.ajax({
						url: "../otpValid.php",
						type: "POST",
						data: {
							data
						},
					});
					req.done(function(msg) {
						$("#xotp").val(msg);
						if (msg == "OTP Validated") {
							$('#xotp').attr('readonly', 'true');
							$('#updateCustomer').attr("disabled", false);
						}
						else if (msg == "Invalid OTP") {
							alert(msg);
						}
					});
				}
			});	
		});
	</script>
<?php include("footerNew.php"); ?>
