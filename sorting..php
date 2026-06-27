<?php

$data = [6,5,7,3,10,1,2,4,8,9];
$n = count($data);

for ($i = 0; $i < $n - 1; $i++){
	for ($j = 0; $j < $n -$i -1; $j++){
	if ($data[$j] > $data[$j+1]){
	$temp = $data[$j];
	$data[$j] = $data[$j+1];
	$data[$j+1] = $temp;
	}
}
echo "Iterasi ke-".($i+1).": ";
print_r($data);

//rumus bubbleshort

}