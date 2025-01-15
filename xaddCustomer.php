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


						<div class="panel-body">

							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-12">
										<div class="hpanel">
											<ul class="nav nav-tabs">
												<li class="active"><a data-toggle="tab" href="#tab-1"> Adhaar EKYC</a></li>
												<li class=""><a data-toggle="tab" href="#tab-2">Varify Adhaar</a></li>
											</ul>
											<div class="tab-content">
												<div id="tab-1" class="tab-pane active">
													<div class="panel-body">

														<div class="row">
															<div class="col-md-6">
																<form id="requestOtpForm" onsubmit="requestOTP(event)">
																	<div class="form-group">
																		<label class="text-success">Customer Adhaar Number</label>
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
														<form id="aadharForm">
															<!-- Aadhaar Number -->
															<div class="form-group mb-3">
																<label for="aadharNumber" class="form-label">Aadhaar Number</label>
																<input type="text" id="aadharNumber" class="form-control" readonly>
															</div>

															<!-- Name -->
															<div class="form-group mb-3">
																<label for="name" class="form-label">Name</label>
																<input type="text" id="name" class="form-control" readonly>
															</div>

															<!-- Date of Birth -->
															<div class="form-group mb-3">
																<label for="dob" class="form-label">Date of Birth</label>
																<input type="text" id="dob" class="form-control" readonly>
															</div>

															<!-- Gender -->
															<div class="form-group mb-3">
																<label for="gender" class="form-label">Gender</label>
																<input type="text" id="gender" class="form-control" readonly>
															</div>

															<!-- Address -->
															<div class="form-group mb-3">
																<label for="address" class="form-label">Address</label>
																<textarea id="address" class="form-control" rows="3" readonly></textarea>
															</div>

															<!-- Locality -->
															<div class="form-group mb-3">
																<label for="locality" class="form-label">Locality</label>
																<input type="text" id="locality" class="form-control" readonly>
															</div>

															<!-- Landmark -->
															<div class="form-group mb-3">
																<label for="landmark" class="form-label">Landmark</label>
																<input type="text" id="landmark" class="form-control" readonly>
															</div>

															<!-- City -->
															<div class="form-group mb-3">
																<label for="city" class="form-label">City</label>
																<input type="text" id="city" class="form-control" readonly>
															</div>

															<!-- District -->
															<div class="form-group mb-3">
																<label for="district" class="form-label">District</label>
																<input type="text" id="district" class="form-control" readonly>
															</div>

															<!-- State -->
															<div class="form-group mb-3">
																<label for="state" class="form-label">State</label>
																<input type="text" id="state" class="form-control" readonly>
															</div>

															<!-- Pincode -->
															<div class="form-group mb-3">
																<label for="pincode" class="form-label">Pincode</label>
																<input type="text" id="pincode" class="form-control" readonly>
															</div>

															<!-- Aadhaar Image -->
															<div class="form-group mb-3">
																<label for="aadharImage" class="form-label">Aadhaar Image</label>
																<img id="aadharImage" alt="Aadhar Image" class="img-fluid rounded shadow" style="max-width: 100%; height: auto;">
															</div>

															<!-- Document Link -->
															<div class="form-group mb-3">
																<label for="documentLink" class="form-label">Document Link</label>
																<a id="documentLink" class="btn btn-outline-primary d-block text-center" target="_blank">No Document Available</a>
															</div>
														</form>

													</div>
												</div>
												<div id="tab-2" class="tab-pane">
													<div class="panel-body">
														<h1>Abhiram B S</h1>
													</div>
												</div>
											</div>


										</div>
									</div>
								</div>
							</div>


						</div>
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


				// working in clg

				/*
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

						const data = result.msg;

						// alert('OTP Submission: ' + JSON.stringify(result));
						console.log(JSON.stringify(result));
					} catch (error) {
						console.error('Error:', error);
						alert('Error submitting OTP');
					}
				}

				*/


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
						const response = await fetch('https://www.truthscreen.com/v1/apicall/nid/aadhar_submit_otp', { // Replace with your actual endpoint
							method: 'POST',
							headers: {
								'Content-Type': 'application/json'
							},
							body: JSON.stringify(requestData)
						});

						if (!response.ok) {
							throw new Error(`HTTP error! Status: ${response.status}`);
						}

						const result = await response.json();

						const data = result.msg; // Assuming response contains `msg` object with user details

						// Populate form fields
						document.getElementById('aadharNumber').value = data.aadharNumber || '';
						document.getElementById('name').value = data.name || '';
						document.getElementById('dob').value = data.dob || '';
						document.getElementById('gender').value = data.gender || '';
						document.getElementById('address').value = data.address || '';
						document.getElementById('locality').value = data.locality || '';
						document.getElementById('landmark').value = data.landmark || '';
						document.getElementById('city').value = data.city || '';
						document.getElementById('district').value = data.district || '';
						document.getElementById('state').value = data.state || '';
						document.getElementById('pincode').value = data.pincode || '';

						// Update Aadhaar Image
						const aadharImage = document.getElementById('aadharImage');
						aadharImage.src = data.aadharImage || '';
						aadharImage.alt = data.aadharImage ? 'Aadhar Image' : 'Image Not Available';

						// Update Document Link
						const documentLink = document.getElementById('documentLink');
						documentLink.href = data.documentLink || '#';
						documentLink.textContent = data.documentLink ? 'View Document' : 'No Document Available';
					} catch (error) {
						console.error('Error:', error);
						alert('Error submitting OTP: ' + error.message);
					}
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

				// CUSTOMER OTP AUTHENTICATION
				/* 				function generateOTP() {
					var data = $('#mobile').val();
					var name = $('#name').val();
					console.log(data);
					console.log(name);
					var req1 = $.ajax({
					url: "ot.php",
					type: "POST",
					data: {
					data: data,
					name: name
					},
					});
					req1.done(function(msg) {
					alert("OTP is sent to customer's mobile");
					});
					}							
					$(document).ready(function () {
					$('#submitCustomer').attr("disabled", false);
					$("#xotp").change(function () {
					var data = $('#xotp').val();
					var req = $.ajax({
					url: "otpValid.php",
					type: "POST",
					data: {
					data
					},
					});
					req.done(function (msg) {
					$("#xotp").val(msg);
					if (msg == "OTP Validated") {
					$('#xotp').attr('readonly', 'true'),
					$('#submitCustomer').attr("disabled", false);
					}
					else if (msg == "Invalid OTP") {
					alert(msg);
					}
					});
					});
				}); */


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
							// 			$.ajax({
							// 				url: "xTransactionAjax.php",
							// 				type: "post",
							// 				data: {
							// 					customerType: customerType,
							// 					proofNumber: this.value,
							// 					contactNo: contactNo
							// 				},
							// 				success: function(response) {
							// 					if (response == 'available') {
							// 						alert("THE CUSTOMER HAS BEEN BLOCKED FROM BILLING PLEASE CONTACT APPROVAL TEAM");
							// 						window.location.href = "xeveryCustomer.php";
							// 					}
							// 				}
							// 			});
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