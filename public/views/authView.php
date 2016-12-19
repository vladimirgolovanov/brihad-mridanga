<div layout="row" layout-align="center center" layout-fill>
    <md-whiteframe class="md-whiteframe-z1" flex="70" layout-padding>
        <form>
            <md-content layout="column">
                <md-input-container>
                    <label>Email</label>
                    <input type="email" ng-model="auth.email">
                </md-input-container>
                <md-input-container>
                    <label>Password</label>
                    <input ng-model="auth.password" type="password">
                </md-input-container>
                <md-input-container layout-align="center center">
                    <div layout="row" layout-margin>
                        <md-button class="md-raised" flex ng-click="auth.login()">Login</md-button>
                    </div>
                </md-input-container>
            </md-content>
        </form>
    </md-whiteframe>
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