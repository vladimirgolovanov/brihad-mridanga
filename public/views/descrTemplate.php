<md-bottom-sheet layout="column" layout-align="center stretch">
    <md-input-container class="md-block descr-textarea" flex>
        <label>Description</label>
        <textarea ng-model="descr" rows="2" md-autofocus></textarea>
    </md-input-container>
    <md-button ng-click="submit()">Ok</md-button>
</md-bottom-sheet>
