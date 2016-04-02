<md-dialog aria-label="Mango (Fruit)">
    <md-toolbar>
      <div class="md-toolbar-tools">
        <h2>Reparto de premios</h2>
        <span flex></span>
        <md-button class="md-icon-button" ng-click="premiosCtrl.cancel()">
          <i class="material-icons">clear</i>
        </md-button>
      </div>
    </md-toolbar>
    <md-dialog-content style="max-height:810px;">
     
            <div ng-repeat="tabla in premiosCtrl.tablaPremios">
              <div ng-bind-html="tabla.tabla" class="{{premiosCtrl.juegoTitulo}}"></div>
            </div>
         
    </md-dialog-content>
</md-dialog>