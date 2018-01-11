<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="person({id:person.id})" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex>
            <h2>Person</h2>
        </div>
        <md-button class="md-icon-button" ng-click="submit();" ng-disabled="form.$invalid || submiting">
            <md-icon md-svg-icon="check" ng-hide="submiting"></md-icon>
            <md-progress-circular ng-show="submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="column" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingBooks" class="md-accent" md-diameter="20px"></md-progress-linear>
    <form name="form" novalidate layout-margin layout="column">
        <md-input-container flex>
            <label>Name</label>
            <input required ng-model="person.name">
        </md-input-container>
        <md-switch ng-model="person.hide" ng-true-value="1" ng-false-value="null" aria-label="Hide">Hide</md-switch>
    </form>
</md-content>
</div>