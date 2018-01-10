<div layout="column" layout-fill>
<md-toolbar layout="column">
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ui-sref="person({id:id})" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <h3 flex layout="column">
            <div ng-if="optype=='return'">Return books</div>
            <div ng-if="optype=='remains'">Remains</div>
            <div ng-if="optype=='exchange'">Exchange</div>
            <div class="toolbar-subtitle">{{getPersonById(id).name}}</div>
        </h3>
        <md-button class="md-icon-button" ng-click="submit();" ng-disabled="!canSubmit()">
            <md-icon md-svg-icon="check" ng-hide="submiting"></md-icon>
            <md-progress-circular ng-show="submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
    </div>
    <md-autocomplete placeholder="To"
                     md-no-cache="noCache"
                     md-selected-item="selectedItem"
                     md-search-text-change="searchTextChange(searchText)"
                     md-search-text="searchText"
                     md-selected-item-change="selectedItemChange(item)"
                     md-items="item in querySearch(searchText) | orderBy:'orderby'"
                     md-menu-class="autocomplete-custom-template"
                     md-item-text="item.name"
                     md-input-id="autoCompleteId"
                     md-min-length="0"
                     md-autoselect="true"
                     key-focus flex
                     ng-show="optype == 'exchange'"
                     style="border-radius:0px;"
                     tabindex="1"
                     md-autofocus>
        <md-item-template>
            <span md-highlight-text="searchText" md-highlight-flags="i" class="item-title">{{item.name}}</span>
        </md-item-template>
        <md-not-found>
            No states matching "{{searchText}}" were found.
        </md-not-found>
    </md-autocomplete>
</md-toolbar>
<md-progress-linear md-mode="indeterminate" ng-show="showSearch > 2" class="md-accent" md-diameter="20px"></md-progress-linear>
<md-content flex>
    <form name="form" flex layout="column" novalidate class="list-form">
        <md-list class="return-books-list">
            <div layout="row">
                <md-datepicker ng-model="date" md-placeholder="Enter date" class="datepick" md-is-open="dateIsOpen"></md-datepicker>
                <md-button ng-click="setDateToLast();" class="lastdate">
                    <md-icon md-svg-icon="small:chevron-double-left" class="s18"></md-icon>
                    <span>{{lastdate | date:'dd.MM.yyyy'}}</span>
                </md-button>
            </div>
            <md-divider></md-divider>
            <md-progress-linear md-mode="indeterminate" ng-show="isLoading" class="md-accent" md-diameter="20px"></md-progress-linear>
            <md-list-item class="md-2-line books-list" flex ng-repeat="item in books" layout-align="space-between top" layout="row" ng-hide="isLoading">
                <div class="md-list-item-text" layout="column" layout-align="start start" flex="70">
                    <h3>{{item.name}}</h3>
                    <p>{{item.bookgroup_name}}{{item.shortname?(' &bullet; '+item.shortname):''}}</p>
                </div>
                <md-input-container flex="15" md-no-float style="padding-top:6px">
                    <input type="number" ng-model="item.qty" min="0" max="{{item.current_qty}}">
                </md-input-container>
                <md-button class="md-small" flex="15" ng-click="item.qty = item.current_qty">{{item.current_qty}}</md-button>
            </md-list-item>
            <md-divider></md-divider>
            <div layout="row" layout-align="center" ng-if="op"><md-button ng-click="delete = 1" ng-hide="delete">Delete</md-button><md-button ng-click="del()" ng-show="delete" class="md-warn" ng-disabled="submiting">Delete</md-button><md-button ng-click="delete = 0" ng-show="delete" ng-disabled="submiting">No, No, No! Cancel</md-button></div>
        </md-list>
    </form>
</md-content>
<md-toolbar class="">
    <div class="md-toolbar-tools" layout-align="end center">
        <md-button class="md-icon-button" ng-click="showDescr()">
            <md-icon md-svg-icon="comment-outline" ng-if="!descr"></md-icon>
            <md-icon md-svg-icon="comment-text-outline" ng-if="descr"></md-icon>
        </md-button>
    </div>
</md-toolbar>
</div>