<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="persons" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex>
            <h2>{{person.name}}</h2>
        </div>
        <md-button class="md-icon-button" ui-sref="editperson({id:person.id})">
            <md-icon md-svg-icon="account-edit"></md-icon>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="column" layout-margin id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="person.isLoading" class="md-accent" md-diameter="20px"></md-progress-linear>
<!--    <md-whiteframe class="md-whiteframe-1dp">
        <canvas class="chart chart-line"
                chart-data="data"
                chart-labels="labels"
                chart-series="series"
                chart-options="chartOptions"></canvas>
    </md-whiteframe>-->
    <md-whiteframe class="md-whiteframe-1dp" ng-if="person.debt">
        <md-list-item class="laxmi-list-item">
            <md-icon md-svg-icon="alert" class="md-warn"></md-icon>
            <div class="md-list-item-text" flex>
                <p>Debt</p>
            </div>
            <md-text-float class="md-secondary">{{person.debt}} р.</md-text-float>
        </md-list-item>
    </md-whiteframe>
    <md-whiteframe class="md-whiteframe-1dp" ng-if="person.laxmi">
        <md-list-item class="laxmi-list-item">
            <md-icon md-svg-icon="cash-multiple"></md-icon>
            <div class="md-list-item-text" flex>
                <p>Laxmi</p>
            </div>
            <md-text-float class="md-secondary">{{person.laxmi}} р.</md-text-float>
        </md-list-item>
    </md-whiteframe>
    <md-whiteframe class="md-whiteframe-1dp" ng-if="booksCount">
        <md-list flex>
            <md-subheader ng-show="person.current_books_price">
                Книги на руках (на {{person.current_books_price}} р.)
            </md-subheader>
            <md-list-item ng-click="booksTable = true;" ng-show="!booksTable" class="no-hover-effect">
                <div class="md-list-item-text" flex>
                    <span ng-repeat="book in person.books">{{book.shortname?book.shortname:book.name}} <span class="md-caption caption-top-align" md-colors="{color:'grey'}">{{book[0]}}</span><span md-colors="{color:'grey'}" ng-if="!$last"> &bullet; </span></span></span>
                </div>
            </md-list-item>
            <md-list-item ng-show="booksTable" ng-repeat="book in person.books">
                <md-icon md-svg-icon="library-books" class="md-primary md-hue-1"></md-icon>
                <div class="md-list-item-text nowrap" flex>
                    <p>{{book.name}}</p>
                </div>
                <md-text-float class="md-secondary book-list-num">{{book[0]}}</md-text-float>
            </md-list-item>
        </md-list>
    </md-whiteframe>
    <md-whiteframe class="md-whiteframe-1dp">
        <md-list flex class="operations">
            <md-subheader>Операции</md-subheader>
