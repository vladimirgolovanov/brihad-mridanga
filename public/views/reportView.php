<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="reports" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex layout="row" layout-align="start center">
            <h2>{{report.id?'Report':'New report'}}</h2>
        </div>
        <md-button ng-if="updateRequired()" class="md-icon-button" ng-click="update()" ng-disabled="updating">
            <md-icon md-svg-icon="refresh" ng-hide="updating"></md-icon>
            <md-progress-circular ng-show="updating" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
        <md-button class="md-icon-button" ng-click="showInfo = !showInfo">
            <md-icon md-svg-icon="information" ng-hide="showInfo"></md-icon>
            <md-icon ng-show="showInfo" md-svg-icon="information-outline"></md-icon>
        </md-button>
        <md-button class="md-icon-button" clipboard text="textToCopy" supported="clipboardSupported" ng-show="clipboardSupported" on-copied="clipboardSuccess()">
            <md-icon md-svg-icon="clipboard-arrow-down" ng-hide="clipboardCopied"></md-icon>
            <md-icon ng-show="clipboardCopied" md-svg-icon="clipboard-check"></md-icon>
        </md-button>
        <md-button ng-if="!till" class="md-icon-button" ng-click="submit()" ng-disabled="submiting">
            <md-icon md-svg-icon="check" ng-hide="submiting"></md-icon>
            <md-progress-circular ng-show="submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
        <md-button ng-if="till && state == 1" class="md-icon-button" ng-click="submit()" ng-disabled="submiting || noRemains()">
            <md-icon md-svg-icon="lock" ng-hide="!report.compiled || submiting"></md-icon>
            <md-icon md-svg-icon="lock-open" ng-hide="report.compiled || submiting"></md-icon>
            <md-progress-circular ng-show="submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="column" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingReports" class="md-accent" md-diameter="20px"></md-progress-linear>
    <div layout="row" ng-hide="report.id" layout-margin>
        <md-datepicker md-date-filter="dateFilter" ng-model="report.custom_date" md-placeholder="Enter date" class="datepick"></md-datepicker>
    </div>
    <div layout="row" class="report-bar" layout-align="space-between center" ng-show="report.id">
        <div layout="row" layout-margin ng-hide="sortMenu">
            <md-select ng-model="from" ng-change="dateSelected()">
                <md-option ng-repeat="r in reports" ng-value="r.custom_date_start" ng-disabled="r.custom_date_start > till">{{r.custom_date_start | amDateFormat:'MMM DD, YYYY'}}</md-option>
            </md-select>
            <div style="margin:15px 5px 15px 2px;">&mdash;</div>
            <md-select ng-model="till" ng-change="dateSelected()">
                <md-option ng-repeat="r in reports" ng-value="r.custom_date" ng-disabled="r.custom_date < from">{{r.custom_date | amDateFormat:'MMM DD, YYYY'}}</md-option>
            </md-select>
        </div>
        <md-button class="md-icon-button" ng-click="sortMenu = true" ng-hide="sortMenu">
            <md-icon md-svg-icon="sort" md-colors="{color:'primary-300'}"></md-icon>
        </md-button>
        <div flex ng-show="sortMenu"></div>
        <div layout="row" ng-show="sortMenu">
            <md-button class="md-icon-button" ng-click="sortMenu = false; sortBy = 'name'">
                <md-icon md-svg-icon="sort-alphabetical"></md-icon>
            </md-button>
            <md-button class="md-icon-button" class="md-warn" ng-click="sortMenu = false; sortBy = 'debt'">
                <md-icon md-svg-icon="minus-circle" class="md-warn"></md-icon>
            </md-button>
            <md-button class="md-icon-button" ng-click="sortMenu = false; sortBy = 'donation'">
                <md-icon md-svg-icon="cash-100" class="currency"></md-icon>
            </md-button>
            <md-button class="md-icon-button" ng-click="sortMenu = false; sortBy = 'points'">
                <md-icon md-svg-icon="star" md-colors="{color:'primary-300'}"></md-icon>
            </md-button>
            <md-button class="md-icon-button" ng-click="sortMenu = false; sortBy = 'total'">
                <md-icon md-svg-icon="book-open-variant"></md-icon>
            </md-button>
        </div>
    </div>
    <md-list flex ng-hide="isLoadingReports || !report.id">
        <md-divider></md-divider>
        <md-list-item ng-hide="isLoadingReports" class="md-2-line no-hover-effect totals">
            <div class="md-list-item-text" layout="row">
                <div flex="55" layout-align="start center">
                    <h2>
                        <span md-colors="{color:'warn'}">&mdash; {{showInfo?'DEBT':getFieldSum(report.persons, 'debt')}}</span>
                    </h2>
                    <h2>
                        <span class="currency">{{showInfo?'TOTAL GAIN':(getFieldSum(report.persons, 'gain')+getFieldSum(report.persons, 'donation')).toFixed()}}</span><span class="currency-dark"> / {{showInfo?'BBT COST':getFieldSum(report.persons, 'buying_price').toFixed()}}</span><br>
                        <span class="currency-light" style="font-size:10px;">{{showInfo?'INCOME':getFieldSum(report.persons, 'gain').toFixed()}} + {{showInfo?'DONATION':getFieldSum(report.persons, 'donation').toFixed()}}{{showInfo?' = TOTAL GAIN':''}}</span>
                    </h2>
                </div>
                <div flex="45" style="text-align:right;">
                    <h2>
                        <span md-colors="{color:'primary'}"> {{showInfo?'POINTS':getFieldSum(report.persons, 'points')}}</span>
                    </h2>
                    <h2>
                        <span>{{showInfo?'BOOKS':getFieldSum(report.persons, 'total')}}</span><br>
                        <span md-colors="{color:'grey-500'}" style="font-size:10px;margin-left:30px;">{{showInfo?'MAHA':getFieldSum(report.persons, 'maha')}} / {{showInfo?'BIG':getFieldSum(report.persons, 'big')}} / {{showInfo?'MIDDLE':getFieldSum(report.persons, 'middle')}} / {{showInfo?'SMALL':getFieldSum(report.persons, 'small')}}</span>
                    </h2>
                </div>
            </div>
        </md-list-item>
        <div ng-repeat="group in report.persons | orderBy:'pgroup':true | groupBy: 'pgroup' | toArray:true track by group.$key">
            <md-divider></md-divider>
            <md-list-item class="md-2-line no-hover-effect" md-no-ink md-colors="{background:'primary-50'}">
                <div class="md-list-item-text group-header" layout="row">
                    <div flex>
                        <h3>{{group.$key!='null'?group.$key:'Другие'}}</h3>
                        <h4>
                            <div style="width:70px;display:inline-block;" class="currency" ng-show="getFieldSum(group, 'donation')">{{showInfo?'DONATION':getFieldSum(group, 'donation')}}</div>
                            <div style="width:70px;display:inline-block;" md-colors="{color:'warn'}" ng-show="getFieldSum(group, 'debt')">&mdash; {{showInfo?'DEBT':getFieldSum(group, 'debt')}}</div>
                        </h4>
                    </div>
                    <div style="width:160px;text-align:right;">
                        <h3>
                            <div style="width:70px;display:inline-block;">{{showInfo?'BOOKS':getFieldSum(group, 'total')}}</div>
                            <div style="width:70px;display:inline-block;" md-colors="{color:'primary'}">{{showInfo?'POINTS':getFieldSum(group, 'points')}}</div>
                        </h3>
                        <h4 md-colors="{color:'grey-500'}" style="font-size:10px;">{{showInfo?'MAHA':getFieldSum(group, 'maha')}} / {{showInfo?'BIG':getFieldSum(group, 'big')}} / {{showInfo?'MIDDLE':getFieldSum(group, 'middle')}} / {{showInfo?'SMALL':getFieldSum(group, 'small')}}</span></h4>
                    </div>
                </div>
                <md-divider></md-divider>
            </md-list-item>
            <md-list-item md-colors="(p.no_remains && (p.current_books_price || p.laxmi || p.debt))?{background:'accent-50'}:{}" class="md-2-line no-hover-effect" md-no-ink ng-repeat="p in group | orderBy:sortBy:(sortBy=='name'?false:true)">
                <div class="md-list-item-text" layout="row">
                    <div flex>
                        <h3 md-colors="(p.no_remains && !p.current_books_price && !p.laxmi && !p.debt)?{color:'grey-400'}:{}">{{p.name}}</h3>
                        <h4>
                            <div style="width:120px;display:inline-block;" class="currency" ng-show="(p.current_books_price || p.laxmi) && p.no_remains">{{showInfo?'RECEIVED / COST':(p.laxmi+' / '+p.current_books_price)}}</div>
                            <div style="width:70px;display:inline-block;" class="currency" ng-show="p.donation">{{showInfo?'DONATION':p.donation}}</div>
                            <div style="width:70px;display:inline-block;" md-colors="{color:'warn'}" ng-show="p.debt">&mdash; {{showInfo?'DEBT':p.debt}}</div>
                        </h4>
                    </div>
                    <div style="width:160px;text-align:right;" ng-show="p.no_remains !== true && p.no_remains !== false">
                        <h3>
                            <div style="width:70px;display:inline-block;">{{p.no_remains?p.books.length:(showInfo?'BOOKS':p.total)}}</div>
                            <div style="width:70px;display:inline-block;" md-colors="{color:'primary'}">{{showInfo?'POINTS':p.points}}</div>
                        </h3>
                        <h4 md-colors="{color:'grey-500'}" style="font-size:10px;">{{showInfo?'MAHA':p.maha}} / {{showInfo?'BIG':p.big}} / {{showInfo?'MIDDLE':p.middle}} / {{showInfo?'SMALL':p.small}}</span></h4>
                    </div>
                    <md-button style="padding-right:0px;margin-right:0px;" class="md-icon-button" ui-sref="remains({id:p.id})" ng-show="(p.no_remains && (p.current_books_price || p.laxmi || p.debt))">
                        <md-icon md-colors="{color:'accent'}" md-svg-icon="checkbox-marked"></md-icon>
                    </md-button>
                    <div style="width:80px;text-align:right;" ng-show="p.no_remains === false">
                        <md-icon md-colors="{color:'grey-400'}" md-svg-icon="refresh"></md-icon>
                    </div>
                </div>
                <md-divider></md-divider>
            </md-list-item>
        </div>
    </md-list>
</md-content>
</div>