<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ng-click="toggleSidenav('left')" hide-gt-sm aria-label="Menu">
            <md-icon md-svg-icon="menu"></md-icon>
        </md-button>
        <h2 flex>Settings</h2>
    </div>
</md-toolbar>
<div class="col-sm-6 col-sm-offset-3">
    <div class="well">
        <h5 ng-if="authenticated">Welcome, {{currentUser.name}}</h5>
        <p>Your ID is {{currentUser.id}}</p>
    </div>
</div>