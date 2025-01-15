<?php
	session_start();
	$type = $_SESSION['usertype'];
	if ($type == 'Branch') {
		include("header.php");
		include("menu.php");
	} 
	else {
		include("logout.php");
	}
	include("dbConnection.php");
	$branchCode = $_SESSION['branchCode'];
	
	/*  GET THE DATA FROM URL  */
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$rowres = mysqli_fetch_assoc(mysqli_query($con, "SELECT branch, customer, contact, quotation, extra
		FROM everycustomer 
		WHERE id='$_GET[id]'"));
		
		$extra = json_decode($rowres['extra'], true);		
		$quot = json_decode($rowres['quotation'],true);
	}
?>
<style>
	#wrapper h3 {
	text-transform: uppercase;
	font-weight: 600;
	font-size: 16px;
	color: #123C69;
	}
	#wrapper .panel-body{
	box-shadow: 10px 15px 15px #999;
	border: 1px solid #edf2f9;
	background-color: #f5f5f5;
	border-radius:3px;	
	padding: 20px;
	}
	.text-success {
	color: #123C69;
	text-transform: uppercase;
	font-weight: 600;
	font-size: 12px;
	}
	.fa_Icon {
	color: #990000;
	}
	.btn-success {
	display: inline-block;
	padding: 0.7em 1.4em;
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
</style>
<div id="wrapper">
	<div class="row content">
		<div class="col-lg-12">
			<div class="hpanel">
				<div class="panel-heading">
					<h3 class="text-success"><i class="fa_Icon fa fa-users"> </i> Enquiry Customer </h3>
				</div>
				<div class="panel-body">
					<form method="POST" class="form-horizontal" action="xsubmit.php" onsubmit="submitRemarksButton.disabled = true; return true;">
						
						<input type="hidden" value="<?php echo $_GET['id']; ?>" class="form-control" name="id">
						<input type="hidden" value="<?php echo $rowres['branch']; ?>" class="form-control" name="branchid">	
						<input type="hidden" value="<?php echo $quot['image']; ?>" class="form-control" name="quotation">
						<input type="hidden" value="<?php echo $quot['rate']; ?>" class="form-control" name="givenRate">
						<input type="hidden" value="<?php echo $extra['bills']; ?>" class="form-control" name="bills">
						
						<div class="col-sm-3">
							<label class="text-success">Customer Name</label>
							<input type="text" name="cusname" class="form-control" value="<?php echo $rowres['customer']; ?>">
						</div>
						<div class="col-sm-3">
							<label class="text-success">Contact Number</label>
							<input type="text" name="cusmob" style="padding:0px 5px" readonly class="form-control" value="<?php echo $rowres['contact']; ?>">
						</div>						
						<div class="col-sm-3">
							<label class="text-success">Gross Weight</label>
							<input type="text" name="gwt" placeholder="Gross Weight" class="form-control" autocomplete="off" required>
						</div>
						<div class="col-sm-3">
							<label class="text-success">Net Weight</label>
							<input type="text" name="nwt" placeholder="Net Weight" class="form-control" autocomplete="off" required>
						</div>					
						
						<label class="col-sm-12"><br></label>
						<div class="col-sm-3">
							<label class="text-success">Metal</label>
							<select name="metal" class="form-control" required>
								<option selected="true" disabled="disabled" value="">Metal</option>
								<option value="Gold">Gold</option>
								<option value="Silver">Silver</option>
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-success">Purity (%)</label>
							<input type="text" name="purity" placeholder="Purity" class="form-control" autocomplete="off" required>
						</div>
						<div class="col-sm-3">
							<label class="text-success">Type Of Transaction</label>
							<select class="form-control m-b" name="typeGold" id="typeGold" required>
								<option selected="true" disabled="disabled" value="">Type</option>
								<option value="physical">Physical Gold</option>
								<option value="release">Release Gold</option>
							</select>
						</div>
						<div class="col-sm-3" id="relAmountID">
							<label class="text-success">Release Amount</label>
							<input type="text" name="ramt" placeholder="Amount" class="form-control" autocomplete="off" id="releaseAmountInput">
						</div>
						<div class="col-sm-3" id="havingMetal">
							<label class="text-success">Having</label>
							<select name="havingG" class="form-control" id="havingG">
								<option selected="true" disabled="disabled" value="">Having</option>
								<option value="with">With Gold</option>
								<option value="without">Without Gold</option>
							</select>
						</div>
						
						<label class="col-sm-12"></label>
						<div class="col-sm-12">
							<label class="text-success">Remarks</label>
							<input type="text" name="remarks" id="enquiryRemarks" required placeholder="Remarks" class="form-control" autocomplete="off">
						</div>
						<label class="col-sm-12"><br></label>
						
						<div class="col-sm-2">
							<label class="text-success"><br></label>
							<input type="hidden" name="submitremarks" value="true">
							<button class="btn btn-success btn-block" name="submitRemarksButton" type="submit"><span style="color:#ffcf40" class="fa fa-save"></span> Submit</button>
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
	<script>
		$(document).ready(function() {
			
			const remarks = document.getElementById('enquiryRemarks');
			remarks.addEventListener('keypress', (event)=>{
				let key = event.key;
				let regex = new RegExp("^[a-zA-Z0-9\.% ,]+$");
				if(!regex.test(key)){
					event.preventDefault();
					return false;
				}
			});
			
			const type_of_transaction = document.getElementById("typeGold");
			
			const having_metal = document.getElementById("havingMetal");
			const having_gold_input = document.getElementById("havingG");
			
			const release_amount = document.getElementById("relAmountID");
			const release_amount_input = document.getElementById("releaseAmountInput");
			
			having_metal.style.display = "none";
			release_amount.style.display = "none";
			
			type_of_transaction.addEventListener("change", (e)=>{
				let type = type_of_transaction.value;
				if(type == 'physical'){
					having_metal.style.display = "inline";
					release_amount.style.display = "none";
					
					having_gold_input.setAttribute("required", "true");
					release_amount_input.removeAttribute("required");
				}
				else if(type == 'release'){
					having_metal.style.display = "none";
					release_amount.style.display = "inline";
					
					having_gold_input.removeAttribute("required");
					release_amount_input.setAttribute("required", "true");
				}
			});
			
		});
	</script>
<?php include("footer.php"); ?>