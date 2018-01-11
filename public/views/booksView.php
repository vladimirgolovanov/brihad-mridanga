<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ng-click="toggleSidenav('left')" hide-gt-sm aria-label="Menu" ng-hide="toggleSearch">
            <md-icon md-svg-icon="menu"></md-icon>
        </md-button>
        <h2 flex ng-hide="showSearch">Books</h2>
        <md-input-container ng-show="showSearch" flex class="search-bar">
            <input type="text" focus-if="showSearch" ng-model="searchQ" ng-focus="searchIsFocused = true;" ng-blur="searchIsFocused = false;">
        </md-input-container>
        <md-button class="md-icon-button" ng-hide="showSearch" ng-click="toggleSearch()" aria-label="search">
            <md-icon md-svg-icon="magnify"></md-icon>
        </md-button>
        <md-button class="md-icon-button" ng-show="showSearch" ng-click="toggleSearch()" aria-label="search">
            <md-icon md-svg-icon="close"></md-icon>
        </md-button>
        <md-menu md-position-mode="target-right target" >
            <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdOpenMenu($event)">
                <md-icon md-menu-origin md-svg-icon="dots-vertical"></md-icon>
            </md-button>
            <md-menu-content width="4" >
                <md-menu-item>
                    <md-button ui-sref="bookgroups">
                        <div layout="row" flex>
                            <md-icon md-menu-align-target md-svg-icon="book-multiple-variant"></md-icon>
                            <p flex>Book groups</p>
                        </div>
                    </md-button>
                </md-menu-item>
            </md-menu-content>
        </md-menu>
    </div>
</md-toolbar>
<md-content flex layout="row" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingBooks" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex ng-hide="isLoadingBooks">
        <div ng-repeat="group in books | orderBy:'bookgroup_id' | groupBy: 'bookgroup_name' | toArray:true track by group.$key">
            <md-divider ng-hide="$first"></md-divider>
            <md-subheader>{{group.$key!='null'?group.$key:'Другие'}}</md-subheader>
            <md-list-item class="md-2-line no-hover-effect" md-no-ink md-colors="boook.id == highlightedItem ? {background: 'primary-100'} : {}" ui-sref="book({id:boook.id})" ng-repeat="boook in group | orderBy:'name' | filter:searchQ track by boook.id">
                <div class="md-list-item-text">
                    <h3>{{boook.name}}</h3>
                    <h4 md-colors="{color:'grey-700'}">
                        <md-icon md-svg-icon="small:star" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{boook.book_type}}&nbsp;&nbsp;
                        <md-icon md-svg-icon="small:library-books" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{boook.pack}}&nbsp;&nbsp;
                        <md-icon md-svg-icon="small:currency-rub" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{boook.price_buy}} / {{boook.price}} / {{boook.price_shop}}</h4>
                </div>
            </md-list-item>
        </div>
        <div class="alert alert-danger" ng-if="books.error">
            <strong>There was an error: </strong> {{error.error}}
            <br>Please go back and login again
        </div>
    </md-list>
</md-content>
</div>
<md-button ui-sref="book" class="md-fab md-fab-bottom-right" aria-label="Add book">
    <md-icon md-svg-icon="plus""></md-icon>
</md-button>
