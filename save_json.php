<?php
$kw=$_GET['kw'];
$pin=$_GET['pin'];
$kwpin=$kw.$pin;
$archivo = file_get_contents("json/".$kwpin.".json");
$boletosGuardados = json_decode($archivo, true);

$nuevoBoleto = json_decode(file_get_contents("php://input"));
$boletosGuardados[$kwpin]['boletos'][]=$nuevoBoleto;

$boletosGuardadosNuevos = json_encode($boletosGuardados);
file_put_contents("json/".$kwpin.".json", $boletosGuardadosNuevos);


/*
$json = json_encode($oJson) ;
echo $json;
/*
$postdata = file_get_contents("php://input");
$file = 'json/boletos.json';
$todosLosBoletos = json_decode(file_get_contents($file)).$postdata;
file_put_contents($file, json_encode($todosLosBoletos), FILE_APPEND | LOCK_EX);
 */
?>