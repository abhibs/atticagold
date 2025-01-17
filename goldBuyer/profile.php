<?php

session_start();
date_default_timezone_set("Asia/Kolkata");

if (
	isset($_SESSION['buyer_mobile']) && $_SESSION['buyer_mobile'] != '' &&
	isset($_SESSION['buyer_name']) && $_SESSION['buyer_name'] != ''
) {

	include("header.php");
	include("dbConnection.php");

	$profile = $con->prepare("SELECT * FROM buyer_profile WHERE contact = :uid");
	$profile->execute([":uid" => $_SESSION['buyer_mobile']]);
	$profile_data = $profile->fetch();

?>


	<main>
		<section class="intro-section">

			<h6 class="section-subtitle">DETAILS</h6>
			<h2 class="section-title" style="font-size: 20px;">Personal Details</h2>

			<dl class="row">
				<dt class="col-sm-3">Name</dt>
				<dd class="col-sm-9"><?php echo $profile_data['name']; ?></dd>

				<dt class="col-sm-3">Contact</dt>
				<dd class="col-sm-9"><?php echo $profile_data['contact']; ?></dd>

				<dt class="col-sm-3">Company</dt>
				<dd class="col-sm-9"><?php echo $profile_data['company']; ?></dd>

				<dt class="col-sm-3"><br></dt>
				<dd class="col-sm-9"><br></dd>

				<dt class="col-sm-3">Address</dt>
				<dd class="col-sm-9">
					<p><?php echo $profile_data['address_line1']; ?></p>
					<p><?php echo $profile_data['address_line2']; ?></p>
					<p><?php echo $profile_data['city']; ?></p>
					<p><?php echo $profile_data['state']; ?> - <?php echo $profile_data['zip']; ?></p>
				</dd>

				<dt class="col-sm-3">Country</dt>
				<dd class="col-sm-9"><?php echo $profile_data['country']; ?></dd>

			</dl>

		</section>

		<footer><a href="https://www.atticagoldcompany.com" target="_blank" rel="noopener noreferrer">Attica Gold Pvt ltd</a>. ISO 9001:2015 Certified Company</footer>
	</main>
<?php
	include("footer.php");
} else {
	header('Location: index.php');
	die();
}
?>