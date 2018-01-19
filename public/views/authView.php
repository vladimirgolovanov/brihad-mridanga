<div layout="row" layout-align="center center" layout-fill>
    <md-card class="md-whiteframe-z1" flex="100" flex-gt-xs="70" flex-gt-sm="50" flex-gt-md="33" layout-padding>
        <md-toolbar class="md-accent md-hue-1">
            <div class="md-toolbar-tools">
                <h2>Brihad Mridanga Login!</h2>
            </div>
        </md-toolbar>
        <md-card-content>
            <form>
                <md-input-container class="md-block">
                    <label>Email</label>
                    <input type="email" ng-model="auth.email" eopd-enter="auth.login()">
                </md-input-container>
                <md-input-container class="md-block">
                    <label>Password</label>
                    <input ng-model="auth.password" type="password" eopd-enter="auth.login()">
                </md-input-container>
                <md-input-container class="md-block">
                    <div layout="row" layout-margin>
                        <md-button class="md-raised" flex ng-click="auth.login()">Login</md-button>
                    </div>
                </md-input-container>
            </form>
        </md-card-content>
    </md-card>
</div>
<!--<div flex layout="column" layout-align="center center">-->
<!--    <div>-->
<!--        <h3>Login</h3>-->
<!--        <form>-->
<!--            <div class="form-group">-->
<!--                <input type="email" class="form-control" placeholder="Email" ng-model="auth.email">-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <input type="password" class="form-control" placeholder="Password" ng-model="auth.password">-->
<!--            </div>-->
<!--            <button class="btn btn-primary" ng-click="auth.login()">Submit</button>-->
<!--        </form>-->
<!--    </div>-->
<!--</div>-->