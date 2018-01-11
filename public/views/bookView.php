<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="books" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex>
            <h2>Book</h2>
        </div>
        <md-button class="md-icon-button" ng-click="submit();" ng-disabled="form.$invalid || submiting">
            <md-icon md-svg-icon="check" ng-hide="submiting"></md-icon>
            <md-progress-circular ng-show="submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="column" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingBooks" class="md-accent" md-diameter="20px"></md-progress-linear>
    <form name="form" novalidate layout-margin>
        <div layout="row">
            <md-input-container flex="66">
                <label>Name</label>
                <input required ng-model="book.name">
            </md-input-container>

            <md-input-container flex="33">
                <label>Short</label>
                <input ng-model="book.shortname">
            </md-input-container>
        </div>
        <div layout="row">
            <md-input-container flex="33">
                <label>Group</label>
                <md-select ng-model="book.bookgroup_id">
                    <md-option ng-value=""><em>None</em></md-option>
                    <md-option ng-repeat="bg in bookgroups" ng-value="bg.id">
                        {{bg.name}}
                    </md-option>
                </md-select>
            </md-input-container>

            <md-input-container flex="33">
                <label>Type</label>
                <md-select ng-model="book.book_type">
                    <md-option ng-value="0"><em>None</em></md-option>
                    <md-option ng-value="1">Maha</md-option>
                    <md-option ng-value="2">Big</md-option>
                    <md-option ng-value="3">Middle</md-option>
                    <md-option ng-value="4">Small</md-option>
                </md-select>
            </md-input-container>

            <md-input-container flex="33">
                <label>Pack</label>
                <input type="number" ng-min="0" required ng-model="book.pack">
            </md-input-container>
        </div>
        <div layout="row">
            <md-input-container flex="33">
                <label>Buy</label>
                <input type="number" ng-min="0" required ng-model="book.price_buy">
            </md-input-container>
            <md-input-container flex="33">
                <label>Distr</label>
                <input type="number" ng-min="0" required ng-model="book.price">
            </md-input-container>
            <md-input-container flex="33">
                <label>Shop</label>
                <input type="number" ng-min="0" required ng-model="book.price_shop">
            </md-input-container>
        </div>
    </form>
</md-content>
</div>