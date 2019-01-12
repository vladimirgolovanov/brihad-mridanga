<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ng-click="toggleSidenav('left')" hide-gt-sm aria-label="Menu" ng-hide="toggleSearch">
            <md-icon md-svg-icon="menu"></md-icon>
        </md-button>
        <h2 flex ng-hide="showSearch">Reports</h2>
    </div>
</md-toolbar>
<md-content flex layout="row" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingReports" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex ng-hide="isLoadingReports">
        <md-list-item class="md-2-line no-hover-effect" md-no-ink ui-sref="report({id:id})" ng-repeat="(id, report) in reports">
            <div class="md-list-item-text">
                <h3>{{report.custom_date_formated}}</h3>
                <h4 md-colors="{color:'grey-700'}">some info...</h4>
            </div>
        </md-list-item>
    </md-list>
</md-content>
</div>
<md-button ui-sref="report" class="md-fab md-fab-bottom-right" aria-label="Add report">
    <md-icon md-svg-icon="plus""></md-icon>
</md-button>
