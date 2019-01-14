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
        <md-divider></md-divider>
        <md-list-item class="md-2-line no-hover-effect" md-no-ink ng-repeat="(id, p) in report.persons">
            <div class="md-list-item-text" layout="row">
                <div flex>
                <h3>{{getPersonById(id).name}}</h3>
                <h4>
                    <span ng-show="p.donation" md-colors="{color:'primary'}"><md-icon md-svg-icon="small:arrow-up" class="s12" md-colors="{color:'primary'}"></md-icon> {{p.donation}}</span>
                    <span ng-show="p.debt" md-colors="{color:'warn'}"><md-icon md-svg-icon="small:arrow-down" class="s12" md-colors="{color:'warn'}"></md-icon> {{p.debt}}</span>
                </h4>
                </div>
                <div style="width:150px;text-align:right;">
                    <h3>
                        <div style="width:70px;display:inline-block;" md-colors="{color:'primary'}"><md-icon md-svg-icon="small:star" class="s12" md-colors="{color:'grey-400'}"></md-icon> {{p.points}}</div>
                        <div style="width:70px;display:inline-block;"><md-icon md-svg-icon="small:library-books" class="s12" md-colors="{color:'grey-400'}"></md-icon> {{p.total}}</div>
                    </h3>
                    <h4 md-colors="{color:'grey-500'}">{{p.maha}} / {{p.big}} / {{p.middle}} / {{p.small}}</span></h4>
                </div>
            </div>
            <md-divider></md-divider>
        </md-list-item>
    </md-list>
</md-content>
</div>