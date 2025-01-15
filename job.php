<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	$type = $_SESSION['usertype'];		
    if($type == 'SocialMedia'){
	    include("header.php");
		include("menuSocialMedia.php");
		
	}else if ($type == 'HR') {
		include("header.php");
		include("menuhr.php");
	}else{
		include("logout.php");
	}
		
	
	include("dbConnection.php");
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

	}
	fieldset {
	margin-top: 1.5rem;
	margin-bottom: 1.5rem;
	border: none;
	border: 5px solid #fff;
	border-radius: 10px;
	padding: 5px;
	box-shadow: rgb(50 50 93 / 25%) 0px 50px 100px -20px, rgb(0 0 0 / 30%) 0px 30px 60px -30px, rgb(10 37 64 / 35%) 0px -2px 6px 0px inset;
	}
	legend{
	margin-left:8px;
	width:400px;
	background-color: #123C69;
	padding: 5px 15px;
	line-height:30px;
	font-size: 18px;
	color: white;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.5);
	transform: translateX(-1.1rem);
	box-shadow: -1px 1px 1px rgba(0,0,0,0.8);
	margin-bottom:0px;
	letter-spacing: 2px;
	}
	button {
	transform: none;
	box-shadow: none;
	}
	
	button:hover {
	background-color: gray;
	cursor: pointer;
	}
	
	legend:after {
	content: "";
	height: 0;
	width:0;
	background-color: transparent;
	border-top: 0.0rem solid transparent;
	border-right:  0.35rem solid black;
	border-bottom: 0.45rem solid transparent;
	border-left: 0.0rem solid transparent;
	position:absolute;
	left:-0.075rem;
	bottom: -0.45rem;
	}
	.row{
	margin-left:0px;
	margin-right:0px;
	}
	@media only screen and (max-width: 500px) {
	legend{
	width:390px;
	font-size: 12px;
	
	}
	}
	.submit-button {
	font-size:20px;
	}
	
</style>
</div>
<div id="wrapper">
	<div class="row content">
		<div class="col-lg-12">
			<div class="hpanel">
				<fieldset>
					<legend><i style="padding-top:15px" class="fa_Icon fa fa-user"></i> Candidates Details</legend>					
					<div class="panel-body" style="background:#f5f5f5;">
						<div class="table-responsive">
							<table id="example5" class="table table-striped table-bordered">
								<thead>
									<tr class="theadRow">
										<th class="text-center"><i style="color:#ffcf40"
															class="fa_Icon fa fa-sort-numeric-asc"></i></th>
													<th>Customer Name</th>
													<th>Contact number</th>
													<th>email</th>
													<th>location</th>
													<th>Date</th>
													<th>status</th>
													<th>Resume</th>
													<th>Done</th>
									</tr>
								</thead>
								<tbody>

<?php
    $i = 1;
    $duplicates = array();
    
    $sql = mysqli_query($con, "SELECT *
                               FROM job 
                               WHERE status='Pending' 
                               ORDER BY id DESC");

    while($row = mysqli_fetch_assoc($sql)) {
        if (!in_array($row['mobile'], $duplicates)) {
            echo "<tr>";
            echo "<td>".$i."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['mobile']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>".$row['location']."</td>";
            echo "<td>".$row['date']."</td>";
            echo "<td>".$row['status']."</td>";
            echo "<td class='text-center'>";
            echo "<a class='submit-button' target='_blank' href='jobDocuments/" . urlencode($row['resume']) . "'><i class='fa fa-file-text-o' style='color:#900;'></i></a>";
            echo "</td>";
            	
    echo '<td class="text-center">';
    echo '<form method="POST" action="">';
    echo '<input type="hidden" name="issueId" value="' . $row['id'] . '">';
    echo '<button class="submit-button" type="submit" name="updatetBtn"><i class="fa fa-check" style="color:green;"></i></button>';

    echo '</form>';
    echo '</td>';
    
            echo "</tr>";
            $i++;
            $duplicates[] = $row['mobile'];
        }
    }
    
    if (isset($_POST['updatetBtn'])) {
    $issueId = $_POST['issueId'];

   $updateSql = "UPDATE job SET status='Done' WHERE id='$issueId'";

    if (mysqli_query($con, $updateSql)) {
       
        echo "<script>setTimeout(\"location.href = 'job.php';\", 150);</script>";
    } else {
        echo '<script>alert("Error updating status: ' . mysqli_error($con) . '");</script>';
    }
}
?>

							</tbody>
							</table>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
	<?php include("footer.php"); ?>