(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('MakeController', MakeController);

    function MakeController($http, $auth, $scope, $rootScope, $log, $state, $stateParams) {

        $rootScope.title = 'Issue books';

        var self = this;
        self.simulateQuery = false;
        self.isDisabled    = false;
        self.querySearch   = querySearch;
        self.selectedItemChange = selectedItemChange;
        self.keyUppp = keyUppp;
        self.searchTextChange   = searchTextChange;
        self.newState = newState;
        self.showSearch = 0;
        self.setFocus = setFocus;
        self.totalPrice = totalPrice;
        self.totalQty = totalQty;
        self.totalPoints = totalPoints;
        self.submit = submit;

        self.selectedId = null;
        self.selectedName = null;
        self.selectedShortname = null;
        self.selectedQty = null;
        self.selectedPrice = null;
        self.selectedPoints = null;

        self.books = [];

        self.id = $stateParams.id;

        $scope.date = new Date();

        $scope.$watch('date', function(newVal, oldVal) {
            if(newVal && oldVal && (newVal.getDate()+newVal.getMonth()+newVal.getYear() != oldVal.getDate()+oldVal.getMonth()+oldVal.getYear())) {
                self.showSearch = 0;
                self.setFocus();
            }
        });

        function submit() {
            self.showSearch = 3;
        }
        function newState(state) {
            alert("Sorry! You'll need to create a Constituion for " + state + " first!");
        }
        // ******************************
        // Internal methods
        // ******************************
        /**
         * Search for states... use $timeout to simulate
         * remote dataservice call.
         */
        function querySearch (query) {
            if(query) {
                var bks = $rootScope.books.map(createFilterFor(query));
                return bks.filter(function(value) {
                    return value !== null;
                });
            } else return null;
        }
        function searchTextChange(text) {
        }
        function selectedItemChange(item) {
            self.selectedId = item.id;
            self.selectedName = item.name;
            self.selectedShortname = item.shortname;
            self.selectedQty = '';
            self.selectedPrice = item.price;
            self.selectedPoints = item.book_type == 0 ? 0 : item.book_type == 1 ? 2 : item.book_type == 2 ? 1 : item.book_type == 3 ? 0.5 : item.book_type == 4 ? 0.25 : 0;
            self.showSearch = 1;
        }
        function keyUppp(event) {
            if(event.keyCode == 13) {
                if(self.showSearch == 1) {
                    self.showSearch = 2;
                } else if(self.showSearch == 2) {
                    self.books.unshift({ id: self.selectedId, name: self.selectedName, qty: self.selectedQty, price: self.selectedPrice, points: self.selectedPoints});
                    self.showSearch = 0;
                    self.searchText = '';
                    self.setFocus();
                }
                event.preventDefault();
            }
        }
        function setFocus() {
            setTimeout(function() {
                document.querySelector("#autoCompleteId").focus();
            }, 0);
        }
        function totalQty() {
            return self.books.reduce(function(total, cur) {
                return total + (cur.qty?parseInt(cur.qty):0);
            }, 0);
        }
        function totalPoints() {
            return self.books.reduce(function(total, cur) {
                return total + cur.points * cur.qty;
            }, 0);
        }
        function totalPrice() {
            return self.books.reduce(function(total, cur) {
                return total + cur.qty * cur.price;
            }, 0);
        }
        /**
         * Create filter function for a query string
         */
        function createFilterFor(query) {
            var lowercaseQuery = angular.lowercase(query);
            return function filterFn(state) {
                var lowercaseState = angular.lowercase(state.shortname+state.name);
                var idx = lowercaseState.indexOf(lowercaseQuery);
                if(idx !== -1) {
                    state.orderby = idx;
                    return state;
                } else return null;
            };
        }
    }
})();