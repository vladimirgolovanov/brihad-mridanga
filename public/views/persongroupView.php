<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="persongroups" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex>
            <h2>Person group</h2>
        </div>
        <md-button class="md-icon-button" ng-click="submit();" ng-disabled="personForm.$invalid">
            <md-icon md-svg-icon="check" ng-hide="submiting"></md-icon>
            <md-progress-circular ng-show="submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="column" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingPersons" class="md-accent" md-diameter="20px"></md-progress-linear>
    <form name="personForm" novalidate layout-margin>
        <div layout="row">
            <md-input-container flex>
                <label>Name</label>
                <input required ng-model="persongroup.name">
            </md-input-container>
        </div>
    </form>
</md-content>
</div>