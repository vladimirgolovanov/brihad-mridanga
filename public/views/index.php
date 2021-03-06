<html>
<head>
    <title>Brihad Mridanga</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/bower_components/angular-material/angular-material.min.css">
    <link rel="stylesheet" href="/static/style.css">
    <link rel="stylesheet" href="/static/fonts.css">
    <base href="/" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

</head>
<body ng-app="bmApp" ng-controller="MainCtrl" layout="row" ng-keypress="keyPressEvent($event)" ng-mousemove="mouseMove($event)">
    <md-sidenav layout="column" ng-show="authenticated" class="md-sidenav-left md-whiteframe-z2 animate" md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')">
        <md-toolbar class="md-accent md-hue-1" style="background:url(/static/app-name-bg.png) -45px center no-repeat">
            <div class="md-toolbar-tools">
                <h2 style="margin-left:70px;">Brihad Mridanga</h2>
            </div>
        </md-toolbar>
        <md-list style="background:url(/static/sidenav-bg.png) no-repeat 0px 20px" flex>
            <md-list-item ui-sref="persons" ng-click="toggleSidenav('left')">
                <div>Persons</div>
            </md-list-item>
            <md-list-item ui-sref="books" ng-click="toggleSidenav('left')">
                <div>Books</div>
            </md-list-item>
            <md-list-item ui-sref="reports" ng-click="toggleSidenav('left')">
                <div>Reports</div>
            </md-list-item>
            <md-list-item ui-sref="settings" ng-click="toggleSidenav('left')">
                <div>Settings</div>
            </md-list-item>
            <md-list-item ng-click="toggleSidenav('left');logout();">
                <div>Logout</div>
            </md-list-item>
        </md-list>
    </md-sidenav>
    <div class="container" flex>
        <div ui-view ng-click="angular.noop()"></div>
    </div>

</body>

<script src="/bower_components/angular/angular.min.js"></script>
<script src="/bower_components/angular-animate/angular-animate.min.js"></script>
<script src="/bower_components/angular-aria/angular-aria.min.js"></script>
<script src="/bower_components/angular-messages/angular-messages.min.js"></script>
<script src="/bower_components/angular-jwt/dist/angular-jwt.min.js"></script>
<script src="/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
<script src="/bower_components/satellizer/dist/satellizer.min.js"></script>
<script src="/bower_components/angular-material/angular-material.min.js"></script>
<script src="/bower_components/ng-focus-if/focusIf.min.js"></script>
<script src="/bower_components/angular-filter/dist/angular-filter.min.js"></script>
<script src="/bower_components/moment/moment.js"></script>
<script src="/bower_components/angular-moment/angular-moment.js"></script>
<script src="/bower_components/chart.js/dist/Chart.min.js"></script>
<script src="/bower_components/angular-chart.js/dist/angular-chart.min.js"></script>
<script src="/bower_components/angular-clipboard/angular-clipboard.js"></script>
<script src="/bower_components/angular-long-press/dist/angular-long-press.min.js"></script>
<script src="/bower_components/hammerjs/hammer.js"></script>
<script src="/bower_components/AngularHammer/angular.hammer.min.js"></script>

<script src="/js/app.js"></script>
<script src="/js/dir.js"></script>
<script src="/js/controllers/authController.js"></script>
<script src="/js/controllers/userController.js"></script>
<script src="/js/controllers/personController.js"></script>
<script src="/js/controllers/editPersonController.js"></script>
<script src="/js/controllers/personsController.js"></script>
<script src="/js/controllers/persongroupsController.js"></script>
<script src="/js/controllers/persongroupController.js"></script>
<script src="/js/controllers/makeController.js"></script>
<script src="/js/controllers/reController.js"></script>
<script src="/js/controllers/LaxmiController.js"></script>
<script src="/js/controllers/booksController.js"></script>
<script src="/js/controllers/bookController.js"></script>
<script src="/js/controllers/bookgroupsController.js"></script>
<script src="/js/controllers/bookgroupController.js"></script>
<script src="/js/controllers/reportsController.js"></script>
<script src="/js/controllers/reportController.js"></script>
<script src="/js/controllers/descrController.js"></script>

</html>