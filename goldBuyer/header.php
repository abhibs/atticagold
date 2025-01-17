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
	</style>
</head>

<body>
	<header style="background-color: transparent;">
		<button class="btn btn-white btn-share ml-auto mr-3 ml-md-0 mr-md-auto"><img src="assets/images/share.svg" alt="share" class="btn-img">
			Hello, <?php echo $_SESSION['buyer_name']; ?>!</button>
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
						<a href="dashboard.php" class="nav-link">Dashboard</a>
						<!-- <a href="submission.php" class="nav-link">Submission</a> -->
						<a href="profile.php" class="nav-link">Profile</a>
						<a href="logout.php" class="nav-link">Logout</a>
					</div>
				</div>
			</div>

		</aside>