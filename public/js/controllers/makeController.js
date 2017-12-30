(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('MakeController', MakeController);

    function MakeController($http, $auth, $scope, $rootScope, $log, $state, $stateParams, $filter) {

        $rootScope.title = 'Issue books';

        var self = this;

        $scope.simulateQuery = false;
        $scope.isDisabled    = false;
        $scope.querySearch   = querySearch;
        $scope.selectedItemChange = selectedItemChange;
        $scope.keyUppp = keyUppp;
        $scope.searchTextChange   = searchTextChange;
        $scope.newState = newState;
        $scope.showSearch = 0;
        $scope.setFocus = setFocus;
        $scope.totalPrice = totalPrice;
        $scope.totalQty = totalQty;
        $scope.totalPoints = totalPoints;
        $scope.submit = submit;

        $scope.selectedId = null;
        $scope.selectedName = null;
        $scope.selectedShortname = null;
        $scope.selectedQty = null;
        $scope.selectedPrice = null;
        $scope.selectedPriceBuy = null;
        $scope.selectedPoints = null;

        $scope.books = [];

        $scope.id = $stateParams.id;

        $scope.date = new Date();
        $scope.lastdate = $rootScope.lastdate;
        $scope.setDateToLast = setDateToLast;

        $scope.$watch('date', function(newVal, oldVal) {
            if(newVal && oldVal && (newVal.getDate()+newVal.getMonth()+newVal.getYear() != oldVal.getDate()+oldVal.getMonth()+oldVal.getYear())) {
                $scope.showSearch = 0;
                $scope.setFocus();
            }
        });

        $scope.$watch('showSearch', function(newVal, oldVal) {
            console.log(newVal);
        });

        function setDateToLast() {
            $scope.date = $scope.lastdate;
        }
        function submit() {
            $rootScope.lastdate = $scope.date;
            var postdata = { 'operation_type': '1', 'id': $scope.id, 'date': $filter('date')($scope.date, 'yyyy-MM-dd'), 'books': $scope.books };
            $http.post('admin/operation', postdata).then(function(response) {
                $state.go('person', {'id': $scope.id});
            }, function(response) {

            });
            $scope.showSearch = 3;
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
            $scope.selectedId = item.id;
            $scope.selectedName = item.name;
            $scope.selectedShortname = item.shortname;
            $scope.selectedQty = '';
            $scope.selectedPrice = item.price;
            $scope.selectedPriceBuy = item.price_buy;
            $scope.selectedPoints = item.book_type == 0 ? 0 : item.book_type == 1 ? 2 : item.book_type == 2 ? 1 : item.book_type == 3 ? 0.5 : item.book_type == 4 ? 0.25 : 0;
            $scope.showSearch = 1;
        }
        function keyUppp(event) {
            if(event.keyCode == 13) {
                if($scope.showSearch == 1) {
                    $scope.showSearch = 2;
                } else if($scope.showSearch == 2) {
                    $scope.books.unshift({ id: $scope.selectedId, name: $scope.selectedName, qty: $scope.selectedQty, price: $scope.selectedPrice, price_buy: $scope.selectedPriceBuy, points: $scope.selectedPoints});
                    $scope.showSearch = 0;
                    $scope.searchText = '';
                    $scope.setFocus();
                }
                event.preventDefault();
            }
            if(event.keyCode == 27) {
                $scope.showSearch = 0;
                $scope.searchText = '';
                $scope.setFocus();
                event.preventDefault();
            }
        }
        function setFocus() {
            setTimeout(function() {
                document.querySelector("#autoCompleteId").focus();
            }, 0);
        }
        function totalQty() {
            return $scope.books.reduce(function(total, cur) {
                return total + (cur.qty?parseInt(cur.qty):0);
            }, 0);
        }
        function totalPoints() {
            return $scope.books.reduce(function(total, cur) {
                return total + cur.points * cur.qty;
            }, 0);
        }
        function totalPrice() {
            return $scope.books.reduce(function(total, cur) {
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