<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	$type = $_SESSION['usertype'];
	if($type=="Master"){
        include("header.php");
    	include("menumaster.php");
    }else if($type=='Software'){
	    include("header.php");
	    include("menuSoftware.php");
	}else if ($_SESSION['usertype'] == "Assets") {
	    include("header.php");
	    include("menuassets.php");		
	}else if ($_SESSION['usertype'] == "IT") {
	    include("header.php");
	    include("menuItMaster.php");		
	}else if ($_SESSION['usertype'] == "ITMaster") {
	    include("header.php");
	    include("menuItMaster.php");		
	}else{
        include("logout.php");
    }
	include("dbConnection.php");
	date_default_timezone_set("Asia/Kolkata");
	$date=date('Y-m-d');
?>
<style>
	#wrapper{
		background: #f5f5f5;
	}	
	#wrapper h3{
		text-transform:uppercase;
		font-weight:600;
		font-size: 20px;
		color:#123C69;
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
	.dataTables_empty{
		text-align:center;
		font-weight:600;
		font-size:12px;
		text-transform:uppercase;
	}
	.btn-success{
		display:inline-block;
		padding:0.7em 1.4em;
		margin:0 0.3em 0.3em 0;
		border-radius:0.15em;
		box-sizing: border-box;
		text-decoration:none;
		font-size: 11px;
		font-family:'Roboto',sans-serif;
		text-transform:uppercase;
		color:#fffafa;
		background-color:#123C69;
		box-shadow:inset 0 -0.6em 0 -0.35em rgba(0,0,0,0.17);
		text-align:center;
		position:relative;
	}
	.fa_Icon {
		color:#b8860b;
	}
	.row{
	    margin-left:0px;
	    margin-right:0px;
	}
	tfoot{
		background-color:#efeff5;
	}
	.text-inactive{
		color:#006400;
	}	
	.font-weight-bold{
		font-weight:bold;
	}
	.text-active{
		color:#006400;
	}
</style>
<div id="wrapper">
	<div class="content animate-panel">
		<div class="col-lg-11">
			<div class="hpanel">
				<div class="panel-heading">
					<h3 class="text-success"><i class="fa_Icon fa fa-balance-scale"></i> WEIGHING SCALE LICENSE RENEWAL </h3>
				</div>
				<div class="panel-body" style="border: 5px solid #fff;border-radius: 10px;padding: 20px;box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;background-color: #F5F5F5;">
					<div class="table-responsive">
						<table id="renewal-datatable" class="table table-striped table-bordered">
							<thead>
								<tr class="theadRow">
									<th><i class="fa fa-sort-numeric-asc"></i></th>
									<th>BRANCH DETAILS</th>
									<th style='text-align:center;'>RENEWAL DATE</th>
									<th style='text-align:center;'>RENEWAL STATUS</th>
									<th style='text-align:center'>RENEWAL ACTION</th>
								</tr>
							</thead>
							<tbody>
								<?php
									//$branchQuery = mysqli_query($con,"SELECT * FROM branch where Status=1 order by renewal_date");
									$branchQuery = mysqli_query($con,"SELECT * FROM branch where Status=1 order by renewal_date");
									$i = 1;
									while($row = mysqli_fetch_assoc($branchQuery)){
										if($row['renewal_status'] == 1){
											$statusClass="";
										}else{
											$statusClass="#f2dede";
										}
										if($row["renewal_date"]!="" && $row["renewal_date"]!="0000-00-00" && $row["renewal_date"]!="00-00-0000" && $row["renewal_date"]!=null && $row["renewal_date"]!=0){
											$renewal_date= date("d-m-Y", strtotime($row["renewal_date"]));
										}else{
											$renewal_date="Renewal Date Not Available";
										}
										
										echo "<tr id='row_".$i."' style='background:$statusClass'>";
										echo "<td>" . $i . "</td>";
										echo "<td><h5 style='text-transform:uppercase;color:#123C69;'>" . $row['branchName'] . "</h5>BRANCH ID : ". $row['branchId'] ."<br>BRANCH STATUS : ";
										if($row['Status'] == 1){
											echo "<span><i class='fa fa-check text-active'></i></button><small>ACTIVE</small></span>";
										}else{
											echo "<span><i class='fa fa-remove text-danger'></i></button><small>CLOSED</small></span>";
										}
										echo "</td>";
										echo "<td style='text-align:center;text-transform:uppercase;color:#123C69;'><h5>" .$renewal_date. "</h5><p><i class='fa fa-calendar' aria-hidden='true'> <input type='date' id='renewalDate_".$row['id']."' class='renewal-date' name='renewalDate_".$i."'></i></p></td>";
										if($row['renewal_status'] == 1){
											echo "<td style='text-align:center;color:#123C69;'><span><i class='fa fa-check text-active' ></i></span><small><b> ACTIVE</b></small></td>";
										}else{
											echo "<td style='text-align:center;color:#123C69;'><span><i class='fa fa-remove text-danger' ></i></span><small><b> EXPIRED</b></small></td>";
										}

										echo "<td style='text-align:center'><button class='btn btn-success'  title='Update Renewal Date' onclick='updateLicense(".$row['id'].")'><i class='fa fa-edit' aria-hidden='true'></i> </button></td>";
										echo "</tr>";
										$i++;
									}
								?>
								<input type="hidden" name="un" id="un" value="<?php echo $_SESSION['login_username'];?>"/>
								<input type="hidden" name="ty" id="ty" value="<?php echo $_SESSION['usertype'];?>"/>
							</tbody>

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
<?php include("footer.php"); ?>
<script>
	$(".renewal-date").val("");
	$('#renewal-datatable').DataTable({
		responsive: true
	});

	function updateLicense(id){
		var renewal_date=$("#renewalDate_"+id).val();
		var un=$("#un").val();
		var ty=$("#ty").val();
		//alert(renewal_date);		
		$.ajax({
			type: "POST",
			url: "edit.php",
			data: {action:'license-renewal',id:id,renewal_date:renewal_date,un:un,ty:ty},
			success: function(response){
				if(response=="SUCCESS"){
					alert("The license renewal data has been updated successfully");
					location.reload();
				}else{
					alert("Oops!! Error in updating data");
					location.reload();
				}
			}
		});

	}
</script>
