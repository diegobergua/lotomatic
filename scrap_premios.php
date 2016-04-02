<?Php
	include('simple_html_dom.php');
	if(isset($_GET["game"]) && isset($_GET['urlId']) && isset($_GET['year'])){
		$game=$_GET["game"];
		$urlId=$_GET["urlId"];
		$year=$_GET["year"];
	
		if($game=='02'){$juego='euromillones';}elseif ($game =="04") { $juego='la-primitiva';}	
		
		$html = file_get_html('http://www.loteriasyapuestas.es/es/'.$juego.'/sorteos/'.$year.'/'.$urlId);
		
		$selector='.tablaDetalle';

		if (empty($html)) {
		}else{
			$tablaPremios = array();
			foreach($html->find($selector) as $e) 
			{
				$tabla = $e->outertext ;
				$tabla = str_replace("src", "title", $tabla);
				
				$tablaP = (object)array(
					'tabla' => $tabla,
				);
				array_push($tablaPremios, $tablaP);
			
			}
			echo json_encode($tablaPremios);
		}
	}
?>