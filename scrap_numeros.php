<?Php
	include('simple_html_dom.php');
	if(isset($_GET["game"])){
		$game=$_GET["game"];
	}else{
		$game="02";
	}
	if(isset($_GET["d"]) && isset($_GET["m"]) && isset($_GET["d"])){
		$d1= $_GET["d"];
		$m1= $_GET["m"];
		$a1= $_GET["a"];
		$d2= $d1;
		$m2= $m1;
		$a2= $a1;
	}else{
		$dh1= date("d");
		$mh1= date("m");
		$ah1= date("Y");
		$ah2= (int)date("Y")-1;
		$d1= $dh1;
		$m1= $mh1;
		$a1= $ah2;
		$d2= $dh1;
		$m2= $mh1;
		$a2= $ah1;
	}
	
	$html = file_get_html('http://www.loteriasyapuestas.es/es/buscador?startDate='.$d1.'/'.$m1.'/'.$a1.'&gameId='.$game.'&type=search&endDate='.$d2.'/'.$m2.'/'.$a2);
	
	if($game=='02'){$juego='euromillones';}elseif ($game =="04") { $juego='primitiva';}
	$selectorJuego='.'.$juego.' .contenidoRegion';
	if (empty($html)) {
	}else{
		$combs = array();
		$status=0;
		$nums="";
		foreach($html->find($selectorJuego) as $e) 
		{
			$c = str_get_html($e);
			$fechas = $c->find(".tituloRegion h3", 0)->plaintext;
			$fechas=str_replace("Resultado del ", "", $fechas);
			$fechas=str_replace("\t", "", $fechas);
			$fechas=str_replace("&nbsp;", "", $fechas);

			$nums = $c->find(".cuerpoRegionIzq ul", 0)->plaintext;
			$nums=str_replace("\t", "", $nums);

			$urlPremios = $c->find(".cuerpoRegionDerecha .enlace a", 0)->href;
			$premios = array_reverse(explode("/", $urlPremios))[0];
			/*$premios =$urlPremios;*/

			if($juego=="euromillones")
			{
				$stars = $c->find(".cuerpoRegionMed ul", 0)->plaintext;
				$stars=str_replace("\t", "", $stars);
				$comb = (object)array(
					'fechas' => trim($fechas),
					'nums' => trim($nums),
					'stars' => trim($stars),
					'premiosUrl' => trim($premios)
				);
			}elseif($juego=="primitiva")
			{
				$compl = $c->find(".cuerpoRegionMed ul", 0)->plaintext;
				$compl=str_replace("\t", "", $compl);

				$reint = $c->find(".cuerpoRegionMed ul", 1)->plaintext;
				$reint=str_replace("\t", "", $reint);

				$joker = $c->find(".joker .numero", 0)->plaintext;
				$joker=str_replace("\t", "", $joker);

				$comb = (object)array(
					'fechas' => trim($fechas),
					'nums' => trim($nums),
					'compl' => trim($compl),
					'reint' => trim($reint),
					'joker' => trim($joker),
					'premiosUrl' => trim($premios)
				);
			}
			array_push($combs, $comb);
			$status++;
		}
		echo json_encode($combs);
	}
	$html->clear(); 
	unset($html);
?>