<div layout="column" layout-align="center center" layout-fill style="background:url(/static/auth-bg.png) center center no-repeat">
    <img src="/static/bm-logo.png" style="width:250px;height:250px;margin-bottom:10px;">
    <form style="width:250px;" layout="column" layout-align="center stretch">
        <md-input-container class="md-block no-error login-input">
            <label>Email</label>
            <input type="email" ng-model="auth.email" eopd-enter="auth.login()">
        </md-input-container>
        <md-input-container class="md-block login-input">
            <label>Password</label>
            <input ng-model="auth.password" type="password" eopd-enter="auth.login()">
        </md-input-container>
        <md-button class="md-accent md-raised" ng-click="auth.login()">Login</md-button>
    </form>
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