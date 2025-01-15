<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	$type=$_SESSION['usertype'];
	if($type=='Software'){
		include("header.php");
		include("menuSoftware.php");
	}
	else{
		include("logout.php");
	}
	include("dbConnection.php");
	$date = date("Y-m-d");
?>
<style>
	#wrapper h3{
	text-transform:uppercase;
	font-weight:600;
	font-size: 16px;
	color:#123C69;
	}
	#wrapper .panel-body{
	border: 5px solid #fff;
	padding: 15px;
	box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px;
	background-color: #f5f5f5;
	border-radius: 3px;
	}
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{
	background-color:#fffafa;
	}
	.text-success{
	color:#123C69;
	text-transform:uppercase;
	font-weight:bold;
	font-size: 12px;
	}
	.btn-primary{
	background-color:#123C69;
	}	
	.theadRow {
	text-transform:uppercase;
	background-color:#123C69!important;
	color: #f2f2f2;
	font-size:11px;
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
	.fa_Icon {
	color: #b8860b;
	}
	tbody{
	font-weight: 600;
	}
</style>
<datalist id="branchList"> 
    <?php 
        $branches = mysqli_query($con,"SELECT branchId,branchName FROM branch where status=1");
        while($branchList = mysqli_fetch_array($branches)){
		?>
		<option value="<?php echo $branchList['branchId']; ?>" label="<?php echo $branchList['branchName']; ?>"></option>
	<?php } ?>
</datalist>
<div id="wrapper">
	<div class="row content">
		<div class="col-lg-12">
			<div class="hpanel">
				<div class="col-xs-8">
					<h3 class="text-success"><span class="fa fa-money" style="color:#990000"></span><b> EXPENSE DETAILS</b></h3>
				</div>
				<div class="col-xs-4" style="margin-top:6.5px">
					<form action="" method="GET">
						<div class="input-group">
							<input list="branchList" class="form-control" name="branchId" placeholder="SELECT BRANCH" required>
							<span class="input-group-btn">
								<button class="btn btn-primary btn-block" id="branchIDsearch" type="submit"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
				</div>
				<div style="clear:both"><br></div>
				<div class="panel-body">
					<table class="table table-striped table-hover">
						<thead>
							<tr class="theadRow">
								<th>BRANCH</th>
								<th>PARTICULAR</th>
								<th>TYPE</th>
								<th style="width:200px">AMOUNT</th>
								<th>STATUS</th>
								<th>TIME</th>
								<th style="text-align:center">EDIT</th>
								<th style="text-align:center">DELETE</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if(isset($_GET['branchId'])){
									$branchId = $_GET['branchId'];
									$expenseSQL = mysqli_query($con,"SELECT e.*,b.branchName FROM expense e,branch b WHERE e.branchCode=b.branchId AND e.branchCode='$branchId' AND e.date='$date'");
									while($row = mysqli_fetch_assoc($expenseSQL)){
										echo "<tr>
										<form method='POST' action='edit.php'>
										<input type='hidden' name='eid' value=".$row['id'].">
										<input type='hidden' name='branchId' value=".$row['branchCode'].">";
										echo "<td>".$row['branchName']."</td>";
										echo "<td>".$row['particular']."</td>";
										echo "<td>".$row['type']."</td>";
										echo "<td><input type='text' name='expenseAmount' class='form-control' autocomplete='off' value='".$row['amount']."'></td>";
										echo "<td>".$row['status']."</td>";
										echo "<td>".$row['time']."</td>";
										echo "<td style='text-align:center'><button onClick=\"javascript: return confirm('Please confirm Again');\" class='btn btn-lg' name='editExpenseDetails' type='submit' style='background-color:transparent'><i class='fa fa-pencil-square-o text-success' style='font-size:16px'></i></button></td>";
										echo "<td style='text-align:center'><button onClick=\"javascript: return confirm('Please confirm to  delete');\" class='btn btn-lg' type='submit' name='deleteExpense' style='background-color:transparent'><i class='fa fa-trash text-danger'></i></button></td>";
										echo "</form></tr>";
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
<?php include("footer.php");?>