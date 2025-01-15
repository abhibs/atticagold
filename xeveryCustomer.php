<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	date_default_timezone_set('Asia/Calcutta');
	$type = $_SESSION['usertype'];
	if($type == 'Branch'){
		include("header.php");
		include("menu.php");
	}
	else{
		include("logout.php");
	}
	
	include("dbConnection.php");
	$branchCode = $_SESSION['branchCode'];
	$date = date('Y-m-d');
	
	/* BRANCH DATA */
	$branchData = mysqli_fetch_assoc(mysqli_query($con,"SELECT branchName, meet,
	(CASE
	WHEN priceId=1 THEN 'Bangalore'
	WHEN priceId=2 THEN 'Karnataka'
	WHEN priceId=3 THEN 'Andhra Pradesh'
	WHEN priceId=4 THEN 'Telangana'
	WHEN priceId=5 THEN 'Chennai'
	WHEN priceId=6 THEN 'Tamilnadu'
	END) AS city
	FROM branch
	WHERE branchId='$branchCode'"));
	
	/* MISC DATA */
	$miscData = mysqli_fetch_assoc(mysqli_query($con,"SELECT
	(SELECT COUNT(DISTINCT(contact)) AS totalWalkin FROM everycustomer WHERE date='$date' AND branch='$branchCode' AND status NOT IN ('Double Entry','Wrong Entry')) AS totalWalkin,
	(SELECT COUNT(id) AS totalSold FROM trans WHERE date='$date' AND branchId='$branchCode' AND status='Approved') AS totalSold,
	(SELECT date FROM closing WHERE branchId='$branchCode' ORDER BY date DESC LIMIT 1) AS ClosingDate,
	(SELECT day FROM misc WHERE purpose='Add Customer') AS addDate,
	(SELECT name FROM employee WHERE empId='$_SESSION[employeeId]') AS BMname
	"));
	
	/* GOLD RATE */
	$goldRate = mysqli_fetch_assoc(mysqli_query($con,"SELECT cash,transferRate
	FROM gold
	WHERE type='Gold' AND date='$date' AND city='$branchData[city]'
	ORDER BY id DESC
	LIMIT 1"));
	
	/* SILVER RATE */
	$silverRate = mysqli_fetch_assoc(mysqli_query($con,"SELECT cash
	FROM gold
	WHERE type='Silver' AND date='$date' AND city='$branchData[city]'
	ORDER BY id DESC
	LIMIT 1"));
	
?>
<link rel="stylesheet" href="vendor/sweetalert/lib/sweet-alert.css" />
<style>
	#wrapper h3{
	text-transform:uppercase;
	font-weight:600;
	font-size: 14px;
	}
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{
	background-color:#fffafa;
	}
	.quotation h3{
	color:#123C69;
	font-size: 18px!important;
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
	thead {
	text-transform:uppercase;
	background-color:#123C69;
	}
	thead tr{
	color: #f2f2f2;
	font-size:10px;
	}
	.btn-success{
	display:inline-block;
	padding:0.7em 1.4em;
	margin:0 0.3em 0.3em 0;
	border-radius:0.15em;
	box-sizing: border-box;
	text-decoration:none;
	font-size: 12px;
	font-family:'Roboto',sans-serif;
	text-transform:uppercase;
	color:#fffafa;
	background-color:#123C69;
	box-shadow:inset 0 -0.6em 0 -0.35em rgba(0,0,0,0.17);
	text-align:center;
	position:relative;
	}
	#wrapper .panel-body{
	box-shadow: 10px 15px 15px #999;
	border: none;
	background-color: #f5f5f5;
	border-radius:3px;
	padding: 20px;
	}
	.tooltip .tooltiptext {
	visibility: hidden;
	width: 120px;
	background-color: #990000;
	color: #fff;
	text-align: center;
	border-radius: 6px;
	padding: 5px 0;
	position: absolute;
	z-index: 1;
	top: -5px;
	right: 105%;
	}
	.tooltip:hover .tooltiptext {
	visibility: visible;
	}
	.fa-icon-color{
	color: #990000;
	}
	.getRate{
	border: 1px solid #123C69;
	color: #123C69;
	border-radius: 3px;
	}
	.getRate:hover{
	color: #ffffff;
	background-color: #123C69;
	border-radius: 0px;
	}
	
	.tooltipBilling:hover .tooltiptextBilling {
	visibility: visible;
	}
	.tooltipBilling .tooltiptextBilling {
	visibility: hidden;
	width: 120px;
	background-color: #990000;
	color: #fff;
	text-align: center;
	border-radius: 6px;
	padding: 5px 0;
	position: absolute;
	z-index: 1;
	top: -5px;
	right: -135%;
	}
	
</style>

<!--   EVERY CUSTOMER MODAL   -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-sm" style="width:500px;">
		<div class="modal-content">
			<div class="color-line"></div>
			<span class="fa fa-close modaldesign" data-dismiss="modal"></span>
			<div class="modal-header" style="background-color: #123C69;color: #f0f8ff;"><h3>ADD CUSTOMER</h3></div>
			<div class="modal-body" style="padding-right: 40px;">
				<form method="POST" class="form-horizontal" action="xsubmit.php">
					<input type="hidden" value="<?php echo $branchCode; ?>" class="form-control" name="branchid">
					<div class="form-group">
						<label class="col-sm-2 control-label text-success">Name</label>
						<div class="col-sm-10">
							<input type="text" name="cusname" placeholder="Customer Name" required class="form-control" autocomplete="off">
						</div>
					</div>
					<div class="form-group" style="margin-top:30px">
						<label class="col-sm-2 control-label text-success">Mobile</label>
						<div class="col-sm-10">						
							<input type="number" class="form-control" name="cusmob" placeholder="Contact Number" required maxlength="10" pattern="[0-9]{10}" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autocomplete="off">
						</div>
					</div>
					<div class="form-group" style="margin-top:30px">
						<label class="col-sm-2 control-label text-success">Type</label>
						<div class="col-sm-10">						
							<select name="customerType" class="form-control" aria-label="Default select example" required>
								<option selected="true" disabled="disabled" value="">Type</option>
								<option value="physical">Physical</option>
								<option value="release">Release</option>
							</select>
						</div>
					</div>
					<div class="form-group" style="margin-top:30px">
						<div class="col-sm-4 col-sm-offset-8">
							<button class="btn btn-success btn-block" name="submitNC" type="submit"><span style="color:#ffcf40" class="fa fa-save"></span> Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<!--   QUOTATION  MODAL   -->
<div class="modal fade" id="quotationModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:1050px;">
		<div class="modal-content">
			<div class="color-line"></div>
			<span class="fa fa-close modaldesign" data-dismiss="modal"></span>
			<form method="POST" action="quotationImageUpload.php" enctype="multipart/form-data" id="qForm">
				<input type="hidden" name="branchId" value="<?php echo $branchCode; ?>">
				<input type="hidden" id="everycustomerID" name="ecID">
				<input type="hidden" id="quotationImage" name="quotationImage">
				<input type="hidden" id="givenRate" name="givenRate">
				<div class="panel-body">
					<div class="panel-heading">
						<h3 class="panel-title">
							<a class="btn btn-success btn-user" style="color:#fff" data-toggle="collapse" data-parent="#accordion" href="#collapse1">Physical</a>
							<a class="btn btn-success btn-user" style="color:#fff" data-toggle="collapse" data-parent="#accordion" href="#collapse2">Release</a>
						</h3>
					</div>
					<div class="panel-group" id="accordion">
						<div class="panel panel-default" >
							<div id="collapse1" class="panel-collapse collapse">
								<div class="panel-body">
									<div class="col-xs-12">
										<!--  QUOTATION PHYSICAL GOLD  -->
										<div class="container-fluid" style="padding:0px;">
											<div class="col-xs-2" style="padding:10px;background-color:#ddd;" id="qPhysicalData">
												<select class="form-control">
													<option selected="true" disabled="disabled" value="">Gold / Silver</option>												
													<option value="<?php echo $goldRate['cash']; ?>">Gold (Cash)</option>
													<option value="<?php echo $goldRate['transferRate']; ?>">Gold (IMPS)</option>
													<option value="<?php echo $silverRate['cash']; ?>">Silver</option>
												</select>
												<select class="form-control" style="margin-top:7px">
													<option selected="true" disabled="disabled" value="">Ornament</option>
													<?php
														$ornamentList = ['22 carot Biscuit (91.6)', '24 carot Biscuit (99.9)', '22 carot Coin (91.6)', '24 carot Coin (99.9)', 'Anklets','Armlets', 'Baby Bangles' ,'Bangles', 'Bracelet', 'Broad Bangles', 'Chain', 'Chain with Locket', 'Chain with Black Beads', 'Drops', 'Ear Rings', 'Gold Bar','Head Locket', 'Locket', 'Matti', 'Necklace', 'Ring', 'Small Gold Piece', 'Studs', 'Studs And Drops', 'Thala/Mangalya Chain', 'Thala/Mangalya Chain with Black Beads', 'Waist Belt/Chain', 'Silver Bar', 'Silver Items','Others'];
														foreach($ornamentList as $item){
															echo "<option value='".$item."'>".$item."</option>";
														}
													?>
												</select>
												<input type="text" placeholder="Gross wgt" class="form-control" autocomplete="off" style="margin-top:7px">
												<input type="text" placeholder="Stone wgt" class="form-control" autocomplete="off" style="margin-top:7px">
												<input type="text" placeholder="Net wgt" class="form-control" autocomplete="off" readonly style="margin-top:7px">
												<select class="form-control" style="margin-top:7px">
													<option selected="true" disabled="disabled" value="">PURITY</option>
													<option value="1001">24K</option>
													<option value="1002">916 (Hallmark)</option>
													<option value="1003">916 (Non-Hallmark)</option>
													<option value="1004">(75% to 84%)</option>
													<option value="1005">(0% to 74%)</option>
													<option value="Silver">Silver</option>
												</select>
												<input type="text" placeholder="System Purity" class="form-control" autocomplete="off" style="margin-top:7px">
												<div class="system_purity_text font-bold text-info text-right" style="font-size: 11px;"> </div>
												<input type="text" placeholder="Given Purity" class="form-control" autocomplete="off" style="margin-top:7px">
												<div class="given_purity_text font-bold text-info text-right" style="font-size: 11px;"> </div>
												<input type="button" class="btn btn-primary btn-block" value="+" style="margin-top:7px">
											</div>
											<div class="col-xs-10" id="physicalGold">
												<div class="col-xs-12" style="padding:10px; background-color:#ddd; margin-bottom:5px;">
													<div class="col-xs-2">
														<label class="text-success">Physical</label>
													</div>
													<div class="col-xs-4">
														<label class="text-success"><span class="fa fa-sign-in fa-icon-color"></span> | <?php echo $miscData['BMname'];?></label>
													</div>
													<div class="col-xs-3">
														<label class="text-success"><span class="fa fa-bank fa-icon-color"></span> | <?php echo $branchData['branchName'];?></label>
													</div>
													<div class="col-xs-3">
														<label class="text-success"><span class="fa fa-user-circle-o fa-icon-color"></span> | <span class="qCustomerName"></span></label>
													</div>
												</div>
												<div class="col-sm-12" style="padding:0px;">
													<table class="table table-bordered table-hover" id="qTablePhysical">
														<thead>
															<tr>
																<th>Ornament</th>
																<th>Gross W</th>
																<th>Stone</th>
																<th>Net W</th>
																<th>Code</th>
																<!-- <th>System Purity</th> -->
																<th>Purity</th>
																<th>Rate</th>
																<th>Gross Amt</th>
																<th>Delete</th>
															</tr>
														</thead>
														<tbody id="qPhysicalTableBody"></tbody>
														<tfoot id="qPhysicalTableFoot" style="background-color:#ddd;font-weight:600;">
															<tr>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<!-- <td></td> -->
																<td></td>
																<td></td>
																<td></td>
																<td></td>
															</tr>
														</tfoot>
													</table>
												</div>
												<div class="col-sm-12" style=" background-color:#ddd; margin-bottom:5px; padding-top:10px;">
													<div class="col-sm-4">
														<div class="input-group m-b">
															<span class="input-group-addon text-success">Gross Amt</span>
															<input type="text" id="qGrossAPhysical" readonly class="form-control">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="input-group">
															<input type="text" id="qMarginPercPhysical" class="form-control">
															<span class="input-group-addon text-success">% |Margin</span>
															<input type="text" id="qMarginAPhysical" readonly class="form-control">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="input-group m-b">
															<span class="input-group-addon text-success">Net Amt</span>
															<input type="text" id="qNetAPhysical" readonly class="form-control">
														</div>
													</div>
												</div>
												<div class="col-sm-12" style="padding-right:0px;padding-left:0px;">
													<textarea  style="resize:none;height:100%;width:100%;margin:0px; border:1px solid #d9d9d9;" placeholder="Remarks"></textarea>
												</div>
											</div>
										</div>
										<div class="col-xs-12" align="right">
											<button type="button" class="btn btn-success" style="margin-top:10px;" id="sendPhysicalQuotButton"><i class="fa fa-camera" style="color:#ffcf40"></i> Save Quotation</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div id="collapse2" class="panel-collapse collapse">
								<div class="panel-body">
									<div class="col-xs-12">
										<!--  QUOTATION RELEASE  -->
										<div class="container-fluid" id="releaseGold" style="padding:0px">
											<div class="col-xs-12" style="padding:10px; background-color:#ddd; margin-bottom:5px">
												<div class="col-xs-2">
													<label class="text-success">Release</label>
												</div>
												<div class="col-xs-4">
													<label class="text-success"><span class="fa fa-sign-in fa-icon-color"></span> | <?php echo $miscData['BMname'];?></label>
												</div>
												<div class="col-xs-3">
													<label class="text-success"><span class="fa fa-bank fa-icon-color"></span> | <?php echo $branchData['branchName'];?></label>
												</div>
												<div class="col-xs-3">
													<label class="text-success"><span class="fa fa-user-circle-o fa-icon-color"></span> | <span class="qCustomerName"></span></label>
												</div>
											</div>
											<div class="col-xs-4" style="padding:10px; background-color:#ddd;">
												<div class="col-xs-6">
													<label class="text-success">Rate</label>
													<select class="form-control" id="qReleaseRate">
														<option selected="true" disabled="disabled" value="">Gold / Silver</option>												
														<option value="<?php echo $goldRate['cash']; ?>">Gold (Cash)</option>
														<option value="<?php echo $goldRate['transferRate']; ?>">Gold (IMPS)</option>
														<option value="<?php echo $silverRate['cash']; ?>">Silver</option>
													</select>
												</div>
												<div class="col-xs-6">
													<label class="text-success">Release Amount</label>
													<input type="text" id="qReleaseA" class="form-control" placeholder="Release Amount">
												</div>
												<div class="col-xs-6">
													<label class="text-success">Gross Weight</label>
													<input type="text" id="qReleaseGrossW" class="form-control" placeholder="Gross Weight">
												</div>
												<div class="col-xs-6">
													<label class="text-success">Net Weight</label>
													<input type="text" id="qReleaseNetW" class="form-control" placeholder="Net Weight">
												</div>
												<div class="col-xs-6">
													<label class="text-success">Release Purity (%)</label>
													<input type="text" id="qReleasePurity" class="form-control" placeholder="Purity %" readonly>
												</div>
												<div class="col-xs-6" style="text-align:center;margin-top:22px">
													<button class="btn btn-success" type="button" id="quotation-button-release">
														<span style="color:#ffcf40" class="fa fa-calculator"></span>
													</button>
												</div>
											</div>
											<div class="col-xs-8">
												<table class="table table-bordered table-hover" id="tableRelease">
													<thead>
														<tr>
															<th style="text-align:center;">Purity</th>
															<th style="text-align:center;">Rate</th>
															<th style="text-align:center;">Gross Amount</th>
															<th style="text-align:center;">Net Amount Payable (2%)</th>
															<th style="text-align:center;">Net Amount Payable (3%)</th>
														</tr>
													</thead>
													<tbody style="text-align:center;background-color:#F5F5F5">
														<tr id="qRelease91">
															<td> 91% </td>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
														</tr>
													</tbody>
												</table>
												<div class="col-xs-12" style="padding: 0px;">
													<textarea  style="resize:none;height:100%;width:100%;margin:0px;border:1px solid #d9d9d9;" placeholder="Release Place"></textarea>
												</div>
											</div>
										</div>
										<div class="col-xs-12" align="right">
											<button type="button" class="btn btn-success" style="margin-top:15px;" id="sendReleaseQuotButton"><i class="fa fa-camera" style="color:#ffcf40"></i> Save Quotation</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div style="clear:both"></div>
</div>


<div id="wrapper">
	<div class="row content">
		
		<!--<div class="col-lg-2">
			<div class="hpanel">
			<div class="panel-body">
			<div class="text-center">
			<h3 class="text-success">New Customer</h3>
			
			<?php
				if ($miscData['addDate'] == 'Yes') {
				?>
				<div class="clearfix" style="height: 19px;"></div>
				<button title="Click To Add New Customer" data-toggle="modal" data-target="#addCustomerModal" class='btn' style="background-color:#123C69;padding:5px 8px 5px 8px;box-shadow:inset 0 -0.6em 0 -0.35em rgba(0,0,0,0.17);">
				<i style="color:#ffa500" class="pe-7s-add-user fa-2x"></i>
				</button>
				<?php }else{ ?>
				<div class="clearfix" style="height: 20px;"></div>
				<i style="color:#990000" class="pe-7s-add-user fa-3x"></i>
			<?php } ?>
			</div>
			</div>
			</div>
		</div>-->
		
		<div class="col-lg-2">
			<div class="hpanel">
				<?php if ($miscData['addDate'] == 'Yes') { ?>
					<div class="panel-body" style="height:50px; padding: 5px; background-color: #BEE890;">
						<ul class="mailbox-list">
							<li>
								<a title="Click To Add New Customer" data-toggle="modal" data-target="#addCustomerModal">
									<span class="pull-right"><i class="pe-7s-add-user fa" style="color:#990000; font-size: 18px; font-weight: 600;"></i> </span>
									Add Customer
								</a>
							</li>
						</ul>
					</div>
					<?php }else{ ?>
					<div class="panel-body" style="height:50px; padding: 5px;">
						<ul class="mailbox-list">
							<li>
								<a>
									<span class="pull-right"><i class="pe-7s-add-user fa" style="color:#990000; font-size: 18px; font-weight: 600;"></i> </span>
									Add Customer
								</a>
							</li>
						</ul>
					</div>
				<?php } ?>
				<div class="panel-body" style="height:50px; margin-top: 20px; padding: 5px;">
					<ul class="mailbox-list">
						<li>
							<?php if ($branchData['meet'] == "") { ?>
								<a>
									<span class="pull-right"><i class="pe-7s-video fa" style="color:#990000; font-size: 18px; font-weight: 600;"></i> </span>
									Meet Link
								</a>
								<?php }else{ ?>
								<a href="<?php echo $branchData['meet']; ?>" target="_BLANK">
									<span class="pull-right"><i class="pe-7s-video fa" style="color:#990000; font-size: 18px; font-weight: 600;"></i> </span>
									Meet Link
								</a>
							<?php } ?>
							
						</li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="col-lg-3">
			<div class="hpanel">
				<div class="panel-body" style="height:120px">
					<div class="stats-title pull-left">
						<h3 class="text-success"><?php echo $branchCode; ?></h3>
					</div>
					<div class="stats-icon pull-right">
						<i style="color:#990000" class="pe-7s-culture fa-3x"></i>
					</div>
					<div class="m-t-xl" style="margin-top:50px">
						<p class="font-bold no-margins">
							<?php echo $branchData['branchName']; ?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="hpanel stats">
				<div class="panel-body" style="height:120px">
					<div class="stats-title pull-left">
						<h3 class="text-success">Business</h3>
					</div>
					<div class="stats-icon pull-right">
						<i style="color:#990000" class="pe-7s-users fa-3x"></i>
					</div>
					<div class="clearfix"></div>
					<div class="col-xs-3" style="padding:0px">
						<small class="stat-label font-extra-bold" style="color:#636464">WALKIN</small>
						<h4><?php echo $miscData['totalWalkin']; ?></h4>
					</div>
					<div class="col-xs-3" style="padding:0px">
						<small class="stat-label font-extra-bold" style="color:#636464">SOLD</small>
						<h4><?php echo $miscData['totalSold']; ?></h4>
					</div>
					<div class="col-xs-6" style="padding:0px">
						<small class="stat-label font-extra-bold" style="color:#636464">COG</small>
						<div id="getCOG" data-branch="<?php echo $_SESSION['branchCode']  ?>" style="padding-top: 5px; color: #990000; cursor: pointer; text-decoration: underline; font-style: italic;">
							Click Here
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--<div class="col-lg-4">-->
		<!--	<div class="hpanel stats">-->
		<!--		<div class="panel-body" style="height:120px">-->
		<!--			<div class="stats-title pull-left">-->
		<!--				<h3 class="text-success">Rate</h3>-->
		<!--			</div>-->
		<!--			<div class="stats-icon pull-right">-->
		<!--				<i style="color:#990000" class="pe-7s-cash fa-3x"></i>-->
		<!--			</div>-->
		<!--			<div class="clearfix"></div>-->
		<!--			<div class="col-xs-4" style="padding:0px">-->
		<!--				<small class="stat-label font-extra-bold" style="color:#636464">CASH</small>-->
		<!--				<h4><?php // echo $goldRate['cash']; ?></h4>-->
		<!--			</div>-->
		<!--			<div class="col-xs-4" style="padding:0px">-->
		<!--				<small class="stat-label font-extra-bold" style="color:#636464">IMPS</small>-->
		<!--				<h4><?php // echo $goldRate['transferRate']; ?></h4>-->
		<!--			</div>-->
		<!--			<div class="col-xs-4" style="padding:0px">-->
		<!--				<small class="stat-label font-extra-bold" style="color:#636464">SILVER</small>-->
		<!--				<h4><?php // echo $silverRate['cash']; ?></h4>-->
		<!--			</div>-->
		<!--		</div>-->
		<!--	</div>-->
		<!--</div>-->
		
		<?php if($miscData['ClosingDate'] == $date){ ?>
			<div class="col-lg-12">
				<div class="hpanel">
					<div class="panel-body">
						<h3 class='text-center' style='margin-bottom: 10px; color:#990000'>BRANCH IS CLOSED, TO REOPEN & BILL - CALL APPROVAL TEAM : 8925537846</h3>
					</div>
				</div>
			</div>
		<?php } ?>
		
		<!--   ENQUIRY / PROCEED TO BUSINESS   -->
		<div class="col-lg-12">
			<div class="hpanel">
				<div class="panel-body">
					<div class="table-responsive project-list">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Customer</th>
									<th>Mobile</th>
									<th style='text-align:center;' colspan="2">Quotation</th>
									<th style='text-align:center;'>Action</th>
									<th style='text-align:center;'>Info</th>
									<th class='text-center'></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$query = mysqli_query($con,"SELECT Id,customer,contact,quotation,status,time,extra
									FROM everycustomer
									WHERE date='$date' AND branch='$branchCode' AND status IN ('Begin', '0', 'Blocked')
									ORDER BY Id ASC");
									
									while($row = mysqli_fetch_assoc($query)){
										
										if($row['extra'] != ''){
											$extra = json_decode($row['extra'],true);
											$bill_type = $extra['Pledge'];
											$bills = $extra['bills'];											
										}
										else{
											$bill_type = "no";
											$bills = 0;
										}
										
										$contact = $row['contact'];
										$walkin_data = mysqli_fetch_Assoc(mysqli_query($con, "SELECT count(*) AS walkin FROM walkin WHERE mobile='$contact'"));
										
										//CHECK PHONE NUMBER
										$checkQ1 = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS countT FROM fraud WHERE phone='$contact'"));
										if ($checkQ1['countT'] > 0){
											echo "<tr>";
											echo "<td class='text-danger'>" . $row['customer'] . "</td>";
											echo "<td class='text-danger'>" . $row['contact'] . "</td>";
											echo "<td style='text-align:center' colspan='5'><button class='btn btn-danger'> CALL ZONAL ( THIEF / FRAUD )</button></td>";
											echo "</tr>";
										}
										else{
											echo "<tr>";
											echo "<td>".$row['customer']."</td>";
											echo "<td>".$row['contact']."</td>";
											
											// QUOTATION BUTTON
											echo "<td style='text-align:right'><a class='btn btn-success btn-user' data-toggle='modal' data-target='#quotationModal' data-rowid='".$row['Id']."' data-rowname='".$row['customer']."'><i class='fa fa-inr' style='font-size:15px;color:#ffa500'></i></a></td>";
											
											// VIEW QUOTATION IMAGE
											if($row['quotation']==''){
												echo "<td></td>";
											}
											else{
												$decoded = json_decode($row['quotation'],true);
												echo "<td><a target='_BLANK' href='QuotationImage/".$decoded['image']."'><button class='btn btn-circle' type='button'><i class='fa fa-file-image-o' style='font-size:18px; font-weight:600; color:#123C69' ></i></button></a></td>";
											}
											
											// ENQUIRY
											if($row['quotation']==''){
												echo "<td style='text-align:center'>
												<button disabled class='btn btn-success btn-user tooltip' style='margin-right:25px'>
												<i class='fa fa-comments' style='color:#ffa500'></i>
												<span class='tooltiptext'>Quotation Not Given</span>
												<b> ENQUIRY</b>
												</button>";
											}
											else{
												echo "<td style='text-align:center'><a href='xbranchEnquiry.php?id=".$row['Id']."' class='btn btn-success btn-user' style='margin-right:25px'><i class='fa fa-comments' style='color:#ffa500'></i><b> ENQUIRY</b></a>";
											}
											
											// BILLING
											if($row['status'] == "Blocked"){
												// BLOCK THE CUSTOMER FROM BILLING UPON BRANCH VISIT AGAIN WITH PREVIOUSLY BILLED IN LESS THAN 20DAYS
												echo "<button class='btn btn-danger'><span style='color:#ffcf40' class='fa fa-phone'></span>  CALL ZONALS TO PROCEED </button></td>";
											}
											else{
												if($miscData['ClosingDate'] == $date){
													echo "<a class='btn btn-success btn-user' disabled><i class='fa fa-arrow-right' style='color:#ffa500'></i><b>BILLING</b></a></td>";
												}
												else{
													if($row['status'] == 'Begin'){
														if($bill_type == "yes"){
															echo "<button class='btn btn-success btn-user' style='background-color: #c7254e; border-color: #c7254e;' disabled><i class='fa fa-arrow-right'style='color:#ffa500'></i><b> PLEDGE</b></button></td>";
														}
														else{														
															echo "<button disabled class='btn btn-success btn-user tooltipBilling'>
															<i class='fa fa-comments' style='color:#ffa500'></i>
															<span class='tooltiptextBilling'>Not Verified<br>By VM</span>
															<b> Billing</b>
															</button></td>";
														}
													}
													else if($row['status'] == '0'){
														if($bill_type == "yes"){
															echo "<a href='addPledgeData.php?id=".$row['Id']."' class='btn btn-success btn-user' style='background-color: #c7254e; border-color: #c7254e;'><i class='fa fa-arrow-right'style='color:#ffa500'></i><b> PLEDGE</b></a></td>";
														}
														else{
															echo "<a href='xcheckCustomer.php?contact=".$row['contact']."&Id=".$row['Id']."&encTime=".$row['time']."' class='btn btn-success btn-user'><i class='fa fa-arrow-right' style='color:#ffa500'></i><b> BILLING</b></a></td>";
														}
													}												
												}
											}
											
											echo "<td style='text-align:center'><a target='_BLANK' class='btn btn-default' href='xeveryCustomerDetails.php?id=".$row['contact']."'> Bills : ".$bills."<br> Enquiry : ".$walkin_data['walkin']." </a></td>";
											
											echo "<td class='text-center'><button class='getRate' data-cashrate='".$goldRate['cash']."' data-impsrate='".$goldRate['transferRate']."' data-silverrate='".$silverRate['cash']."' >Rate</button></td>";
											
											echo "</tr>";
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
	<?php include("footer.php"); ?>
	<script type="text/javascript" src="scripts/html2canvas.min.js"></script>
	<script src="scripts/quotation-v2.js"></script>
	<script src="vendor/sweetalert/lib/sweet-alert.min.js"></script>
	<script>
		$(function () {
			$('.getRate').click(function(){
				const cashRate = this.dataset.cashrate;
				const impsRate = this.dataset.impsrate;
				const silverRate = this.dataset.silverrate;
				const text = "Cash Rate: "+cashRate+"\n IMPS Rate: "+impsRate+"\n Silver: "+silverRate;
				swal({
					title: "Today's Rate",
					text: text
				});
			});
		});
	</script>
	<script>
		(function () {
			
			const getCOG = document.getElementById("getCOG");
			getCOG.addEventListener("click", getCogData);

			async function getCogData(){
				getCOG.textContent = "Loading...";
				getCOG.removeEventListener("click", getCogData);

				const branchId = getCOG.dataset.branch;
				const response = await fetch("getBranchCOG.php?branch="+branchId);
				const result = await response.text();

				getCOG.textContent = "Click Here";
				getCOG.addEventListener("click", getCogData);

				swal({
					title: result+"%",
					text: "Your 30 Day COG"
				});
			}

		})();
	</script>
