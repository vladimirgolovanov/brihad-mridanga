<md-toolbar>
    <div class="md-toolbar-tools">
        <md-button class="md-icon-button" ng-click="toggleSidenav('left')" hide-gt-md aria-label="Menu">
            <md-icon md-svg-icon="menu"></md-icon>
        </md-button>
        <h2 flex>Persons</h2>
    </div>
</md-toolbar>
<div class="col-sm-6 col-sm-offset-3">
    <div class="well">
        <h5 ng-if="authenticated">Welcome, {{currentUser.name}}</h5>
        <h3>Users</h3>
        <div class="buttons" style="margin-bottom: 10px">
            <button class="btn btn-primary" ng-click="user.getUsers()">Get Users!</button>
            <button class="btn btn-danger" ng-click="logout()">Logout</button>
        </div>
        <ul class="list-group" ng-if="user.users">
            <li class="list-group-item" ng-repeat="user in user.users">
                <h4>{{user.name}}</h4>
                <h5>{{user.email}}</h5>
            </li>
        </ul>
        <div class="alert alert-danger" ng-if="user.error">
            <strong>There was an error: </strong> {{user.error.error}}
            <br>Please go back and login again
        </div>
    </div>
</div>