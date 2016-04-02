<md-card-content style="background:#DADEE8;text-align:left" ng-init="bolId = ($index - LotoCtrl.boletos.length+1)*-1">
	<div ng-init="indexId = $index" layout="row" layout-align="center center">
		<md-fab-speed-dial md-open="false" md-direction="left" style="position:absolute;margin-top:-15px;right:25px" class="md-scale">
			<md-fab-trigger>
				<md-button style="background:rgba(0,0,0,0);box-shadow:none;"class="md-fab md-mini md-raised">
					<i class="material-icons">menu</i>
				</md-button>
			</md-fab-trigger>
			<md-fab-actions>
				<md-button class="md-fab md-raised md-mini">
					<i class="material-icons">share</i>
				</md-button>
				<md-button ng-click="LotoCtrl.borrarBoleto(bolId)" class="md-fab md-raised md-mini">
					<i class="material-icons">delete_forever</i>
				</md-button>
				<md-button class="md-fab md-raised md-mini">
					<i class="material-icons">favorite</i>
				</md-button>
			</md-fab-actions>
		</md-fab-speed-dial>
	</div>
	<div layout="row" layout-align="start center" style="color:#888;margin:0 0 20px 0;font-weight:bold">
		{{boleto.juego}} - {{boleto.etiqueta}}
	</div>
	<div layout="row" style="padding-bottom:1px">
		<md-button ng-repeat="numero in boleto.num" class="md-fab md-primary md-mini bolita" ng-click="null">
			<strong>{{numero}}</strong>
		</md-button>
	</div>
	<div layout="row" style="padding-top:1px">
		<md-button ng-repeat="estrella in boleto.star" class="md-fab md-primary md-mini bolita estrella" ng-click="null">
			<strong>{{estrella}}</strong>
		</md-button>
		<md-button ng-repeat="reintegro in boleto.rein" class="md-fab md-primary md-mini bolita reintegro" ng-click="null">
			r<strong>{{reintegro}}</strong>
		</md-button>
		<div ng-show="boleto.joker[1]" layout="row" layout-align="end center" flex>
			<div><strong>Joker:&nbsp;</strong></div>
			<div>{{boleto.joker[1]}}</div>
		</div>
	</div>
	<div style="margin-top:10px;"></div>
	<div layout="row" layout-sm="column" layout-align="space-around" ng-show="LotoCtrl.loading">
		<md-progress-circular md-mode="indeterminate"></md-progress-circular>
	</div>
	<div ng-hide="LotoCtrl.loading">
		<div ng-click="show = !show" ng-show="boleto.juego =='Euromillones'" ng-repeat="result in LotoCtrl.euromillones | limitTo:4" layout="column" style="background:#fff;color:#aaa;border:1px solid #aaa;margin-top:2px;" flex>
			<div layout="row" layout-align="center center" style="padding:5px;font-size:13px">
				<strong>{{result.fechas}}</strong>
			</div>
			<md-divider></md-divider>
			<div layout="row" layout-align="start center" style="padding:5px;">
				<div layout="row" layout-align="center center" flex>
					<strong style="font-size:13px">Ver resultado</strong>
				</div> 
				<div layout="row" layout-align="start center">
					Aciertos:&nbsp;<strong>{{LotoCtrl.aciertos($index, indexId, boleto.juego)}}</strong>
				</div>
				
			</div>
			<div ng-show="show" style="color:#888">
				<md-divider></md-divider>
				<div layout="column" layout-align="center center" style="padding:5px;">
					<div><strong>{{result.nums}}</strong></div>
					<div>Estrellas&nbsp;<strong>{{result.stars}}</strong></div>
				</div>
			</div>
			
		</div>
		<div ng-click="show = !show" ng-show="boleto.juego =='Primitiva'" ng-repeat="result in LotoCtrl.primitiva | limitTo:4" layout="column" style="background:#fff;color:#aaa;border:1px solid #aaa;margin-top:2px;" flex>
			<div layout="row" layout-align="center center" style="padding:5px;font-size:13px">
				<strong>{{result.fechas}}</strong>
			</div>
			<md-divider></md-divider>
			<div layout="row" layout-align="center center" style="padding:5px;">
				<div layout="row" layout-align="center center" flex>
					<strong style="font-size:13px">Ver resultado</strong>
				</div> 
				<div layout="row" layout-align="start center">
					Aciertos:&nbsp;<strong>{{LotoCtrl.aciertos($index, indexId, boleto.juego)}}</strong>
				</div>
			</div>
			<div ng-show="show" style="color:#888">
				<md-divider></md-divider>
				<div layout="column" layout-align="center center" style="padding:5px;">
					<div><strong>{{result.nums}}</strong> - C&nbsp;<strong>{{result.compl}}</strong></div>
					<div>R&nbsp;<strong>{{result.reint}}</strong> - Joker&nbsp;<strong>{{result.joker}}</strong></div>
				</div>
			</div>
		</div>
	</div>

	

</md-card-content>
		

