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
			Login</button>
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
				<h2 class="section-title">Login</h2>
				<p class="mb-4">Enter your mobile number & password to login</p>

				<div id="error-message"></div>

				<form class="contact-form" action="login.php" method="post">
					<div class="form-group">
						<label for="loginMobile" class="form-label">Mobile number</label>
						<div class="input-group has-validation">
							<input type="text" name="loginMobile" class="form-control" id="loginMobile" placeholder="Mobile number (10 digit)" maxlength="10" required autocomplete="off">
							<div class="invalid-feedback">! Please, Enter valid 10 digit mobile number</div>
						</div>
					</div>
					<div class="form-group">
						<label for="loginPassword" class="form-label">Password</label>
						<div class="input-icon-container">
							<input type="password" name="loginPassword" class="form-control" id="loginPassword" required autocomplete="off">
							<i id="togglePassword" class="fa fa-eye-slash"></i>
						</div>
					</div>

					<button type="submit" class="btn btn-primary form-submit-btn" name="loginBuyer">Login</button>
				</form>

			</section>

			<section class="achievements-section">
				<h6>Don't have account? <a href="register.php">Signup</a></h6>
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
	<script src="public/js/login.js"></script>
	<script src="public/js/passwordeye.js"></script>
</body>

</html>