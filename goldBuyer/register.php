<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Attica Gold</title>
	<link rel="shortcut icon" type="image/png" href="../images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Mukta:300,400,500,600,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="assets/vendors/@fortawesome/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="assets/css/live-resume.css">
	<style>
		html,
		body {
			margin: 0;
			height: 100%;
			background-color: #760107;
		}

		main .contact-form .form-control {
			font-size: 12px;
		}

		.input-icon-container {
			display: flex;
			flex-direction: row;
			align-items: center;
			overflow: hidden;
		}

		.input-icon-container input {
			flex: auto 1 1;
		}

		.input-icon-container .fa {
			padding: 10px;
			background: #f3f3f3;
			color: grey;
		}
	</style>
</head>

<body>
	<header style="background-color: transparent;">
		<button class="btn btn-white btn-share ml-auto mr-3 ml-md-0 mr-md-auto"><img src="assets/images/share.svg" alt="share" class="btn-img">
			Register</button>
	</header>
	<div class="content-wrapper">
		<aside>
			<div class="profile-img-wrapper">
				<img src="./assets/images/logo.jpg" alt="profile">
			</div>
			<h1 class="profile-name" style="color: #E8C556;">Attica Gold Company</h1>

			<nav class="social-links">
				<a href="https://www.facebook.com/AtticaGoldCompany/" class="social-link"><i class="fab fa-facebook-f"></i></a>
				<a href="https://www.instagram.com/attica.goldcompany/" class="social-link"><i class="fab fa-instagram"></i></a>
				<a href="https://www.atticagoldcompany.com" class="social-link"><i class="fab fa-dribbble"></i></a>
			</nav>

			<div class="widget card">
				<div class="card-body">
					<div class="widget-content">
						<a href="index.php" class="nav-link">Login</a>
						<a href="register.php" class="nav-link">Register</a>
					</div>
				</div>
			</div>
		</aside>
		<main>
			<section class="contact-section">
				<h2 class="section-title">Create Account</h2>
				<p class="mb-4">Enter your personal details to create account</p>

				<div id="error-message"></div>

				<form class="contact-form" action="signup.php" method="POST">
					<div class="form-group">
						<label for="regName" class="form-label">Your name</label>
						<input type="text" name="regName" class="form-control" id="regName" placeholder="First and last name" required autocomplete="off">
						<div class="invalid-feedback">! Please, enter your name!</div>
					</div>
					<div class="form-group">
						<label for="regMobile" class="form-label">Mobile number</label>
						<div class="input-group mb-3">
							<span class="input-group-text" id="basic-addon1">+91</span>
							<input type="text" name="regMobile" class="form-control" pattern="^\d{10}$" title="Please enter a valid 10-digit mobile number">
							<div class="invalid-feedback" id="mobile-number-feedback">! Please, Enter valid 10 digit mobile number</div>
						</div>
					</div>
					<div class="form-group">
						<label for="regCompany" class="form-label">Company name</label>
						<input type="text" name="regCompany" class="form-control" id="regCompany" required autocomplete="off">
						<div class="invalid-feedback">! Please, enter you company name!</div>
					</div>

					<div class="form-group form-group-name">
						<label for="regAddress1" class="form-label">Address line 1</label>
						<input type="text" name="regAddress1" class="form-control" id="regAddress1" placeholder="1234 Main St" required autocomplete="off">
						<div class="invalid-feedback">! Please, enter Address!</div>
					</div>
					<div class="form-group form-group-name">
						<label for="regAddress2" class="form-label">Address line 2</label>
						<input type="text" name="regAddress2" class="form-control" id="regAddress2" placeholder="Apartment, studio, or floor" required autocomplete="off">
						<div class="invalid-feedback">! Please, enter Address!</div>
					</div>
					<div class="form-group form-group-name">
						<label for="regCity" class="form-label">City</label>
						<input type="text" name="regCity" class="form-control" id="regCity" required autocomplete="off">
						<div class="invalid-feedback">! Please, enter your city!</div>
					</div>
					<div class="form-group form-group-name">
						<label for="regState" class="form-label">State</label>
						<input type="text" name="regState" class="form-control" id="regState" required autocomplete="off">
						<div class="invalid-feedback">! Please, enter your state!</div>
					</div>
					<div class="form-group form-group-name">
						<label for="regCountry" class="form-label">Country</label>
						<input type="text" name="regCountry" class="form-control" id="regCountry" required autocomplete="off">
						<div class="invalid-feedback">! Please, enter your country!</div>
					</div>
					<div class="form-group form-group-name">
						<label for="regZip" class="form-label">Zip</label>
						<input type="text" name="regZip" class="form-control" id="regZip" placeholder="123456" required autocomplete="off" pattern="^\d{6}$" title="Please enter a valid 6-digit pincode">
						<div class="invalid-feedback">! Please, enter your pincode!</div>
					</div>


					<div class="form-group">
						<label for="regPassword" class="form-label">Password</label>
						<div class="input-icon-container">
							<input type="password" name="regPassword" class="form-control" id="regPassword" required autocomplete="off">
							<i id="togglePassword" class="fa fa-eye-slash"></i>
						</div>
					</div>
					<div class="form-group" style="margin-bottom: 0px;">
						<p style="margin-bottom: 0px;">Verify your Mobile Number through OTP</p><br>
					</div> 
					<div class="form-group form-group-name">
						<button type="button" class="btn btn-primary form-submit-btn w-100" id="regOTPButton" style="margin-top: 12px;">Send OTP</button>
						<br><br>
						<button type="submit" class="btn btn-primary form-submit-btn" name="registerBuyer">Register</button>
					</div>
					<div class="form-group form-group-name">
						<input type="text" name="regOTP" class="form-control  mt-3" id="regOTP" maxlength="6" placeholder="OTP" required>
					</div>

					<div class="d-grid gap-2 mt-3" id="submit-div"></div>
				</form>

			</section>

			<section class="achievements-section">
				<h6>Already have an account? <a href="index.php">Login</h6>
			</section>

			<footer><a href="https://www.atticagoldcompany.com" target="_blank" rel="noopener noreferrer">Attica Gold Pvt ltd</a>. ISO 9001:2015 Certified Company</footer>
		</main>
	</div>
	<script src="assets/vendors/jquery/dist/jquery.min.js"></script>
	<script src="assets/vendors/@popperjs/core/dist/umd/popper-base.min.js"></script>
	<script src="assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="assets/vendors/entry/jq.entry.min.js"></script>
	<script src="assets/js/live-resume.js"></script>
	<script src="public/js/main.js"></script>
	<script src="public/js/signup.js"></script>
	<script src="public/js/passwordeye.js"></script>
</body>

</html>