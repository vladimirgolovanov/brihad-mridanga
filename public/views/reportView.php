<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="reports" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex>
            <h2>Report</h2>
        </div>
        <md-button class="md-icon-button" ng-click="submit();" ng-disabled="1 || form.$invalid || submiting">
            <md-icon md-svg-icon="check" ng-hide="submiting"></md-icon>
            <md-progress-circular ng-show="submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="column" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingReports" class="md-accent" md-diameter="20px"></md-progress-linear>
    <form name="form" novalidate layout-margin layout="column">
        <div layout="row">
            <md-datepicker ng-disabled="true" ng-model="report.custom_date" md-placeholder="Enter date" class="datepick"></md-datepicker>
        </div>
    </form>
    <md-list flex ng-hide="isLoadingReports">
        <md-list-item class="md-2-line no-hover-effect" md-no-ink ng-repeat="(id, p) in report.persons">
            <div class="md-list-item-text">
                <h3>{{getPersonById(id).name}}</h3>
                <h4 md-colors="{color:'grey-700'}">
                    <md-icon md-svg-icon="small:star-circle" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{p.maha}}
                    <md-icon md-svg-icon="small:star" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{p.big}}
                    <md-icon md-svg-icon="small:star-half" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{p.middle}}
                    <md-icon md-svg-icon="small:star-outline" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{p.small}}
                </h4>
            </div>
        </md-list-item>
    </md-list>
</md-content>
</div>