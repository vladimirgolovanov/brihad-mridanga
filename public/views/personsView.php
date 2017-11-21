<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ng-click="toggleSidenav('left')" hide-gt-xs aria-label="Menu">
            <md-icon md-svg-icon="menu"></md-icon>
        </md-button>
        <h2 flex>Persons</h2>
        <md-menu md-position-mode="target-right target" >
            <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdOpenMenu($event)">
                <md-icon md-menu-origin md-svg-icon="dots-vertical"></md-icon>
            </md-button>
            <md-menu-content width="4" >
                <md-menu-item>
                    <md-button ng-click="showPersons('all');">
                        <div layout="row" flex>
                            <md-icon md-menu-align-target md-svg-icon="account-multiple"></md-icon>
                            <p flex>Показать всех</p>
                        </div>
                    </md-button>
                </md-menu-item>
                <md-menu-item>
                    <md-button ng-click="personsctrl.personsOrder('name');">
                        <div layout="row" flex>
                            <md-icon md-menu-align-target md-svg-icon="sort-alphabetical"></md-icon>
                            <p flex>По алфавиту</p>
                        </div>
                    </md-button>
                </md-menu-item>
                <md-menu-item>
                    <md-button ng-click="personsctrl.personsOrder('debt');">
                        <div layout="row" flex>
                            <md-icon md-menu-align-target md-svg-icon="sort-numeric"></md-icon>
                            <p flex>По долгам</p>
                        </div>
                    </md-button>
                </md-menu-item>
            </md-menu-content>
        </md-menu>

    </div>
</md-toolbar>
<md-content flex layout="row" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingPersons" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex ng-hide="isLoadingPersons">
        <md-list-item class="md-2-line" ui-sref="person({id:person.id})" ng-repeat="person in persons | orderBy:personsctrl.personsOrderBy:personsctrl.personsReverse">
            <div ng-class="{1:'greyed', null:''}[person.hide]" class="md-list-item-text" layout="column" layout-align="start start">
                <h3 style="overflow: hidden; text-overflow: ellipsis; width:200px;">{{person.name}}</h3>
                <div layout="row">
                    <p class="md-caption"><span ng-show="person.current_books_price">{{person.current_books_price}} р.</span><span ng-show="person.current_books_price && person.debt"> &bullet; </span><span ng-show="person.debt"><span style="color:red;">{{person.debt}} р.</span></span></p>
                </div>
                <div class="md-secondary md-caption" style="width:70px;">{{person.last_remains_date}}</div>
            </div>
        </md-list-item>
        <div class="alert alert-danger" ng-if="persons.error">
            <strong>There was an error: </strong> {{error.error}}
            <br>Please go back and login again
        </div>
    </md-list>
</md-content>
</div>