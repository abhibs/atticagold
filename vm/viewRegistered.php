<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	$type = $_SESSION['usertype'];
	if ($type == 'VM-HO') {
		include("headervc.php");
		include("menuvc.php");
	}
	else {
		include("logout.php");
	}
	$date = date('Y-m-d');
	$empId = $_SESSION['employeeId'];
	
	$vmBranchList = mysqli_fetch_assoc(mysqli_query($con,"SELECT branch FROM vmagent WHERE agentId='$empId'"));
	$branches = explode(",", $vmBranchList['branch']);
	
	$count = count($branches);
	$branchString = "";
	for($i=0; $i<$count; $i++){
		$branchString .= "'". $branches[$i] ."'";
		if($i < $count-1){
			$branchString .= ",";
		}
	}

	$branchArray = [];
	$branchNames = mysqli_query($con, "SELECT branchId, branchName FROM branch WHERE branchId IN (". $branchString .")");
	while($row = mysqli_fetch_assoc($branchNames)){
		$branchArray[$row['branchId']] = $row['branchName'];
	}

?>

<style>
	.timerDisplay{
	font-family: monospace;
	font-weight: bold;
	letter-spacing: 4px;
	}
</style>

<div id="wrapper">
	<div class="row content">		

		<div class="col-lg-12" style="margin-bottom: 10px;">
			<div class="hpanel">
				<div class="panel-header" >
					<div class="col-lg-11">
						<h3 class="font-light m-b-xs text-success">
							Registered Customers
						</h3>
					</div>
					<div class="col-lg-1">
						<!-- <button class="btn-success btn-block" id="refreshPage"><span style="color:#fff; padding-right: 5px;" class="fa fa-refresh"></span>Refresh</button> -->
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-lg-12">
			<div class="hpanel">
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered">
							<thead>
								<tr class="theadRow">								
									<th>Branch</th>
									<th>Customer</th>									
									<th>Time</th>
									<th class="text-center">Update</th>
									<th class="text-center" width="150px">Timer</th>
								</tr>
							</thead>
							<tbody id="reg-table-body">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>		
		
	</div>
<?php include("footerNew.php"); ?>
<script>
	(function(){

		const reg_table_body = document.getElementById("reg-table-body");
		const branchData = <?php echo json_encode($branchArray) ?>;
		const branchString = "<?php echo $branchString; ?>";

		
		async function fetchData(){
			const response = await fetch("getRegistered.php", {
				method: "POST",
				headers: {
					"Content-Type": "application/json"
				},
				body: JSON.stringify({
					getRegisteredData: true,
					branchString: branchString
				})
			})
			const result = await response.json();
			if(result.error){
				console.log(error)
				return
			}

			const current_date_time = new Date();
			reg_table_body.innerHTML = "";
			result.forEach((data, i)=>{
				const { timeText, color } = getDiffColor(data['time'], current_date_time);

				const tr = document.createElement("tr");
				tr.classList.add("table-row");
				tr.setAttribute("data-time", data['time']);
				tr.style.backgroundColor = color;
				
				const td1 = document.createElement("td");
				td1.textContent = branchData[data['branch']];

				const td2 = document.createElement("td");
				td2.textContent = data['customer'];

				const td3 = document.createElement("td");				
				td3.textContent = data['time'];

				const td4 = document.createElement("td");				
				td4.classList.add("text-center");
				td4.innerHTML = "<a href='updateRegistered.php?id="+data['Id']+"' class='btn' type='button'><i class='fa fa-pencil-square-o text-success'style='font-size:16px'></i></a>"

				const td5 = document.createElement("td");
				td5.classList.add("timerDisplay");
				td5.classList.add("text-center");
				td5.textContent = timeText;

				tr.appendChild(td1);
				tr.appendChild(td2);
				tr.appendChild(td3);
				tr.appendChild(td4);
				tr.appendChild(td5);

				reg_table_body.appendChild(tr);				
			})
		}
		fetchData();
		// const interval = setInterval(fetchData, 10000);
		// const refresh_btn = document.getElementById("refreshPage");
		// refresh_btn.addEventListener("click", ()=>{
		// 	fetchData();
		// })


		const convertMS = (ms)=>{
			var d, h, m, s;
			s = Math.floor(ms / 1000);
			m = Math.floor(s / 60);
			s = s % 60;
			h = Math.floor(m / 60);
			m = m % 60;
			d = Math.floor(h / 24);
			h = h % 24;
			h += d * 24;
			return {
				hour: h,
				min: m,
				sec: s
			}
		}
		function getDiffColor(start_time, current_date_time){
			const start_time_arr = start_time.split(":");

			const start_date_time = new Date(current_date_time.getFullYear(), +current_date_time.getMonth(), current_date_time.getDate(), start_time_arr[0], start_time_arr[1], start_time_arr[2]);
			const diff = current_date_time - start_date_time;

			const { hour, min, sec } = convertMS(diff);
			let timeText = (hour % 12 > 0) ? hour % 12 +":" : "";
			timeText += min +":"+ sec;

			let color = "";
			if(hour % 12 == 0){
				if(min < 5){
					color = "#c9df8a";
				}
				else if(min >= 5 && min < 10){
					color = "#ffebaa";
				}
				else if(min >= 10 && min < 15){
					color = "#ffb38a";
				}
				else{
					color = "#ff7b7b";
				}
			}
			else{
				color = "#b6b6b6";
			}

			return {
				timeText: timeText,
				color: color
			}

		}
		function timerDisplay(){
			const table_row = document.querySelectorAll(".table-row");
			const current_date_time = new Date();

			table_row.forEach((row, i)=>{
				const time = row.dataset.time;

				const { timeText, color } = getDiffColor(time, current_date_time);

				const newTd = row.querySelector(".timerDisplay");
				newTd.textContent = timeText;								
				row.style.backgroundColor = color;
			})
		}
		timerDisplay();
		setInterval(timerDisplay, 1000);

	})();
</script>
