<?php
$test_array = array(array());
$num = 0;
while ($num < 4) {
	switch ($num) {
		case 0:
			$name = "test1";
			$test_array[$num]["passed"] = (false) ? "True" : "False";
			$test_array[$num]["db_name"] = $name;
			break;
			
		case 1:
			$name = "test2";
			$test_array[$num]["passed"] = (true) ? "True" : "False";
			$test_array[$num]["db_name"] = $name;
			break;
			
		case 2:
			$name = "test3";
			$test_array[$num]["passed"] = (false) ? "True" : "False";
			$test_array[$num]["db_name"] = $name;
			break;
		
		case 3:
			$name = "test4";
			$test_array[$num]["passed"] = (true) ? "True" : "False";
			$test_array[$num]["db_name"] = $name;
			break;
	}
	$num++;
}


for ($x = 0; $x < count($test_array); $x++) {
	echo "Analysis: {$x}. " . $test_array[$x]["passed"] . " :: " . $test_array[$x]["db_name"] . "<br>";
}


