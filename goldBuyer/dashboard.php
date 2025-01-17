<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

if (
	isset($_SESSION['buyer_mobile']) && $_SESSION['buyer_mobile'] != '' &&
	isset($_SESSION['buyer_name']) && $_SESSION['buyer_name'] != ''
) {

	include("header.php");

?>

	<main>
		<section class="contact-section">

			<h2 class="section-title" style="font-size: 20px;">Your rate & quotation</h2>

			<div id="submit-message"></div>

			<form class="contact-form" method="post" action="quot.php">
				<input type="hidden" name="quotContact" value="<?php echo $_SESSION['buyer_mobile']; ?>">
				<div class="form-group">
					<label for="quotRate" class="form-label">Rate</label>
					<input type="text" name="quotRate" class="form-control" id="quotRate" required placeholder="Eg: 5680">
					<div class="invalid-feedback">! Please, enter your Rate!</div>
				</div>
				<div class="form-group">
					<label for="quotQuantity" class="form-label">Quantity</label>
					<input type="text" name="quotQuantity" class="form-control" id="quotQuantity" required placeholder="kg / gms">
					<div class="invalid-feedback">! Please, enter Quantity!</div>
				</div>
				<div class="form-group">
					<label for="quotQuantity" class="form-label">Mode of Payment</label>
					<div class="radio"><label> <input type="radio" checked="" value="cod" id="optionsRadios1" name="payment"> Cash</label></div>
					<div class="radio"><label> <input type="radio" value="account" id="optionsRadios2" name="payment"> Account
						</label></div>

				</div>
				<button type="submit" name="quotSubmit" class="btn btn-primary form-submit-btn">Submit</button>
			</form>

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
<script src="public/js/quot.js"></script>