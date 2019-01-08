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
        <div layout="row">
            <md-input-container flex>
                <label>Name</label>
                <input required ng-model="person.name">
            </md-input-container>
        </div>
        <div layout="row">
            <md-input-container flex>
                <label>Description</label>
                <input ng-model="person.descr">
            </md-input-container>
        </div>
        <div layout="row">
            <md-input-container flex>
                <label>Group</label>
                <md-select ng-model="person.persongroup_id">
                    <md-option ng-value=""><em>None</em></md-option>
                    <md-option ng-repeat="pg in persongroups" ng-value="pg.id">
                        {{pg.name}}
                    </md-option>
                </md-select>
            </md-input-container>
        </div>
        <div layout="row">
            <md-switch flex="50" ng-model="person.hide" ng-true-value="1" ng-false-value="null" aria-label="Hide">Hide</md-switch>
            <md-switch flex="50" ng-model="person.favourite" ng-true-value="1" ng-false-value="null" aria-label="Hide">Favourite</md-switch>
        </div>
    </form>
</md-content>
</div>