<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ui-sref="persons" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <h2 flex>Person groups</h2>
    </div>
</md-toolbar>
<md-content flex layout="row" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingPersons" class="md-accent" md-diameter="20px"></md-progress-linear>
    <md-list flex ng-hide="isLoadingPersons">
        <div ng-repeat="pg in persongroups | orderBy:'name' | toArray:true">
            <md-list-item ui-sref="persongroup({id:pg.id})">
                <p>{{pg.name}}</p>
            </md-list-item>
        </div>
    </md-list>
</md-content>
</div>
<md-button ui-sref="persongroup" class="md-fab md-fab-bottom-right" aria-label="Add person group">
    <md-icon md-svg-icon="plus""></md-icon>
</md-button>
