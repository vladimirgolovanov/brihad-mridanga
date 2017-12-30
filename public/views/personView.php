<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="persons" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex>
            <h2>{{person.name}}</h2>
        </div>
    </div>
</md-toolbar>
<md-content flex layout="column" layout-margin id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="person.isLoading" class="md-accent" md-diameter="20px"></md-progress-linear>
    <div class="alert alert-danger" ng-if="persons.error">
        <strong>There was an error: </strong> {{persons.error.error}}
        <br>Please go back and login again
    </div>
    <md-whiteframe class="md-whiteframe-1dp" ng-if="person.debt">
        <md-list-item>
            <md-icon md-svg-icon="alert" class="md-warn"></md-icon>
            <div class="md-list-item-text" flex>
                <p>Долг</p>
            </div>
            <md-text-float class="md-secondary">{{person.debt}} р.</md-text-float>
        </md-list-item>
    </md-whiteframe>
    <md-whiteframe class="md-whiteframe-1dp" ng-if="person.books">
        <md-list flex>
            <md-subheader ng-show="person.current_books_price">Книги на руках (на {{person.current_books_price}} р.)</md-subheader>
            <md-list-item ng-repeat="book in person.books">
                <md-icon md-svg-icon="library-books" class="md-primary md-hue-1"></md-icon>
                <div class="md-list-item-text" flex>
                    <p>{{book.name}}</p>
                </div>
                <md-text-float class="md-secondary">{{book[0]}}</md-text-float>
            </md-list-item>
        </md-list>
    </md-whiteframe>
    <md-whiteframe class="md-whiteframe-1dp">
        <md-list flex>
            <md-subheader>Операции</md-subheader>
            <md-list-item class="md-3-line" ng-repeat="os in person.osgrp" layout="row">
                <md-icon md-svg-icon="{{opIcon[os.type]}}" class="md-accent"></md-icon>
                <div class="md-list-item-text" flex>
                    <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;"><span ng-repeat="book in os.books_distr">{{book.shortname?book.shortname:book.name}} <span class="md-caption caption-top-align" md-colors="{color:'grey'}">{{book.o}}</span><span md-colors="{color:'grey'}" ng-if="!$last"> &bullet; </span></span></span></h3>
                    <h4 md-colors="{color:'grey-700'}">
                        <md-icon md-svg-icon="small:library-books" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{os.total_books}}&nbsp;&nbsp;
                        <md-icon md-svg-icon="small:star" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{os.total_points}}&nbsp;&nbsp;
                        <md-icon md-svg-icon="small:currency-rub" class="s12" md-colors="{color:'grey-500'}"></md-icon><md-icon md-svg-icon="small:arrow-up" class="s12" md-colors="{color:'grey-500'}"></md-icon> {{os.total_gain}}</h4>
                    <p md-colors="{color:'grey'}">{{os.description ? os.description : '&nbsp;'}}</p>
                    <md-divider></md-divider>
                </div>
                <md-text-float class="md-secondary md-caption" md-colors="{color:'grey'}">{{os.date | date:'d MMM yy'}}</md-text-float>

            </md-list-item>
        </md-list>
    </md-whiteframe>
</md-content>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-around center">
        <md-button aria-label="{{item}}" class="md-icon-button" ui-sref="make({id:person.id})" ng-repeat="item in ['inbox', 'currency-rub', 'checkbox-marked-circle', 'account-switch', 'minus']">
            <md-icon md-svg-icon="{{item}}"></md-icon>
        </md-button>
    </div>
</md-toolbar>
</div>