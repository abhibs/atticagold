<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
$type = $_SESSION['usertype'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	include("./Config/AuthScreen/sendRequest.php");

	$json_data = file_get_contents('php://input');
	$request_data = json_decode($json_data, true);

	if ($request_data['type'] == "Aadhaar-paperless-EKYC" && $request_data['step'] == "1") {
		$url = "https://www.truthscreen.com/v1/apicall/nid/aadhar_get_otp";
		$body = [
			"aadharNo" => $request_data['aadhaarNumber'],
			"transId" => "12345",
			"docType" => 211,
		];
		$decrypted = sendRequest($url, $body);

		echo json_encode($decrypted);
		exit;
	}

	if ($request_data['type'] == "Aadhaar-paperless-EKYC" && $request_data['step'] == "2") {
		$url = "https://www.truthscreen.com/v1/apicall/nid/aadhar_submit_otp";
		$body = [
			"transId" => $request_data['tsTransId'],
			"otp" => (int)$request_data['otp']
		];
		$decrypted = sendRequest($url, $body);

		echo json_encode($decrypted);
		exit;
	}
}

if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['encData']) && !empty($_GET['encData']) && $type == 'Branch') {
	$id = $_GET['id'];
	include("dbConnection.php");
	$custDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT e.customer,e.contact,e.image,b.branchName,e.block_counter,e.time 
		FROM everycustomer e,branch b 
		WHERE e.id='$id' AND e.branch=b.branchId"));
	if (base64_decode($_GET['encData']) == date("Y-m-d") . $custDetails['time']) {
		include("header.php");
		include("menu.php");

		//$branch = $_SESSION['branchCode'];
		$_SESSION['mobile'] = $custDetails['contact'];
		unset($_SESSION['bill']);



?>
		<style>
			#results img {
				width: 100px;
			}

			#wrapper {
				background: #E3E3E3;
			}

			#wrapper h3 {
				text-transform: uppercase;
				font-weight: 600;
				font-size: 18px;
				color: #123C69;
			}

			.form-control[disabled],
			.form-control[readonly],
			fieldset[disabled] .form-control {
				background-color: #fffafa;
			}

			.text-success {
				color: #123C69;
				text-transform: uppercase;
				font-weight: 700;
				font-size: 12px;
			}

			.fa_Icon {
				color: #800000;
			}

			.btn-success {
				display: inline-block;
				padding: 0.6em 1.4em;
				margin: 0 0.3em 0.3em 0;
				border-radius: 0.15em;
				box-sizing: border-box;
				text-decoration: none;
				font-size: 12px;
				font-family: 'Roboto', sans-serif;
				text-transform: uppercase;
				color: #fffafa;
				background-color: #123C69;
				box-shadow: inset 0 -0.6em 0 -0.35em rgba(0, 0, 0, 0.17);
				text-align: center;
				position: relative;
			}

			.branchName {
				text-transform: uppercase;
				color: #900;
			}

			#wrapper .panel-body {
				box-shadow: 10px 15px 15px #999;
				border: 1px solid #edf2f9;
				border-radius: 7px;
				background-color: #f5f5f5;
				padding: 20px;
				margin-top: 20px;
			}

			.font-weight-bold {
				font-weight: bold;
			}

			dt {
				margin-bottom: 25px;
			}

			dt {
				margin-bottom: 25px;
			}
		</style>
		<div id="wrapper">
			<div class="row content">
				<div class="col-lg-12">
					<div class="hpanel">
						<div class="col-xs-8">
							<h3> &nbsp; <span class="fa_Icon fa fa-user"></span> &nbsp; NEW CUSTOMER </h3>
						</div>
						<div class="col-xs-4 input-group">
							<span class="input-group-addon"><span class="fa_Icon fa fa-bank"></span></span>
							<input type="text" class="form-control branchName text-center font-weight-bold" readonly value="<?php echo $custDetails['branchName']; ?>">
						</div>


						<!-- <div class="panel-body"> -->

							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-12">
										<div class="hpanel">
											<ul class="nav nav-tabs">
												<li class="active"><a data-toggle="tab" href="#tab-1"> Aadhar eKYC</a></li>
												<li class=""><a data-toggle="tab" href="#tab-2">Manual</a></li>
											</ul>
											<div class="tab-content">
												<div id="tab-1" class="tab-pane active">
													<div class="panel-body">

														<div class="row">
															<div class="col-md-6">
																<form id="requestOtpForm" onsubmit="requestOTP(event)">
																	<div class="form-group">
																		<label class="text-success">Customer Aadhar Number</label>
																		<input type="text" required id="aadhaarNumber" name="aadhaarNumber" placeholder="Enter Customer Adhar Number" class="form-control" pattern="[0-9]{12}" maxlength="12" autocomplete="off">
																	</div>
																	<button class="btn btn-success" type="submit">
																		<span style="color:#ffcf40" class="fa fa-save"></span> Request OTP
																	</button>
																</form>
																<!-- <h5>Genereated Transaction ID : </h5> -->
															</div>
															<div class="col-md-6">
																<form id="submitOtpForm" onsubmit="submitOTP(event)">
																	<div class="form-group">
																		<label class="text-success">Generated Transaction ID</label>
																		<input type="text" id="transId" name="transId" required placeholder="Generated Transaction ID" class="form-control" autocomplete="off">
																	</div>
																	<div class="form-group">
																		<label class="text-success">Customer Mobile OTP</label>
																		<input type="text" required id="otp" name="otp" placeholder="Customer Mobile OTP" class="form-control" autocomplete="off">
																	</div>
																	<button class="btn btn-success" type="submit">
																		<span style="color:#ffcf40" class="fa fa-save"></span> Submit
																	</button>
																</form>
															</div>
														</div>
														<div id="otpSubmitOutput" class="output"></div>

													</div>
												</div>
												<div id="tab-2" class="tab-pane">
													<div class="panel-body">
														<form method="POST" action="xsubmit.php" enctype="multipart/form-data" autocomplete="off">
															<input type="hidden" name="cusId" value="<?php $custId = "ATTICA-" . rand(10000, 99999);
																										$_SESSION['customerID'] = $custId;
																										echo $custId; ?>">

															<!-- ---------------    PERSONAL DATA   ----------------- -->
															<div class="form-group col-xs-3" style="padding-right:50px">
																<label class="text-success">Customer Photo</label>
																<div id="results" style="position:absolute;"></div>
																<a onClick="take_snapshot()">
																	<div id="my_camera"></div>
																	<i style="position:absolute; top:40%; left:20%; font-size:15px; font-weight:900; color:#900">CLICK HERE</i>
																</a>
																<input type="text" name="image" class="image-tag" required style="opacity:0; width:0; float:left;"><br>
															</div>
															<div class="col-xs-9">
																<div class="form-group col-sm-4">
																	<label class="text-success">Contact Number</label>
																	<input type="text" name="mobile" id="mobile" placeholder="Contact Number" maxlength="10" class="form-control" value="<?php echo $custDetails['contact']; ?>" readonly>
																</div>
																<div class="form-group col-sm-8">
																	<label class="text-success">Customer Name</label>
																	<input type="text" name="name" required id="name" class="form-control" autocomplete="off" placeholder="Customer Name">
																</div>
																<div class="form-group col-sm-2">
																	<label class="text-success">DOB : Day</label>
																	<select class="form-control" name="day" id="day" required>
																		<option selected="true" disabled="disabled" value="">DD</option>
																		<?php for ($i = 1; $i <= 31; $i++) {
																			echo "<option value=" . $i . ">" . $i . "</option>";
																		} ?>
																	</select>
																</div>
																<div class="form-group col-sm-2">
																	<label class="text-success">Month</label>
																	<select class="form-control" name="month" id="month" required>
																		<option selected="true" disabled="disabled" value="">MM</option>
																		<option value="1">January</option>
																		<option value="2">February </option>
																		<option value="3">March</option>
																		<option value="4">April</option>
																		<option value="5">May</option>
																		<option value="6">June</option>
																		<option value="7">July</option>
																		<option value="8">August</option>
																		<option value="9">September</option>
																		<option value="10">October</option>
																		<option value="11">November</option>
																		<option value="12">December</option>
																	</select>
																</div>
																<div class="form-group col-sm-2">
																	<label class="text-success">Year</label>
																	<select class="form-control" name="year" id="year" required>
																		<option selected="true" disabled="disabled" value="">YYYY</option>
																		<?php for ($i = 2021; $i >= 1900; $i--) {
																			echo "<option value=" . $i . ">" . $i . "</option>";
																		} ?>
																	</select>
																</div>
																<div class="form-group col-sm-6" style="line-height:25px" align="center">
																	<label class="text-success">Customer Gender</label><br>
																	<b style="color:#990000">
																		<input name="gender" value="Male" class="i-checks" type="radio" required> MALE
																		<input name="gender" value="Female" class="i-checks" type="radio"> FEMALE
																		<input name="gender" value="Others" class="i-checks" type="radio"> OTHERS
																	</b>
																</div>
																<label class="col-sm-12 control-label"></label>
																<div class="form-group col-sm-4">
																	<label class="text-success">Additional Contacts</label>
																	<select class="form-control" name="relation" required id="addContact">
																		<option selected="true" disabled="disabled" value="">ADDITIONAL CONTACT</option>
																		<option value="Father/Mother">Father/Mother</option>
																		<option value="Husband/Wife">Husband/Wife </option>
																		<option value="Brother/Sister">Brother/Sister</option>
																		<option value="LegalGaurdian">Legal Gaurdian</option>
																	</select>
																</div>
																<div class="form-group col-sm-4">
																	<label class="text-success">Contact</label>
																	<input type="text" name="rcontact" required id="rContact" placeholder="Contact" class="form-control" pattern="[0-9]{10}" maxlength="10" autocomplete="off">
																</div>
																<!--<div class="col-sm-2" style="padding-top:22px">
										<a onClick="generateOTP()" class="btn btn-success btn-block"><i class="fa_icon fa fa-paper-plane"></i> Send OTP</a>
										</div>
										<div class="col-sm-2" style="padding-top:22px">
										<input type="text" placeholder="Enter OTP" class="form-control" maxlength="6" required name="otp" id="xotp" autocomplete="off">
									</div>-->
															</div>
															<!-- ---------------    END OF PERSONAL DATA   ----------------- -->

															<!-- ---------------    CURRENT ADDRESS   ----------------- -->
															<label class="col-sm-12 control-label">
																<h3 class="text-success">
																	<hr><i style="color:#900" class="fa fa-map-marker"></i> Current Address
																</h3>
															</label>
															<div class="form-group col-sm-4">
																<label class="text-success">Address Line</label>
																<input type="text" name="caline" required class="form-control" autocomplete="off" placeholder="Address">
															</div>
															<div class="form-group col-sm-4">
																<label class="text-success">Area / Locality</label>
																<input type="text" name="clocality" required class="form-control" autocomplete="off" placeholder="Area">
															</div>
															<div class="form-group col-sm-4">
																<label class="text-success">Landmark</label>
																<input type="text" name="cland" required class="form-control" autocomplete="off" placeholder="Landmark">
															</div>
															<div class="form-group col-sm-4">
																<label class="text-success">State</label>
																<select name="cstate" id="state" class="form-control">
																	<option>Select State</option>
																</select>
															</div>
															<div class="form-group col-sm-4">
																<label class="text-success">City</label>
																<select name="ccity" id="city" class="form-control"></select>
															</div>
															<div class="form-group col-sm-4">
																<label class="text-success">Pincode</label>
																<input type="text" name="cpin" required class="form-control" autocomplete="off" placeholder="Pincode">
															</div>
															<!-- ---------------   END OF CURRENT ADDRESS   ----------------- -->

															<!-- ---------------    DOCUMENTS   ----------------- -->
															<label class="col-sm-12 control-label">
																<hr>
															</label>
															<div class="col-sm-3">
																<label class="text-success">ID Proof</label>
																<select class="form-control" name="idProof" id="idProof" required>
																	<option selected="true" disabled="disabled" value="">ID PROOF</option>
																	<option value="Voter Id">Voter Id</option>
																	<option value="Aadhar Card">Aadhar Card</option>
																	<option value="Ration Card">Ration Card</option>
																	<option value="Pan Card">Pan Card</option>
																	<option value="Passport">Passport</option>
																	<option value="Driving License">Driving License</option>
																	<option value="Others">Others</option>
																</select>
																<input type="text" class="form-control" style="padding:5px 10px;background:#e6e6e6;" name="idProofNum" id="idProofNum" pattern="^[a-zA-Z0-9]+$" minlength="9" placeholder=" * Proof Number" required autocomplete="off">
																<span class="font-weight-bold text-danger">Please avoid space or any special characters like (+!@#$%^&*{}?/-)</span>
															</div>
															<div class="col-sm-3">
																<label class="text-success">Address Proof</label>
																<select class="form-control" name="addProof" style="padding:0px 2px" id="addProof" required>
																	<option selected="true" disabled="disabled" value="">ADDRESS PROOF</option>
																	<option value="Voter Id">Voter Id</option>
																	<option value="Aadhar Card">Aadhar Card</option>
																	<option value="Ration Card">Ration Card</option>
																	<option value="Passport">Passport</option>
																	<option value="Driving License">Driving License</option>
																	<option value="Rental Agreement">Rental Agreement</option>
																	<option value="Others">Others</option>
																</select>
																<input type="text" class="form-control" style="padding:5px 10px;background:#e6e6e6;" name="addProofNum" id="addProofNum" pattern="^[a-zA-Z0-9]+$" minlength="9" placeholder=" * Proof Number" required autocomplete="off">
																<span class="font-weight-bold text-danger">Please avoid space or any special characters like (+!@#$%^&*{}?/-)</span>
															</div>
															<div class="col-sm-3">
																<label class="text-success">Type Of Transaction</label>
																<select class="form-control m-b" name="typeGold" id="typeGold" required>
																	<option selected="true" disabled="disabled" value="">TYPE</option>
																	<option value="physical">Physical </option>
																	<option value="release">Release </option>
																</select>
															</div>
															<div class="col-sm-2" align="right" style="padding-top:22px">
																<input type="hidden" name="block_counter" id="block_counter" value="<?php echo $custDetails['block_counter']; ?>">
																<button class="btn btn-success" name="submitManualCustomer" id="submitCustomer1" type="submit">
																	<span style="color:#ffcf40" class="fa fa-save"></span> Submit
																</button>
															</div>
															<!-- ---------------    END OF DOCUMENTS   ----------------- -->
														</form>
													</div>
												</div>
											</div>


										</div>
									</div>
								</div>
							</div>


						<!-- </div> -->
					</div>
				</div>
			</div>
			<div style="clear:both"></div>
			<?php include("footer.php"); ?>
			<script src="scripts/webcam.min.js"></script>
			<script src="scripts/states.js"></script>
			<script>
				async function requestOTP(event) {
					event.preventDefault();

					const aadhaarNumber = document.getElementById('aadhaarNumber').value;
					const requestData = {
						type: "Aadhaar-paperless-EKYC",
						step: "1",
						aadhaarNumber: aadhaarNumber
					};

					try {
						const response = await fetch('', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json'
							},
							body: JSON.stringify(requestData)
						});
						const result = await response.json();
						alert('OTP Request: ' + JSON.stringify(result));


					} catch (error) {
						console.error('Error:', error);
						alert('Error requesting OTP');
					}
				}

				async function submitOTP(event) {
					event.preventDefault();

					const transId = document.getElementById('transId').value;
					const otp = document.getElementById('otp').value;
					const requestData = {
						type: "Aadhaar-paperless-EKYC",
						step: "2",
						tsTransId: transId,
						otp: otp
					};

					try {
						const response = await fetch('', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json'
							},
							body: JSON.stringify(requestData)
						});
						const result = await response.json();
						displayOutput("", result, 'otpSubmitOutput');
					} catch (error) {
						displayOutput("Error", {
							message: "Error submitting OTP",
							details: error.message
						}, 'otpSubmitOutput');
					}
				}

				function displayOutput(title, data, outputId) {
					const outputDiv = document.getElementById(outputId);
					outputDiv.innerHTML = `
                <h2>${title}</h2>
                ${jsonToForm(data, outputId)}
            `;
				}

				function jsonToForm(json, outputId) {
					let form = "<form class='otpSubmitForm' action='xsubmit.php' method='post' enctype='multipart/form-data'>";

					// Function to recursively handle nested objects
					function processObject(data, prefix = '') {
						Object.keys(data).forEach(key => {
							let fieldKey = key.replace(/\s+/g, '-'); // Replace spaces with dashes
							if (typeof data[key] === 'object' && data[key] !== null) {
								form += `<fieldset><legend>${fieldKey}</legend>`;
								processObject(data[key], fieldKey); // Recursively process the nested object
								form += `</fieldset>`;
							} else {
								if (key === 'Image') { // Check if the key is 'Image'
									form += `
									<div class="col-md-12">
                                <label class="text-success" for="${fieldKey}">Aadhaar Image</label>
                                <img src="${data[key]}" alt="Aadhaar Image" class="image-fluid">
								</div>
                            `;
								} else {
									form += `
									
									<div class="col-md-12">
									<div class="form-group">
                                <label class="text-success" for="${fieldKey}">${key}</label>
                                <input type="text" id="${fieldKey}" name="${fieldKey}" class="form-control" value="${data[key]}" readonly> 
									</div>
									</div>
								`;

								}
							}
						});
					}

					processObject(json); // Start the recursive process

					form += `

<div class="form-group col-md-4" style="padding-right:50px">
									<label class="text-success">Customer Photo</label>
									<div id="results" style="position:absolute;"></div>
									<a onClick="take_snapshot()">
										<div id="my_camera"></div>
										<i style="position:absolute; top:40%; left:20%; font-size:15px; font-weight:900; color:#900">CLICK HERE</i>
									</a>
									<input type="text" name="image" class="image-tag" required style="opacity:0; width:0; float:left;"><br>
								</div>
<div class="form-group col-sm-4">

<label class="text-success">Contact Number</label>

<input type="text" name="mobile" id="mobile" placeholder="Contact Number" maxlength="10" class="form-control" value="<?php echo $custDetails['contact']; ?>" readonly>

</div>

											<div class="form-group col-md-4">
										<label class="text-success">Additional Contacts</label>
										<select class="form-control" name="relation" required id="addContact">
											<option selected="true" disabled="disabled" value="">ADDITIONAL CONTACT</option>
											<option value="Father/Mother">Father/Mother</option>
											<option value="Husband/Wife">Husband/Wife </option>
											<option value="Brother/Sister">Brother/Sister</option>
											<option value="LegalGaurdian">Legal Gaurdian</option>
										</select>
									</div>
<div class="form-group col-md-4">
										<label class="text-success">Additional Contact Number</label>
										<input type="text" name="rcontact" required id="rContact" placeholder="Contact" class="form-control" pattern="[0-9]{10}" maxlength="10" autocomplete="off">
									</div>
																	<div class="form-group col-md-4">
									<label class="text-success">Landmark</label>
									<input type="text" name="cland" required class="form-control" autocomplete="off" placeholder="Landmark">
								</div>

																									<div class="form-group col-md-4">
									<label class="text-success">Aadhar Number</label>
									<input type="text" name="idProofNum" required class="form-control" autocomplete="off" placeholder="Aadhar Number">
								</div>
								<div class="col-md-4">
									<label class="text-success">Type Of Transaction</label>
									<select class="form-control m-b" name="typeGold" id="typeGold" required>
										<option selected="true" disabled="disabled" value="">TYPE</option>
										<option value="physical">Physical </option>
										<option value="release">Release </option>
									</select>
								</div>
        `;
					form += `
					<div class="row">
					<div class="col">
    					<button class="btn btn-success" type="submit" name="submitCustomer">
							<span style="color:#ffcf40" class="fa fa-save"></span>Add Customer
						</button>
					</div>
					</div>
</form>`;
					return form;
				}
			</script>
			<script language="JavaScript">
				// WEBCAM RELATED 
				Webcam.set({
					width: 210,
					height: 160,
					image_format: 'jpeg',
					jpeg_quality: 100
				});
				Webcam.attach('#my_camera');

				function take_snapshot() {
					Webcam.snap(function(data_uri) {
						$(".image-tag").val(data_uri);
						document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
					});
				}



				function check_number(proofNo) {
					if (proofNo.match(/^(\d)\1+$/g)) {
						return 1;
					} else {
						return 0;
					}
				}
				var customerType = "New";
				$('#idProofNum,#addProofNum').on('change', function() {
					var proofNo = $(this).val();
					res = check_number(proofNo);
					if (res == '1') {
						alert("PLEASE ENTER PROPER NUMBER");
					} else {
						var contactNo = $("#mobile").val();
						var block_counter = $("#block_counter").val();
						if (block_counter < 2) {

						}
					}
				});
			</script>

	<?php
	} else {
		header("Location: xeveryCustomer.php");
	}
} else {
	include("logout.php");
}
	?>