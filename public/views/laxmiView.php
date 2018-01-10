<div layout="column" layout-fill>
<md-toolbar layout="column">
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ui-sref="person({id:id})" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <h3 flex>{{title}}</h3>
        <md-button class="md-icon-button" ng-click="submit();" ng-disabled="showSearch == 3">
            <md-icon md-svg-icon="check"></md-icon>
        </md-button>
    </div>
    <md-autocomplete placeholder="Книги"
                     ng-disabled="isDisabled"
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
                     ng-show="showSearch == 0"
                     style="border-radius:0px;"
                     tabindex="1"
                     md-autofocus>
        <md-item-template>
            <span md-highlight-text="searchText" md-highlight-flags="^i" class="item-title">{{item.name}}</span>
            <span md-highlight-text="searchText" md-highlight-flags="^i" class="item-metadata">BBT | {{item.shortname}}</span>
        </md-item-template>
        <md-not-found>
            No states matching "{{searchText}}" were found.
        </md-not-found>
    </md-autocomplete>
</md-toolbar>
<!--<md-content flex ng-show="showSearch == -1">-->
<!--    <md-calendar ng-model="date"></md-calendar>-->
<!--</md-content>-->
<md-content ng-show="showSearch == 1 || showSearch == 2" class="ng-hide" md-theme="dark" layout="column">
    <md-list>
        <md-list-item class="md-2-line" flex layout-align="space-between top" layout="row">
            <div class="md-list-item-text" layout="column" layout-align="start start" flex="70">
                <h3>{{selectedName}}</h3>
                <p>BBT | {{selectedShortname}}</p>
            </div>
            <md-input-container flex="15" md-no-float class="md-subhead md-accent">
                <input type="number" ng-model="selectedQty" focus-if="showSearch == 1" ng-keydown="keyUppp($event);">
            </md-input-container>
            <md-input-container flex="15" md-no-float class="md-subhead">
                <input type="number" ng-model="selectedPrice" focus-if="showSearch == 2" ng-keydown="keyUppp($event);">
            </md-input-container>
        </md-list-item>
    </md-list>
</md-content>
<md-progress-linear md-mode="indeterminate" ng-show="showSearch > 2" class="md-accent" md-diameter="20px"></md-progress-linear>
<md-content ng-show="showSearch >= 0" flex ng-style="{'opacity' : showSearch == 3 ? 0.4 : 1}">
    <form flex layout="column">
        <md-list class="books-list">
            <div>
                <md-datepicker ng-model="date" md-placeholder="Enter date" class="datepick" md-is-open="dateIsOpen"></md-datepicker>
                <md-button ng-click="setDateToLast();" md-no-ink>
                    <md-icon md-svg-icon="small:chevron-double-left" class="s18"></md-icon>
                    {{lastdate | date:'dd.MM.yyyy'}}
                </md-button>
            </div>
            <md-divider></md-divider>
            <md-list-item class="md-2-line" flex ng-repeat="item in books" layout-align="space-between top" layout="row">
                <div class="md-list-item-text" layout="column" layout-align="start start" flex="70">
                    <h3>{{item.name}}</h3>
                    <p>BBT</p>
                </div>
                <md-input-container flex="15" md-no-float style="padding-top:6px">
                    <input type="tel" next-focus ng-model="item.qty">
                </md-input-container>
                <md-input-container flex="15" md-no-float style="padding-top:6px;">
                    <input type="tel" next-focus ng-model="item.price" style="color:#999;">
                </md-input-container>
            </md-list-item>
        </md-list>
    </form>
</md-content>
<md-toolbar class="">
    <div class="md-toolbar-tools" layout-align="space-between center">
        <div>
            <md-icon md-svg-icon="small:library-books" class="s18"></md-icon> {{totalQty()}} <span md-colors="{color:'primary-300'}">&bullet;</span>
            <md-icon md-svg-icon="small:star" class="s18"></md-icon> {{totalPoints()}} <span md-colors="{color:'primary-300'}">&bullet;</span>
            <md-button class="price-button" ng-click="pay()" md-colors="{background:'primary-600'}" ng-hide="showPayed"><md-icon md-svg-icon="small:currency-rub" class="s18"></md-icon> {{totalPrice()}}</md-button>
        </div>
        <md-input-container flex class="payed-input" ng-show="showPayed">
            <md-icon md-svg-icon="small:currency-rub" class="s18"></md-icon>
            <input type="number" ng-model="payed" focus-if="showPayed" md-colors="{'color':'accent', 'border-color':'accent'}">
        </md-input-container>
        <md-button class="md-icon-button" ng-click="showDescr()" ng-hide="descr">
            <md-icon md-svg-icon="comment-text-outline"></md-icon>
        </md-button>
        <md-button class="md-icon-button" ng-click="showDescr()" ng-show="descr">
            <md-icon md-svg-icon="comment-text"></md-icon>
        </md-button>
    </div>
</md-toolbar>
</div>