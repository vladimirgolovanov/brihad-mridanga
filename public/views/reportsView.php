<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ng-click="toggleSidenav('left')" hide-gt-sm aria-label="Menu" ng-hide="toggleSearch">
            <md-icon md-svg-icon="menu"></md-icon>
        </md-button>
        <h2 flex ng-hide="showSearch">Reports</h2>
    </div>
</md-toolbar>
<md-content flex layout="column" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingReports" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex ng-hide="isLoadingReports">
        <md-list-item ng-hide="isLoadingReports" class="md-2-line no-hover-effect">
            <div class="md-list-item-text" layout="row">
                <div flex layout="row" layout-align="start center">
                    <h3>
                        <span md-colors="{color:'warn'}" style="font-weight:500;"><md-icon md-svg-icon="small:alert-circle-outline" md-colors="{color:'warn'}" style="margin-bottom:3px;"><md-tooltip>Debts</md-tooltip></md-icon> {{reports[0].debt}}</span>
                    </h3>
                </div>
                <div style="width:250px;text-align:right;">
                    <h3>
                        <div style="width:70px;display:inline-block;font-weight:500;">{{totalProp('total')}}</div>
                        <div style="width:70px;display:inline-block;font-weight:500;" md-colors="{color:'primary'}">{{totalProp('points')}}</div>
                    </h3>
                    <h4 md-colors="{color:'grey-500'}">{{totalProp('maha')}} / {{totalProp('big')}} / {{totalProp('middle')}} / {{totalProp('small')}}</span></h4>
                </div>
            </div>
            <md-divider></md-divider>
        </md-list-item>
            <md-list-item ng-repeat="report in reports" md-colors="{background: report.compiled?'primary-50':'white'}" class="md-2-line no-hover-effect" md-no-ink ui-sref="report({from:report.custom_date_start, till:report.custom_date})">
                <div class="md-list-item-text" layout="column">
                    <div layout="row">
                        <div flex>
                            <h3>{{report.custom_date_start | amDateFormat:'MMM DD, YYYY'}} &ndash; {{report.custom_date | amDateFormat:'MMM DD, YYYY'}}</h3>
                            <h4>
                                <span class="currency">{{report.gain+report.donation}}<span class="currency-light"> / {{report.buying_price}}</span></span>
                            </h4>
                        </div>
                        <div style="width:160px;text-align:right;">
                            <h3>
                                <div style="width:70px;display:inline-block;">{{report.total}}</div>
                                <div style="width:70px;display:inline-block;" md-colors="{color:'primary'}">{{report.points}}</div>
                            </h3>
                            <h4 md-colors="{color:'grey-500'}">{{report.maha}} / {{report.big}} / {{report.middle}} / {{report.small}}</span></h4>
                        </div>
                    </div>
                </div>
                <md-divider class="progress-divider">
                    <md-progress-linear md-mode="determinate" value="{{report.points / 500}}" flex></md-progress-linear>
                </md-divider>
            </md-list-item>
    </md-list>
</md-content>
</div>
<md-button ui-sref="report" class="md-fab md-fab-bottom-right" aria-label="Add report">
    <md-icon md-svg-icon="plus""></md-icon>
</md-button>
