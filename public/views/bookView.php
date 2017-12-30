<div layout="column" layout-fill>
<md-toolbar>
    <div class="md-toolbar-tools" layout-align="space-between center">
        <md-button class="md-icon-button" ui-sref="books" aria-label="Back">
            <md-icon md-svg-icon="arrow-left"></md-icon>
        </md-button>
        <div flex>
            <h2>Book</h2>
        </div>
        <md-button class="md-icon-button" ng-click="c.submit();" ng-disabled="bookForm.$invalid">
            <md-icon md-svg-icon="check" ng-hide="c.submiting"></md-icon>
            <md-progress-circular ng-show="c.submiting" class="md-hue-2" md-diameter="24px"></md-progress-circular>
        </md-button>
    </div>
</md-toolbar>
<md-content flex layout="column" id="content">
    <md-progress-linear md-mode="indeterminate" ng-show="isLoadingBooks" class="md-accent" md-diameter="20px"></md-progress-linear>
    <form name="bookForm" novalidate layout-margin>
        <div layout="row">
            <md-input-container flex="66">
                <label>Name</label>
                <input required ng-model="c.book.name">
            </md-input-container>

            <md-input-container flex="33">
                <label>Short</label>
                <input ng-model="c.book.shortname">
            </md-input-container>
        </div>
        <div layout="row">
            <md-input-container flex="33">
                <label>Group</label>
                <md-select ng-model="c.book.bookgroup_id">
                    <md-option ng-value=""><em>None</em></md-option>
                    <md-option ng-repeat="bg in bookgroups" ng-value="bg.id">
                        {{bg.name}}
                    </md-option>
                </md-select>
            </md-input-container>

            <md-input-container flex="33">
                <label>Type</label>
                <input type="number" ng-min="0" ng-max="4" required ng-model="c.book.book_type">
            </md-input-container>

            <md-input-container flex="33">
                <label>Pack</label>
                <input type="number" ng-min="0" required ng-model="c.book.pack">
            </md-input-container>
        </div>
        <div layout="row">
            <md-input-container flex="33">
                <label>Buy</label>
                <input type="number" ng-min="0" required ng-model="c.book.price_buy">
            </md-input-container>
            <md-input-container flex="33">
                <label>Distr</label>
                <input type="number" ng-min="0" required ng-model="c.book.price">
            </md-input-container>
            <md-input-container flex="33">
                <label>Shop</label>
                <input type="number" ng-min="0" required ng-model="c.book.price_shop">
            </md-input-container>
        </div>
    </form>
</md-content>
</div>