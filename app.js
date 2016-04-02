(function(){

	var app = angular.module('lotomatic', ['ngMaterial', 'ngSanitize', 'ngCookies']).config(function($mdDateLocaleProvider) {
		$mdDateLocaleProvider.months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
		$mdDateLocaleProvider.shortMonths = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
		$mdDateLocaleProvider.days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'];
		$mdDateLocaleProvider.shortDays = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];
		$mdDateLocaleProvider.firstDayOfWeek = 1;
		
	});
	var year = new Date().getFullYear();
	app.directive('resultCard', function(){
		return{
			restrict: 'A',
			templateUrl: 'result-card.php',
			controller: ['$http', '$scope', '$mdDialog', '$mdMedia', function($http, $scope, $mdDialog, $mdMedia){
				$scope.status = '  ';
				$scope.customFullscreen = $mdMedia('xs') || $mdMedia('xs');
				$scope.showAlert = function(ev, premiosUrl) {
					urlId = premiosUrl;
					$mdDialog.show({
						controller: function($mdDialog) {
							var contenidoAlerta = this;
							contenidoAlerta.juegoTitulo= juegoTitulo;
							urlScrap="scrap_premios.php?game="+game+"&urlId="+urlId+"&year="+year;
							$http.get(urlScrap).success(function(data){
								contenidoAlerta.tablaPremios = data;
							});
							this.cancel = function() {
								$mdDialog.cancel();
							};
							
						},
						controllerAs: 'premiosCtrl',
						templateUrl: 'premios.alert.php',
						parent: angular.element(document.body),
						targetEvent: ev,
						clickOutsideToClose:true,
						fullscreen: $scope.customFullscreen
					});
				};
				$scope.isThisGame = function(thisGame){
					if(thisGame == game){return true;}else{return false;}
				}
			}]
		}
	});

	app.directive('resultBoletos', function(){
		return{
			restrict: 'A',
			templateUrl: 'result-boletos.php',
			controller: ['$http', '$scope', '$mdDialog', '$mdMedia', '$filter', function($http, $scope, $mdDialog, $mdMedia, $filter){
			}],
			controllerAs: "resultBoletosCtrl"
		}
	});

	app.factory('getCombis', ["$http", function($http) {
		var newCombis;
		return function(lotoVar, miFecha){
			if(miFecha){
				resF = miFecha.split("/");
				resF[1] = parseInt(resF[1])+1;
				urlScrap="scrap_numeros.php?game="+game+"&d="+ resF[0]+"&m="+ resF[1]+"&a="+ resF[2];
			}else{
				urlScrap="scrap_numeros.php?game="+game;
			}
			$http.get(urlScrap).success(function(data){
				lotoVar.combinations = data;
				lotoVar.loading=false;
			});
		};
	}]);


	app.factory('getBoletos', ["$http", "$cookies", function($http, $cookies) {
		return function(boletosVar){
			kw = $cookies.get('kw');
			pin = $cookies.get('pin');
			$http.get("read_json.php?kw="+kw+"&pin="+pin).success(function(data){
				boletosVar.boletos=[];
				angular.forEach(Array(data)[0], function(element) {
					boletosVar.boletos.push(element);
				});
			});
		};
	}]);

	app.factory('toggleSideBar',  function ($mdSidenav) {
		return function(){
			$mdSidenav('left').toggle();
		};

	});

	app.controller('LotoController', ["$scope", "$http", "$filter", "getCombis", "toggleSideBar", "$cookies", "getBoletos", function ($scope, $http, $filter, getCombis, toggleSideBar, $cookies, getBoletos) {
		var loto = this;
		loto.kw = $cookies.get('kw');
		loto.pin = $cookies.get('pin');
		
		game='00';
		juegoTitulo='Acceso a mis boletos';

		loto.vista= "login";
		loto.loading=true;
		loto.juegoTitulo=juegoTitulo;
		
		loto.combinations = [];
		loto.hoy = new Date();
		loto.boletos = getBoletos(loto);

		//$filter('date')(new Date(), 'dd/MM/yyyy');

		this.changeGame = function(newGame, fecha) {
			loto.kw = $cookies.get('kw');
			loto.pin = $cookies.get('pin');
			
			loto.loading=true;
			game=newGame;

			if(game=="00"){
				if(!loto.kw || !loto.pin){
					loto.juegoTitulo="Acceso a mis boletos";
					loto.vista= "login";
				}else{
					loto.juegoTitulo="Mis boletos";
					loto.vista = 'misBoletos';
				}
			}else{
				loto.vista = 'historial';
				if(game=="02"){
					loto.juegoTitulo="Euromillones"
				}else if(game=="04"){
					loto.juegoTitulo="Primitiva"
				}
				loto.actualizar(fecha);	
			}
			if(fecha!='00'){
				toggleSideBar();	
			}
			
		};

		this.actualizar = function(fecha) {
			if(!fecha){this.miFecha = null;}else{
			year = fecha.split("/").reverse()[0];
			}
			loto.combinations = getCombis(this, fecha);
		};
		
		this.onlyDays = function(date) {
			var day = date.getDay();
			if(game=="02"){
				return day === 2 || day === 5;
			}else if(game=="04"){
				return day === 4 || day === 6;
			}
		};

		this.toggleLeftMenu = function() {
			toggleSideBar();
		};

		this.splitter = function(nums) {
			arr = nums.split(' ');
			arr = arr.filter(function(e){return e});
			return arr;
		};
		this.logout = function()
		{
			$cookies.remove('kw');
			$cookies.remove('pin');

			loto.user = "";
			loto.pin = "";
			loto.boletos = [];
			$scope.boletoTemp = [];

			loto.changeGame('00', '00');
		}
		this.login = function() {
			if(loto.user && loto.pin){
				$cookies.put('kw', loto.user);
				$cookies.put('pin', loto.pin);
				loto.juegoTitulo="Mis boletos";
				loto.vista = 'misBoletos';
				loto.boletos = getBoletos(loto);
			}
		}

		this.update = function(boletoTemp) {
			// Example with 1 argument
			$scope.boleto= angular.copy(boletoTemp);
		};
		
		this.update = function(boletoTemp) {
			// Example with 1 argument
			$scope.boleto= angular.copy(boletoTemp);
		};

		this.reset = function() {
			$scope.boletoTemp = {};
		};


		this.guardarBoleto = function(boletoTemp){
			loto.kw = $cookies.get('kw');
			loto.pin = $cookies.get('pin');
			var request = $http({
				method: "post",
				url: "save_json.php?kw="+loto.kw+"&pin="+loto.pin,
				data: boletoTemp,
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
			});

			request.success(function (data) {
				loto.boletos = getBoletos(loto);
				$scope.boletoTemp = {};
				$scope.nuevo = false;
			});
		};

		this.borrarBoleto = function(boletoId){
			loto.kw = $cookies.get('kw');
			loto.pin = $cookies.get('pin');
			$http.get("update_json.php?kw="+kw+"&pin="+pin+"&boletoId="+boletoId).success(function(data){
				loto.boletos = getBoletos(loto);
			});
		};
		$scope.range = function(min, max, step) {
			step = step || 1;
			var input = [];
			for (var i = min; i <= max; i += step) {
				input.push(i);
			}
			return input;
		};

		this.getCombisByGame = function(game){

			loto.loading=true;
			urlScrap="scrap_numeros.php?game="+game;
			$http.get(urlScrap).success(function(data){
				if(game=='02')
				{
					loto.euromillones = data;
				}
				else if(game=='04')
				{
					loto.primitiva = data;
				}
				loto.loading=false;
			});
		};
		if(!loto.euromillones)
		{
			loto.getCombisByGame('02');
		}
		if(!loto.primitiva)
		{
			loto.getCombisByGame('04');
		}
		this.aciertos = function(resId, bolId, theGame)
		{
			//return loto.boletos[resId];

				if(theGame=="Euromillones")
				{
					if(loto.euromillones != undefined && loto.boletos != undefined){
						var aciertosTotales=0;
						var estrellas=0;
						for(nb=1;nb<=5;nb++)
						{
							for(nr=0;nr<=4;nr++)
							{
								numBol = parseInt(loto.boletos[bolId]["num"][nb], 10);
								numRes = parseInt(loto.euromillones[resId].nums.split("                   ")[nr], 10);

								if(numBol == numRes){
									aciertosTotales++;
								}
							}
						}
						for(nb=1;nb<=2;nb++)
						{
							for(nr=0;nr<=2;nr++)
							{
								starBol = parseInt(loto.boletos[bolId]["star"][nb], 10);
								starRes = parseInt(loto.euromillones[resId].stars.split("                   ")[nr], 10);

								if(starBol == starRes){
									estrellas++;
								}
							}
						}
						return aciertosTotales+"+"+estrellas;

					}
				}else if(theGame=="Primitiva"){
					
					if(loto.primitiva != undefined && loto.boletos != undefined){
						var aciertosTotales=0;
						var reintegro=false;
						var complementario=false;
						for(nb=1;nb<=6;nb++)
						{
							for(nr=0;nr<=5;nr++)
							{
								numBol = parseInt(loto.boletos[bolId]["num"][nb], 10);
								numRes = parseInt(loto.primitiva[resId].nums.split("                   ")[nr], 10);

								if(numBol == numRes){
									aciertosTotales++;
								}
							}
							compBol = parseInt(loto.boletos[bolId]["num"][nb], 10);
							compRes = parseInt(loto.primitiva[resId].compl.split("                   ")[0], 10);

							if(compBol == compRes){
								complementario = true;
							}
						}
						reinBol = parseInt(loto.boletos[bolId]["rein"][1], 10);
						reinRes = parseInt(loto.primitiva[resId].reint[0], 10);
						
						if(reinBol == reinRes){
							reintegro = true;
						}
						if(aciertosTotales == 5)
						{
							if(complementario==true)
							{
								return aciertosTotales+" + C";
							}else{
								return aciertosTotales;
							}
						}else if(aciertosTotales == 6)
						{
							if(reintegro==true)
							{
								return aciertosTotales+" + R";
							}else{
								return aciertosTotales;
							}
						}else if(aciertosTotales < 3)
						{
							if(reintegro==true)
							{
								return "R";
							}else{
								return aciertosTotales;
							}
						}else{
							return aciertosTotales;
						}
						
					}					
				}


		}
		
		loto.changeGame('00', '00');

	}]);

	




})();