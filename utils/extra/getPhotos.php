<?php

    $file = "ATTICA-4130520240318112700.jpg";
	$imageFiles = glob('QuotationImage/AGPL09420241119*.png', GLOB_BRACE);
// 	print_r($imageFiles);
	
	$len = count($imageFiles);
	for($i=0; $i<$len; $i++){
	    echo "<a href='".$imageFiles[$i]."'>".$imageFiles[$i]."</a>";
	    echo "<br>";
	}

?>
