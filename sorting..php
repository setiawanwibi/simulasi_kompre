<?php

$data = [6,4,5,3,7,9,12,10];
$n = count($data);

for ($i = 0; $i < $n - 1; $i++){
	for ($j = 0; $j < $n - $i - 1; $j++){
	if ($data[$j] > $data[$j+1]){
	$temp = $data[$j];
	$data[$j] = $data[$j+1];
	$data[$j+1] = $temp;
}
}

print_r($data);
}
