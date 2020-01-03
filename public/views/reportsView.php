<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ng-click="toggleSidenav('left')" hide-gt-sm aria-label="Menu" ng-hide="toggleSearch">
            <md-icon md-svg-icon="menu"></md-icon>
        </md-button>
        <h2 flex ng-hide="showSearch">Reports</h2>
        <md-button class="md-icon-button" ng-click="showInfo = !showInfo">
            <md-icon md-svg-icon="information" ng-hide="showInfo"></md-icon>
            <md-icon ng-show="showInfo" md-svg-icon="information-outline"></md-icon>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="column" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingReports" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex ng-hide="isLoadingReports">
        <md-list-item ng-hide="isLoadingReports" class="md-2-line no-hover-effect totals">
            <div class="md-list-item-text" layout="row">
                <div flex="55" layout-align="start center">
                    <h2>
                        <span md-colors="{color:'warn'}">&mdash; {{showInfo?'DEBT':lastProp('debt')}}</span>
                    </h2>
                    <h2>
                        <span class="currency">{{showInfo?'TOTAL GAIN':(totalProp('gain')+totalProp('donation')).toFixed()}}</span><span class="currency-dark"> / {{showInfo?'BBT COST':totalProp('buying_price').toFixed()}}</span><br>
                        <span class="currency-light" style="font-size:10px;">{{showInfo?'INCOME':totalProp('gain').toFixed()}} + {{showInfo?'DONATION':totalProp('donation').toFixed()}}{{showInfo?' = TOTAL GAIN':''}}</span>
                    </h2>
                </div>
                <div flex="45" style="text-align:right;">
                    <h2>
                        <span md-colors="{color:'primary'}"> {{showInfo?'POINTS':totalProp('points')}}</span>
                    </h2>
                    <h2>
                        <span>{{showInfo?'BOOKS':totalProp('total')}}</span><br>
                        <span md-colors="{color:'grey-500'}" style="font-size:10px;margin-left:30px;">{{showInfo?'MAHA':totalProp('maha')}} / {{showInfo?'BIG':totalProp('big')}} / {{showInfo?'MIDDLE':totalProp('middle')}} / {{showInfo?'SMALL':totalProp('small')}}</span>
                    </h2>
                </div>
            </div>
            <md-divider></md-divider>
        </md-list-item>
        <md-list-item ng-repeat="report in reports" md-colors="{background: report.compiled?'primary-50':''}" class="md-2-line no-hover-effect" md-no-ink ui-sref="report({from:report.custom_date_start, till:report.custom_date})">
            <div class="md-list-item-text" layout="column">
                <div layout="row">
                    <div flex>
                        <h3>{{report.custom_date_start | amDateFormat:'MMM DD, YYYY'}} &ndash; {{report.custom_date | amDateFormat:'MMM DD, YYYY'}}</h3>
                        <h4>
                            <span class="currency" ng-if="report.compiled">{{showInfo?'TOTAL_GAIN':(report.gain+report.donation)}}<span class="currency-light"> / {{showInfo?'BBT_COST':report.buying_price}}</span></span>
                        </h4>
                    </div>
                    <div style="width:160px;text-align:right;">
                        <h3>
                            <div style="width:70px;display:inline-block;" ng-if="report.compiled">{{showInfo?'BOOKS':report.total}}</div>
                            <div style="width:70px;display:inline-block;" md-colors="{color:'primary'}" ng-if="report.compiled">{{showInfo?'POINTS':report.points}}</div>
                        </h3>
                        <h4 md-colors="{color:'grey-500'}" style="font-size:10px;" ng-if="report.compiled">{{showInfo?'MAHA':report.maha}} / {{showInfo?'BIG':report.big}} / {{showInfo?'MIDDLE':report.middle}} / {{showInfo?'SMALL':report.small}}</span></h4>
                    </div>
                </div>
            </div>
            <md-divider class="progress-divider">
                <md-progress-linear md-mode="determinate" value="{{report.compiled ? report.points / 500 : 0}}" flex></md-progress-linear>
            </md-divider>
        </md-list-item>
    </md-list>
</md-content>
</div>
<md-button ui-sref="report" class="md-fab md-fab-bottom-right" aria-label="Add report">
    <md-icon md-svg-icon="plus""></md-icon>
</md-button>
