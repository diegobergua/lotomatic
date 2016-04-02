<md-card-content style="background:#DADEE8;text-align:initial">
	<div layout-align="center center" style="color:#888;margin:0 0 10px 0;font-weight:bold">
		{{combination.fechas}}
	</div>

	<div layout="row" style="padding-bottom:1px">
		<md-button ng-repeat="numero in LotoCtrl.splitter(combination.nums)" class="md-fab md-primary md-mini bolita" ng-click="null">
			<strong>{{numero}}</strong>
		</md-button>
	</div>

	<div layout="row" style="padding-top:0" flex>
		<div ng-if="isThisGame('02')" layout="row">
			<md-button class="md-fab md-mini bolita estrella" ng-repeat="estrella in LotoCtrl.splitter(combination.stars)">
				<strong>{{estrella}}</strong>
			</md-button>
		</div>
		<div ng-if="isThisGame('04')" layout="row" flex>
			<md-button class="md-fab md-mini bolita complementario" ng-repeat="complementario in LotoCtrl.splitter(combination.compl)">
				c<strong>{{complementario}}</strong>
			</md-button>
			<md-button class="md-fab md-mini bolita reintegro" ng-repeat="reintegro in LotoCtrl.splitter(combination.reint)">
				r<strong>{{reintegro}}</strong>
			</md-button>
			<div layout="row" layout-align="end center" flex>
				<div><strong>Joker:&nbsp;</strong></div>
				<div>{{combination.joker}}</div>
			</div>
		</div>
	</div>
	<div layout="row" layout-align="end end">
		<div>
			<md-button class="md-raised " ng-click="showAlert($event, combination.premiosUrl)">
				PREMIOS
			</md-button>
		</div>
	</div>
</md-card-content>
		

