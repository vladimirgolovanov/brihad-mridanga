<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="persons" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex>
            <h2>{{personctrl.person.name}}</h2>
        </div>
        <md-button class="md-icon-button" ui-sref="persons" aria-label="Back">
            <md-icon md-svg-icon="dots-vertical"></md-icon>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="row" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="person.isLoading" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex>
        <md-list-item>
            <md-icon md-svg-icon="alert" class="md-warn"></md-icon>
            <div class="md-list-item-text" flex>
                <p>Долг</p>
            </div>
            <md-text-float class="md-secondary">1500 р.</md-text-float>
        </md-list-item>
        <md-divider></md-divider>
        <md-subheader ng-show="personctrl.person.current_books_price">Книги на руках (на {{personctrl.person.current_books_price}} р.)</md-subheader>
        <md-list-item ng-repeat="book in personctrl.person.books">
            <md-icon md-svg-icon="book" class="md-primary md-hue-1"></md-icon>
            <div class="md-list-item-text" flex>
                <p>{{book.name}}</p>
            </div>
            <md-text-float class="md-secondary">{{book[0]}}</md-text-float>
        </md-list-item>
        <md-divider></md-divider>
        <md-subheader>Операции</md-subheader>
        <md-list-item class="md-3-line" ng-repeat="os in personctrl.person.osgrp" layout="row">
            <md-icon md-svg-icon="check-circle-outline" class="md-primary"></md-icon>
            <div class="md-list-item-text" flex>
                <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;">@ <span ng-repeat="book in os.books_distr">{{book.text}} </span></h3>
                <h4 md-colors="{color:'grey-700'}">
                    <md-icon md-svg-icon="small:library-books" class="s12" md-colors="{color:'grey-500'}"></md-icon> 238&nbsp;&nbsp;
                    <md-icon md-svg-icon="small:star" class="s12" md-colors="{color:'grey-500'}"></md-icon> 473.5&nbsp;&nbsp;
                    <md-icon md-svg-icon="small:currency-rub" class="s12" md-colors="{color:'grey-500'}"></md-icon> 1450</h4>
                <p md-colors="{color:'grey'}">{{os.description ? os.description : '&nbsp;'}}</p>
            </div>
            <md-text-float class="md-secondary md-caption" md-colors="{color:'grey'}">16 Oct</md-text-float>
        </md-list-item>
        <div class="alert alert-danger" ng-if="persons.error">
            <strong>There was an error: </strong> {{persons.error.error}}
            <br>Please go back and login again
        </div>
    </md-list>
</md-content>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-around center">
        <md-button class="md-icon-button" ui-sref="make({id:personctrl.person.id})" ng-repeat="item in ['inbox', 'currency-rub', 'checkbox-marked-circle', 'account-switch', 'minus']">
            <md-icon md-svg-icon="{{item}}"></md-icon>
        </md-button>
    </div>
</md-toolbar>
</div>