<div layout="column" layout-fill>
<md-toolbar layout="column">
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ui-sref="person({id:makectrl.id})" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <h3 flex>{{title}}</h3>
        <md-button class="md-icon-button" ng-click="makectrl.submit();" ng-disabled="makectrl.showSearch == 3">
            <md-icon md-svg-icon="check"></md-icon>
        </md-button>
    </div>
    <md-autocomplete placeholder="Книги"
                     ng-disabled="makectrl.isDisabled"
                     md-no-cache="makectrl.noCache"
                     md-selected-item="makectrl.selectedItem"
                     md-search-text-change="makectrl.searchTextChange(makectrl.searchText)"
                     md-search-text="makectrl.searchText"
                     md-selected-item-change="makectrl.selectedItemChange(item)"
                     md-items="item in makectrl.querySearch(makectrl.searchText) | orderBy:'orderby'"
                     md-menu-class="autocomplete-custom-template"
                     md-item-text="item.name"
                     md-input-id="autoCompleteId"
                     md-min-length="0"
                     md-autoselect="true"
                     key-focus flex
                     ng-show="makectrl.showSearch == 0"
                     style="border-radius:0px;"
                     md-autofocus>
        <md-item-template>
            <span md-highlight-text="makectrl.searchText" md-highlight-flags="^i" class="item-title">{{item.name}}</span>
            <span md-highlight-text="makectrl.searchText" md-highlight-flags="^i" class="item-metadata">BBT | {{item.shortname}}</span>
        </md-item-template>
        <md-not-found>
            No states matching "{{makectrl.searchText}}" were found.
        </md-not-found>
    </md-autocomplete>
</md-toolbar>
<!--<md-content flex ng-show="makectrl.showSearch == -1">-->
<!--    <md-calendar ng-model="date"></md-calendar>-->
<!--</md-content>-->
<md-content ng-show="makectrl.showSearch == 1 || makectrl.showSearch == 2" class="ng-hide" md-theme="dark" layout="column">
    <md-list>
        <md-list-item class="md-2-line" flex layout-align="space-between top" layout="row">
            <div class="md-list-item-text" layout="column" layout-align="start start" flex="70">
                <h3>{{makectrl.selectedName}}</h3>
                <p>BBT | {{makectrl.selectedShortname}}</p>
            </div>
            <md-input-container flex="15" md-no-float class="md-subhead md-accent">
                <input type="number" ng-model="makectrl.selectedQty" focus-if="makectrl.showSearch == 1" ng-focus="if(makectrl.showSearch != 1) makectrl.showSearch = 1;" ng-keydown="makectrl.keyUppp($event);">
            </md-input-container>
            <md-input-container flex="15" md-no-float class="md-subhead">
                <input type="number" ng-model="makectrl.selectedPrice" focus-if="makectrl.showSearch == 2" ng-focus="if(makectrl.showSearch != 2) makectrl.showSearch = 2;" ng-keydown="makectrl.keyUppp($event);">
            </md-input-container>
        </md-list-item>
    </md-list>
</md-content>
<md-progress-linear md-mode="indeterminate" ng-show="makectrl.showSearch > 2" class="md-accent" md-diameter="20px"></md-progress-linear>
<md-content ng-show="makectrl.showSearch >= 0" flex ng-style="{'opacity' : makectrl.showSearch == 3 ? 0.4 : 1}">
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
            <md-list-item class="md-2-line" flex ng-repeat="item in makectrl.books" layout-align="space-between top" layout="row">
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
        <div><md-icon md-svg-icon="small:library-books" class="s18"></md-icon> {{makectrl.totalQty()}}</div>
        <div><md-icon md-svg-icon="small:star" class="s18"></md-icon> {{makectrl.totalPoints()}}</div>
        <div>{{makectrl.totalPrice()}}<md-icon md-svg-icon="small:currency-rub" class="s18"></md-icon></div>
    </div>
</md-toolbar>
</div>