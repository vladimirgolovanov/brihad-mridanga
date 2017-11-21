<div layout="column" layout-fill>
<md-toolbar layout="column">
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ui-sref="person({id:c.id})" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <h3 flex>{{title}}</h3>
        <md-button class="md-icon-button" ng-click="c.submit();" ng-disabled="c.showSearch == 3">
            <md-icon md-svg-icon="check"></md-icon>
        </md-button>
    </div>
    <md-autocomplete placeholder="Книги"
                     ng-disabled="c.isDisabled"
                     md-no-cache="c.noCache"
                     md-selected-item="c.selectedItem"
                     md-search-text-change="c.searchTextChange(c.searchText)"
                     md-search-text="c.searchText"
                     md-selected-item-change="c.selectedItemChange(item)"
                     md-items="item in c.querySearch(c.searchText) | orderBy:'orderby'"
                     md-menu-class="autocomplete-custom-template"
                     md-item-text="item.name"
                     md-input-id="autoCompleteId"
                     md-min-length="0"
                     md-autoselect="true"
                     key-focus flex
                     ng-show="c.showSearch == 0"
                     style="border-radius:0px;"
                     md-autofocus>
        <md-item-template>
            <span md-highlight-text="c.searchText" md-highlight-flags="^i" class="item-title">{{item.name}}</span>
            <span md-highlight-text="c.searchText" md-highlight-flags="^i" class="item-metadata">BBT | {{item.shortname}}</span>
        </md-item-template>
        <md-not-found>
            No states matching "{{c.searchText}}" were found.
        </md-not-found>
    </md-autocomplete>
</md-toolbar>
<!--<md-content flex ng-show="c.showSearch == -1">-->
<!--    <md-calendar ng-model="date"></md-calendar>-->
<!--</md-content>-->
<md-content ng-show="c.showSearch == 1 || c.showSearch == 2" class="ng-hide" md-theme="dark" layout="column">
    <md-list>
        <md-list-item class="md-2-line" flex layout-align="space-between top" layout="row">
            <div class="md-list-item-text" layout="column" layout-align="start start" flex="70">
                <h3>{{c.selectedName}}</h3>
                <p>BBT | {{c.selectedShortname}}</p>
            </div>
            <md-input-container flex="15" md-no-float class="md-subhead md-accent">
                <input type="number" ng-model="c.selectedQty" focus-if="c.showSearch == 1" ng-focus="if(c.showSearch != 1) c.showSearch = 1;" ng-keydown="c.keyUppp($event);">
            </md-input-container>
            <md-input-container flex="15" md-no-float class="md-subhead">
                <input type="number" ng-model="c.selectedPrice" focus-if="c.showSearch == 2" ng-focus="if(c.showSearch != 2) c.showSearch = 2;" ng-keydown="c.keyUppp($event);">
            </md-input-container>
        </md-list-item>
    </md-list>
</md-content>
<md-progress-linear md-mode="indeterminate" ng-show="c.showSearch > 2" class="md-accent" md-diameter="20px"></md-progress-linear>
<md-content ng-show="c.showSearch >= 0" flex ng-style="{'opacity' : c.showSearch == 3 ? 0.4 : 1}">
    <md-list>
        <md-list-item>
            <md-input-container flex>
                <md-icon md-svg-icon="calendar"></md-icon>
                <input type="date" ng-model="date">
            </md-input-container>
        </md-list-item>
        <md-divider></md-divider>
    </md-list>
    <form flex layout="column">
        <md-list class="books-list">
            <md-list-item class="md-2-line" flex ng-repeat="item in c.books" layout-align="space-between top" layout="row">
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
        <div><md-icon md-svg-icon="small:library-books" class="s18"></md-icon> {{c.totalQty()}}</div>
        <div><md-icon md-svg-icon="small:star" class="s18"></md-icon> {{c.totalPoints()}}</div>
        <div>{{c.totalPrice()}}<md-icon md-svg-icon="small:currency-rub" class="s18"></md-icon></div>
    </div>
</md-toolbar>
</div>