<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ui-sref="books" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <h2 flex>Book groups</h2>
    </div>
</md-toolbar>
<md-content flex layout="row" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingBooks" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex ng-hide="isLoadingBooks">
        <div ng-repeat="bg in bookgroups | orderBy:'name' | toArray:true">
            <md-list-item ui-sref="bookgroup({id:bg.id})">
                <p>{{bg.name}}</p>
            </md-list-item>
        </div>
    </md-list>
</md-content>
</div>
<md-button ui-sref="bookgroup" class="md-fab md-fab-bottom-right" aria-label="Add book group">
    <md-icon md-svg-icon="plus""></md-icon>
</md-button>
