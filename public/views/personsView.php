<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ng-click="toggleSidenav('left')" hide-gt-sm aria-label="Menu">
            <md-icon md-svg-icon="menu"></md-icon>
        </md-button>
        <h2 flex ng-hide="showSearch">Persons</h2>
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
                    <md-button ng-click="showPersons('all');">
                        <div layout="row" flex>
                            <md-icon md-menu-align-target md-svg-icon="account-multiple"></md-icon>
                            <p flex>Show all</p>
                        </div>
                    </md-button>
                </md-menu-item>
                <md-menu-item>
                    <md-button ng-click="personsOrder('name');">
                        <div layout="row" flex>
                            <md-icon md-menu-align-target md-svg-icon="sort-alphabetical"></md-icon>
                            <p flex>By ABC</p>
                        </div>
                    </md-button>
                </md-menu-item>
                <md-menu-item>
                    <md-button ng-click="personsOrder('debt');">
                        <div layout="row" flex>
                            <md-icon md-menu-align-target md-svg-icon="sort-numeric"></md-icon>
                            <p flex>By dept</p>
                        </div>
                    </md-button>
                </md-menu-item>
            </md-menu-content>
        </md-menu>

    </div>
</md-toolbar>
<md-content flex layout="row" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingPersons" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex ng-hide="isLoadingPersons" class="persons">
        <md-list-item class="md-2-line no-hover-effect" md-no-ink md-colors="person.id == highlightedItem ? {background: 'primary-100'} : {}" ui-sref="person({id:person.id})" ng-repeat="person in persons | orderBy:personsOrderBy:personsReverse | filter:searchQ track by person.id">
            <div ng-class="{1:'greyed', null:''}[person.hide]" class="md-list-item-text" layout="column" layout-align="start start">
                <h3>{{person.name}}</h3>
                <div layout="row">
                    <p class="md-caption"><span ng-show="person.last_remains_date_formated" md-colors="{color:'primary-400'}">{{person.last_remains_date_formated}}</span><span ng-show="person.last_remains_date_formated && person.current_books_price" class="grey-font"> &bullet; </span><span ng-show="(person.current_books_price - person.laxmi) > 0">{{(person.current_books_price - person.laxmi)}} р.</span><span ng-show="(person.last_remains_date_formated || person.current_books_price) && person.debt" class="grey-font"> &bullet; </span><span ng-show="person.debt"><span md-colors="{color:'warn'}">{{person.debt}} р.</span></span></p>
                </div>
            </div>
        </md-list-item>
        <div class="alert alert-danger" ng-if="persons.error">
            <strong>There was an error: </strong> {{error.error}}
            <br>Please go back and login again
        </div>
    </md-list>
</md-content>
</div>
<md-button ui-sref="addperson" class="md-fab md-fab-bottom-right" aria-label="Add person">
    <md-icon md-svg-icon="plus""></md-icon>
</md-button>
