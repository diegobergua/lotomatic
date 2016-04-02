<?php
header('Content-Type: text/html; charset=UTF-8'); 
?>
<html lang="es-ES" ng-app="lotomatic">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
 
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.0.0/angular-material.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	</head>
	<body ng-cloak  ng-controller="LotoController as LotoCtrl">
		<div layout="column" style="height:100%" flex>
			<md-toolbar layout="row" layout-align="center center">
				<md-button class="md-fab md-primary md-hue-2" ng-click="LotoCtrl.toggleLeftMenu()">
					<i class="material-icons">menu</i>
				</md-button>
				<h4>{{LotoCtrl.juegoTitulo}}</h4>
				<span flex></span>
				<md-button class="md-fab" ng-hide="LotoCtrl.vista == 'login' || LotoCtrl.vista == 'misBoletos'" ng-click="LotoCtrl.actualizar('')" layout-align="end center" style="background:white;color:rgb(63,81,181)">
					<i class="material-icons">refresh</i>
				</md-button>
				<md-button ng-show="LotoCtrl.vista == 'misBoletos'" ng-click="LotoCtrl.logout()" class="md-fab">
					<i class="material-icons">exit_to_app</i>
				</md-button>
			</md-toolbar>

			<div layout="row" id="container" flex>

				<md-sidenav md-is-locked-open="false" class="md-whiteframe-4dp" md-component-id="left">
					<md-list flex>
						<md-subheader class="md-no-sticky">Menu</md-subheader>
						<md-list-item ng-click="LotoCtrl.changeGame('00')" class="md-3-line" layout="row">
							<div class="md-list-item-text" layout="column" flex>
								<h3>Mis boletos</h3>
								<p>Todas tus combinaciones</p>
							</div>
						</md-list-item>
						<md-divider></md-divider>
						<md-list-item ng-click="LotoCtrl.changeGame('02')" class="md-3-line">
							<div class="md-list-item-text" layout="column">
								<h3>Euromillones</h3>
								<p>Ver historial de resultados</p>
							</div>
						</md-list-item>
						<md-divider></md-divider>
						<md-list-item ng-click="LotoCtrl.changeGame('04')" class="md-3-line">
							<div class="md-list-item-text" layout="column">
								<h3>Primitiva</h3>
								<p>Ver historial de resultados</p>
							</div>
						</md-list-item>
						<md-divider></md-divider>

					</md-list>
				</md-sidenav>

				<md-content id="content" layout-padding flex>
				
					<div ng-show="LotoCtrl.vista == 'login'" style="text-align:center">
						<form novalidate name="loginForm">
							<label style="color:#bbb">No es necesario registro<br/>sólo Palabra clave y Pin para<br/>almacenar boletos. ¡Invéntatelas!</label>
							<br/>
							<br/>
							<md-input-container class="md-block">
								<label>Palabra clave</label>
								<input name="user" ng-model="LotoCtrl.user" type="text" />
							</md-input-container>
							<md-input-container class="md-block">
								<label>PIN</label>
								<input required name="pin" ng-model="LotoCtrl.pin" type="num"/>
							</md-input-container>
							<md-button class="md-raised" ng-click="LotoCtrl.login()">ENTRAR</md-button>
							<br/>
							<br/>
							<label style="color:#bbb">Recuerda que debes usar la misma Palabra clave y el mismo Pin para ver los boletos guardados.</label>
							<br/>
							<br/>
							
						</form>
					</div>
					<div ng-show="LotoCtrl.vista == 'misBoletos'" ng-init="nuevo = false">
						<div layout="row" layout-align="end end" ng-show="!nuevo" flex>
							<md-button class="md-fab md-primary md-mini" ng-click="LotoCtrl.getCombisByGame('02');LotoCtrl.getCombisByGame('04')" layout-align="start center" >
								<i class="material-icons">refresh</i>
							</md-button>
							<md-button ng-click="nuevo = !nuevo" class="md-fab md-primary md-mini">
								<i class="material-icons">control_point</i>
							</md-button>
						</div>
						<form  ng-show="nuevo" novalidate name="nuevoBoleto" class="simple-form">
							<md-input-container class="md-block" flex-xs>
							<label>Tipo de boleto</label>
							 <md-select ng-model="boletoTemp.juego">
								<md-option ng-repeat="juego in ['Euromillones', 'Primitiva'] track by $index" value="{{juego}}">
								{{juego}}
								</md-option>
							</md-select>
							</md-input-container>
							<md-input-container class="md-block" flex-xs>
								<label>Etiqueta</label>
								<input maxlength="10" name="etiqueta" ng-model="boletoTemp.etiqueta" type="text"/>
							</md-input-container>
							<div ng-show="boletoTemp.juego">
								
								<div ng-show="boletoTemp.juego=='Euromillones'" layout="column">
									<div layout="row">
										<md-input-container ng-repeat="n in range(1,5)" class="md-block" flex-xs>
											<label>Num</label>
											<input maxlength="2" name="num{{n}}" ng-model="boletoTemp.num[n]" type="tel" min="0" max="99" pattern="[0-9]*" />
										</md-input-container>
									</div>
									<div layout="row">
										<md-input-container ng-repeat="n in range(1,2)" class="md-block" flex-xs>
											<label>Estrella</label>
											<input maxlength="2" name="star{{n}}" ng-model="boletoTemp.star[n]" type="tel" min="0" max="99" pattern="[0-9]*" />
										</md-input-container>
									</div>
								</div>

								<div ng-show="boletoTemp.juego=='Primitiva'" layout="column">
									<div layout="row">
										<md-input-container ng-repeat="n in range(1,6)" class="md-block" flex-xs>
											<label>Num</label>
											<input maxlength="2" name="num{{n}}" ng-model="boletoTemp.num[n]" type="tel" min="0" max="99" pattern="[0-9]*" />
										</md-input-container>
									</div>
									<div layout="row">
										<md-input-container style="width:30%"ng-repeat="n in range(1,1)" class="md-block">
											<label>Reintegro</label>
											<input maxlength="1" name="rein{{n}}" ng-model="boletoTemp.rein[n]" type="tel" min="0" max="9" pattern="[0-9]*" />
										</md-input-container>
										<md-input-container ng-repeat="n in range(1,1)" class="md-block" flex-xs>
											<label>Joker</label>
											<input maxlength="7" name="joker{{n}}" ng-model="boletoTemp.joker[n]" type="tel" min="0" max="99" pattern="[0-9]*" />
										</md-input-container>
									</div>
								</div>
								
							</div>
					
							<div layout="row" class="md-block" layout-align="end end" >
								<span ng-show="boletoTemp.juego">
								<md-button class="md-fab md-mini md-warn" ng-click="LotoCtrl.reset(nuevoBoleto)">
									<i class="material-icons">delete</i>
								</md-button>
								<md-button class="md-fab md-mini" style="background:#36ab36" ng-click="LotoCtrl.guardarBoleto(boletoTemp)">
									<i class="material-icons">done</i>
								</md-button>
								</span>
							
								<md-button class="md-fab md-mini md-primary" ng-click="nuevo = !nuevo">
									<i class="material-icons">clear</i>
								</md-button>
							</div>
						</form>
						<div style="margin:auto" flex-sm flex-gt-sm="50">
							<md-card result-boletos ng-repeat="boleto in LotoCtrl.boletos track by $index"></md-card>
						</div>
						
					</div>
					
					<div ng-show="LotoCtrl.vista == 'historial'">
						<div style="text-align:left">
							<md-datepicker md-max-date="LotoCtrl.hoy" ng-model="LotoCtrl.miFecha" md-date-filter="LotoCtrl.onlyDays" ng-change="LotoCtrl.actualizar(LotoCtrl.miFecha.getDate() + '/' + LotoCtrl.miFecha.getMonth() + '/' + LotoCtrl.miFecha.getFullYear())" md-placeholder="Elija fecha"></md-datepicker>
						</div>
						<div layout="row" layout-sm="column" layout-align="space-around" ng-show="LotoCtrl.loading">
							<md-progress-circular md-mode="indeterminate"></md-progress-circular>
						</div>
						<div style="margin:auto" flex-sm flex-gt-sm="50">
							<md-card result-card ng-repeat="combination in LotoCtrl.combinations"></md-card>
						</div>
					</div>
				</md-content>

			</div>
		</div>
		
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.2/angular.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.2/angular-animate.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.2/angular-aria.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.2/angular-messages.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.0.0/angular-material.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.2/angular-sanitize.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.2/angular-cookies.js"></script>

		<script type="text/javascript" src="app.js"></script>
		<style type="text/css">

			.bolita{margin:1px;}
			.estrella{background-color: #F1E13D !important;color:black !important;}
			.complementario{background-color: #2B7B52 !important;color:white !important;}
			.reintegro{background-color: #A23961 !important;color:white !important;}
			.joker{}
			
		
			.tablaDetalle tbody tr th:last-child{width:0;display: none;}
			.tablaDetalle tbody tr td:last-child{width:0;display: none;}
			.Primitiva .tablaJoker tbody tr th:last-child{width:auto;display: block;}
			.Primitiva .tablaJoker tbody tr td:last-child{width:auto;display: block;}
			.tablaDetalle tbody tr th:first-child{border-left: none;}
			.tablaDetalle tbody tr td:first-child{border-left: none;}
			.agraciados{display: none;width: 0;}
			.tablaDetalle{
				text-align: left;
				width: 100%;
				font-size: 13px;
			}
			.tablaDetalle th{
				background: #e2e2e2;
    			padding: 3px;
    			border-left: 1px solid #888;
			}
			.tablaDetalle td{
    			padding: 3px;
    			border-left: 1px solid #888;
			}
		</style>
	</body>
</html>