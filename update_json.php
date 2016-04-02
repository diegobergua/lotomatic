<?php
$kw=$_GET['kw'];
$pin=$_GET['pin'];
$boletoId=$_GET['boletoId'];
$kwpin=$kw.$pin;
$archivo = file_get_contents("json/".$kwpin.".json");
$boletosGuardados = json_decode($archivo, true);

$nuevoBoleto = json_decode(file_get_contents("php://input"));
unset($boletosGuardados[$kwpin]['boletos'][$boletoId]);

$boletosGuardadosNuevos = json_encode($boletosGuardados);
file_put_contents("json/".$kwpin.".json", $boletosGuardadosNuevos);


?>