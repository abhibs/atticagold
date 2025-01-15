<?php
	error_reporting(E_ERROR | E_PARSE);
	session_start();
	$type=$_SESSION['usertype'];
	if($type=='CallDisplay'){
		include("header.php");
	}
	else if($type == 'Master'){
		include("header.php");
	}
	else{
		include("logout.php");
	}
	date_default_timezone_set("Asia/Kolkata");
	include("dbConnection.php"); 
	$date=date('Y-m-d');
	
	/***  SINGLE CURL INVOCATION WITH POST DATA  ***/
	
	$rowTSC = array();
	$rowTSC['From_Billing_Date'] = $date;
	$rowTSC['To_Billing_Date'] = $date;
	$url1 = "http://14.97.3.235/Alpha_Attica/Api/sold_out_count";
	$chs = curl_init($url1);
	$a = json_encode($rowTSC);
	curl_setopt($chs, CURLOPT_POSTFIELDS,$a);
	curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($chs);
	curl_close($chs); 
	$result4 = json_decode($res, true);
	//echo "Total Sold Out : ".$result4['TOTAL_SOLD_OUT'];
	$soldout = $result4['TOTAL_SOLD_OUT'];
	
?>
<script>
	window.alert = function() {};
</script>
?>
<meta http-equiv="refresh" content="15">
<style>
	td {
	border: 1px solid white;
	}
</style>
<div class="content" style="margin-top:50px">
	<!--<h3 class="text-success" class="modal-title" id="exSoldOut">Agent Details</h3>-->
	<table id="example2" class="table" style="background-color:#1a1a1a;color:white;">
		<thead>
			<tr>
				<th>Name</th>
				<!--<th>ID</th>-->
				<th>Sold</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($result4 as $key => $value) {					
                   $result3 = array("8009" => 'Anandhi',"8006" => 'Shamini', "8032" => 'Angel', "8010" => 'Anuja', "8027" => 'Blessy', "8037" => 'Ganga', "8017" => 'Geetha', "8094" => 'Hemavathi R', "8003" => 'Indhumathi', "8005" => 'Indra', "8040" => 'Varalakshmi', "8035" => 'Azeez', "8025" => 'Jyothika', "8002" => 'Kiran', "8001" => 'Mamatha Y', "8029" => 'Mamatha.s', "8023" => 'Meghana CSR', "8031" => 'Monisha', "8030" => 'Padmapriya', "8012" => 'Shwetha', "8019" => 'Priya.N', "8016" => 'Ramya N', "8034" => 'Ramya T', "8039" => 'Shamala', "8007" => 'RD Chowdhary', "8033" => 'Shodini', "8024" => 'Sneha', "8036" => 'Soundarya', "8018" => 'Akshaya', "8008" => 'Vijayalakshmi', "8036" => 'Vydevi', "8013" => 'Sanjana',"8103" => 'Rajarajeshwari',"8076" => 'Geetha C',"8101" => 'Manigandan',"8004" => 'Gangothri',"8014" => 'Anitha',"8028" => 'Shilpa');
								// echo "<b> (" . $result3[$key] . " " . $key . " ) : " . $value . "</b><br>";
                    echo "<tr>";
                    echo "<td>" . '<h1><b>' . $result3[$key] . '</b></h4>' . "</td>";
                    // echo "<td>" . '<h4>' . $key . '</h4>' . "</td>";
                    echo "<td>" . '<h1><b>' . $value . '<b></h4>' . "</td>";
                    echo "<tr>";
				}
			?>
		</tbody>
	</table>
</div>
</div>
</div>
