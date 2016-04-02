<?php
$kw=$_GET['kw'];
$pin=$_GET['pin'];
$kwpin=$kw.$pin;
if(file_exists("json/".$kwpin.".json")){
	$archivo = file_get_contents("json/".$kwpin.".json");

	$boletosGuardados = json_decode($archivo, true);
	if($boletosGuardados[$kwpin]['boletos']){
		echo json_encode(array_reverse($boletosGuardados[$kwpin]['boletos'])); 
	}
}
?>