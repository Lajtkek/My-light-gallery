<?php 

function randomHash($length = 64){
	return bin2hex(random_bytes($length));
}

function assocArrayToArray($assocArray, $key){
	$arr = [];
	foreach ($assocArray as &$object) {
		array_push($arr, $object[$key]);
	}
	return $arr;
}

?>