<!--            <md-list-item ui-sref="os.type == 1 ? editmake({id:person.id, op:os.id}) : (os.type == 2 ? editLaxmi({id:person.id, op:os.id}) : null)" class="md-3-line" ng-repeat="os in person.osgrp" layout="row"  layout-align="start start" md-colors="{background:opIcon[os.type].bgcolor}">-->
            <md-list-item ng-click="editOperation(os)" class="md-3-line no-hover-effect" md-no-ink ng-repeat="os in person.osgrp" layout="row"  layout-align="start start" md-colors="{background:opIcon[os.type].bgcolor}">
                <md-icon md-svg-icon="{{opIcon[os.type].icon}}" ng-class="opIcon[os.type].class"></md-icon>
                <div class="md-list-item-text" flex>
                    <p><span md-colors="{color:opIcon[os.type].color}">{{os.date | date:'dd.MM.yy'}}</span><span ng-if="os.description" md-colors="{color:'grey'}"> / {{os.description}}</span></p>
                    <h3 ng-if="os.type == 'remains'"><span ng-if="!os.books_distr.length">:(</span><span ng-repeat="book in os.books_distr">{{book.shortname?book.shortname:book.name}} <span class="md-caption caption-top-align" md-colors="{color:'primary-400'}">{{book.o}}</span><span md-colors="{color:'primary-200'}" ng-if="!$last"> &bullet; </span></span></span></h3>
                    <h3 ng-if="os.type == 'make'"><span ng-if="!os.books.length">:(</span><span ng-repeat="book in os.books">{{book.shortname?book.shortname:book.name}} <span class="md-caption caption-top-align" md-colors="{color:'grey'}">{{book.o}}</span><span md-colors="{color:'grey'}" ng-if="!$last"> &bullet; </span></span></span></h3>
                    <h3 ng-if="os.type == 'return'"><span ng-if="!os.books.length">:(</span><span ng-repeat="book in os.books">{{book.shortname?book.shortname:book.name}} <span class="md-caption caption-top-align" md-colors="{color:'grey'}">{{book.o}}</span><span md-colors="{color:'grey'}" ng-if="!$last"> &bullet; </span></span></span></h3>
                    <h3 ng-if="os.type == 'Laxmi'"><span>{{os.Laxmi}} р.</span></h3>
                    <h4 md-colors="{color:'primary'}" ng-if="os.type == 'remains'">
                        <md-icon md-svg-icon="small:library-books" class="s12" md-colors="{color:'primary-300'}"></md-icon> {{os.total_books}}<span ng-if="os.total_non_bbt" md-colors="{color:'primary-300'}">+{{os.total_non_bbt}}</span>&nbsp;&nbsp;
                        <md-icon md-svg-icon="small:star" class="s12" md-colors="{color:'primary-300'}"></md-icon> {{os.total_points}}&nbsp;&nbsp;
                        <span ng-if="os.donation_gain" md-colors="{color:'green'}"><md-icon md-svg-icon="small:arrow-up-bold" class="s12" md-colors="{color:'green'}"></md-icon> {{os.donation_gain}}&nbsp;&nbsp;</span>
                        <span ng-if="os.debt" md-colors="{color:'warn-400'}"><md-icon md-svg-icon="small:alert" class="s12" md-colors="{color:'warn-400'}"></md-icon> {{os.debt}}</span></h4>
                    <h4 md-colors="{color:'grey-700'}" ng-if="os.type == 'make'">
                        <md-icon md-svg-icon="small:library-books" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{os.total_books}}<span ng-if="os.total_non_bbt" md-colors="{color:'grey-400'}">+{{os.total_non_bbt}}</span>&nbsp;&nbsp;
                        <md-icon md-svg-icon="small:star" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{os.total_points}}&nbsp;&nbsp;
                        <md-icon md-svg-icon="small:currency-rub" class="s12"></md-icon> {{os.total_Laxmi}}</h4>
                    <h4 ng-if="os.type == 'Laxmi' || os.type == 'return'">&nbsp;</h4>
                    <md-divider></md-divider>
                </div>

            </md-list-item>
            <md-divider></md-divider>
            <div layout="row" layout-align="center"><md-button ng-click="showAll();">Show all</md-button></div>
        </md-list>
    </md-whiteframe>
</md-content>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-around center">
        <md-button aria-label="Issue books" class="md-icon-button" ui-sref="make({id:person.id})">
            <md-icon md-svg-icon="plus-circle-outline"></md-icon>
        </md-button>
        <md-button aria-label="Laxmi" class="md-icon-button" ui-sref="Laxmi({id:person.id})">
            <md-icon md-svg-icon="cash-100"></md-icon>
        </md-button>
        <md-button aria-label="Remains" class="md-icon-button" ui-sref="remains({id:person.id})">
            <md-icon md-svg-icon="checkbox-marked"></md-icon>
        </md-button>
        <md-button aria-label="Exchange" class="md-icon-button" ui-sref="exchange({id:person.id})">
            <md-icon md-svg-icon="cached"></md-icon>
        </md-button>
        <md-button aria-label="Return" class="md-icon-button" ui-sref="return({id:person.id})">
            <md-icon md-svg-icon="undo"></md-icon>
        </md-button>
    </div>
</md-toolbar>
</div>