<div layout="column" layout-fill>
<md-toolbar layout="column">
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ui-sref="person({id:id})" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <h3 flex layout="column">
            <div>Laxmi</div>
            <div class="toolbar-subtitle">{{getPersonById(id).name}}</div>
        </h3>
        <md-button class="md-icon-button" ng-click="submit();" ng-disabled="!payed || submiting">
            <md-icon md-svg-icon="check" ng-hide="submiting"></md-icon>
            <md-progress-circular ng-show="submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
    </div>
</md-toolbar>
<md-content flex>
    <form flex layout="column">
        <md-list>
            <div layout="row">
                <md-datepicker ng-model="date" md-placeholder="Enter date" class="datepick" md-is-open="dateIsOpen"></md-datepicker>
                <md-button ng-click="setDateToLast();" class="lastdate">
                    <md-icon md-svg-icon="small:chevron-double-left" class="s18"></md-icon>
                    <span>{{lastdate | date:'dd.MM.yyyy'}}</span>
                </md-button>
            </div>
            <md-divider></md-divider>
            <md-list-item flex class="Laxmi-input">
                <md-input-container flex>
                    <label>Laxmi Jiii</label>
                    <md-icon md-svg-icon="currency-rub"></md-icon>
                    <input type="number" ng-model="payed" focus-if="1">
                </md-input-container>
            </md-list-item>
            <md-divider></md-divider>
            <div layout="row" layout-align="center" ng-if="op"><md-button ng-click="delete = 1" ng-hide="delete">Delete</md-button><md-button ng-click="del()" ng-show="delete" class="md-warn" ng-disabled="submiting">Delete</md-button><md-button ng-click="delete = 0" ng-show="delete" ng-disabled="submiting">No, No, No! Cancel</md-button></div>
    </form>
</md-content>
<md-progress-linear md-mode="indeterminate" ng-show="showSearch > 2" class="md-accent" md-diameter="20px"></md-progress-linear>
<md-toolbar class="">
    <div class="md-toolbar-tools" layout-align="end center">
        <md-button class="md-icon-button" ng-click="showDescr()">
            <md-icon md-svg-icon="comment-outline" ng-if="!descr"></md-icon>
            <md-icon md-svg-icon="comment-text-outline" ng-if="descr"></md-icon>
        </md-button>
    </div>
</md-toolbar>
</div>