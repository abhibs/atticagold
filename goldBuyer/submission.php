<?php 	
	session_start();
	date_default_timezone_set("Asia/Kolkata");
	
	if(isset($_SESSION['buyer_mobile']) && $_SESSION['buyer_mobile']!='' && 
	isset($_SESSION['buyer_name']) && $_SESSION['buyer_name']!=''){
		
		include("header.php");
		include("dbConnection.php");
		
		$quot = $con->prepare("SELECT * FROM buyer_quot WHERE contact = :contact AND date = :date");
		$quot->execute([
		":contact"  => $_SESSION['buyer_mobile'],
		":date" => date('Y-m-d')
		]);
		
	?>	
	
	<main>
		
		<section class="services-section">	
			
			<h6 class="section-subtitle">DATA</h6>
			<h2 class="section-title" style="font-size: 20px;">Your rate & quotation Submissions</h2>
			
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Rate</th>
							<th scope="col">Quantity</th>
							<th scope="col">Date</th>
							<th scope="col">Time</th>
							<th scope="col">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php 	
							$i = 1;
							while ($row = $quot->fetch(PDO::FETCH_ASSOC)) {
								if($row['status'] == "Pending"){
									echo "<tr>";
								}
								else if($row['status'] == "Accepted"){
									echo "<tr class='table-success'>";
								}
								else if($row['status'] == "Rejected"){
									echo "<tr class='table-danger'>";
								}
								echo "<th scope='row'>".$i."</th>";
								echo "<td>".$row['rate']."</td>";
								echo "<td>".$row['quantity']."</td>";
								echo "<td>".$row['date']."</td>";
								echo "<td>".$row['time']."</td>";
								echo "<td>".$row['status']."</td>";
								echo "</tr>";
								$i++;
							}
						?>
					</tbody>
				</table>
			</div>
		</section>
		
		<footer><a href="https://www.atticagoldcompany.com" target="_blank" rel="noopener noreferrer">Attica Gold Pvt ltd</a>. ISO 9001:2015 Certified Company</footer>
	</main>
	
	<?php	
		include("footer.php");
	}
	else{
		header('Location: index.php');
		die();
	}
?>	