(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('MakeController', MakeController);

    function MakeController($http, $auth, $scope, $rootScope, $log, $state, $stateParams, $filter, $mdBottomSheet, $timeout) {

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
        $scope.canSubmit = canSubmit;
        $scope.submiting = false;
        $scope.del = del;
        $scope.pay = pay;

        $scope.selectedId = null;
        $scope.selectedGroup = null;
        $scope.selectedName = null;
        $scope.selectedShortname = null;
        $scope.selectedQty = null;
        $scope.selectedPrice = null;
        $scope.selectedPriceBuy = null;
        $scope.selectedPoints = null;

        $scope.books = [];

        $scope.id = $stateParams.id;
        $scope.op = null;

        $scope.date = new Date();
        $scope.lastdate = $rootScope.lastdate;
        $scope.setDateToLast = setDateToLast;
        $scope.dateIsOpen = false;

        $scope.delete = 0;

        $scope.textToCopy = "";
        $scope.clipboardSupported = false;
        $scope.clipboardSuccess = clipboardSuccess;
        $scope.clipboardCopied = false;

        if($stateParams.op) {
            $scope.op = $stateParams.op;
            $scope.isLoading = true;
            $http.get('admin/operation/show/'+$stateParams.op).then(function(result) {
                $scope.books = result.data.books.map(function(item) {
                    item.points = item.book_type == 0 ? 0 : item.book_type == 1 ? 2 : item.book_type == 2 ? 1 : item.book_type == 3 ? 0.5 : item.book_type == 4 ? 0.25 : 0
                    return item;
                });
                $scope.descr = result.data.descr;
                $scope.date = new Date(result.data.date);
                $scope.isLoading = false;
            }, function(error) {
                $rootScope.showMessage(error.statusText, 'error');
                $scope.isLoading = false;
                $scope.op = null;
            });
        } else {
            $scope.isLoading = false;
        }

        $scope.showPayed = false;
        $scope.payed = null;

        $scope.showDescr = showDescr;
        $scope.descr = '';
        $scope.descrPromise = null;
        $scope.descrIsOpen = false;

        $scope.$watch('date', function(newVal, oldVal) {
            if(newVal && oldVal && (newVal.getDate()+newVal.getMonth()+newVal.getYear() != oldVal.getDate()+oldVal.getMonth()+oldVal.getYear())) {
                $scope.showSearch = 0;
                $scope.setFocus();
            }
        });

        $scope.$watch('books', function(newVal, oldVal) {
            var text = "";
            var totalPrice = 0;
            for(var k in $scope.books) {
                totalPrice += ($scope.books[k].qty?parseInt($scope.books[k].qty):0) * $scope.books[k].price;
                text += $scope.books[k].name + "\t" + $scope.books[k].qty + " x " + $scope.books[k].price + " р. = " + $scope.books[k].qty*$scope.books[k].price + " р.\n";
            }
            text += "Итого: " + totalPrice + " р.";
            $scope.textToCopy = text;
        });

        $scope.$on('submit', function(event, data) {
            if(!$scope.descrIsOpen && $scope.canSubmit()) {
                $scope.submit();
            }
        });

        $scope.$on('date', function(event, data) {
            if($scope.date == $scope.lastdate) {
                $scope.dateIsOpen = true;
            } else $scope.setDateToLast();
        });

        $scope.$on('descr', function(event, data) {
            showDescr();
        });

        $scope.$on('back', function(event, data) {
            $state.go('person', {id:$scope.id});
        });

        $scope.$on('payed', function(event, data) {
            pay();
        });

        function clipboardSuccess() {
            $scope.clipboardCopied = true;
            $timeout(function() {
                $scope.clipboardCopied = false;
            }, 1000);
        }

        function showDescr() {
            $scope.descrIsOpen = true;
            $scope.descrPromise = $mdBottomSheet.show({
                templateUrl: '/views/descrTemplate.php',
                controller: 'DescrController',
                locals:{descr:$scope.descr}
            }).then(function(descr) {
                $scope.descr = descr;
                $scope.descrIsOpen = false;
            }).catch(function() {
                $scope.descrIsOpen = false;
            });
        }

        function pay() {
            $scope.showPayed = true;
            $scope.payed = $scope.totalPrice();
        }
        function setDateToLast() {
            $scope.date = $scope.lastdate;
        }
        function del() {
            $scope.payed = null;
            $scope.books = [];
            $scope.submit();
        }
        function canSubmit() {
            return $scope.form.$valid && $scope.totalQty() && !$scope.submiting && !$scope.isLoading;
        }
        function submit() {
            $rootScope.lastdate = $scope.date;
            var postdata = { 'datetime': $scope.op, 'operation_type': '1', 'id': $scope.id, 'date': $filter('date')($scope.date, 'yyyy-MM-dd'), 'books': $scope.books, 'payed': $scope.payed, 'descr':$scope.descr };
            $http.post('admin/operation', postdata).then(function(response) {
                $state.go('person', {'id': $scope.id});
            }, function(response) {

            });
            $scope.submiting = true;
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
                var obj = {'book_ids': $scope.books.map(function(item) {
                    return item.id;
                })};
                var bks = $rootScope.books.map(createFilterFor(query), obj);
                return bks.filter(function(value) {
                    return value !== null;
                });
            } else return null;
        }
        function searchTextChange(text) {
        }
        function selectedItemChange(item) {
            $scope.selectedId = item.id;
            $scope.selectedGroup = item.bookgroup_name;
            $scope.selectedName = item.name;
            $scope.selectedShortname = item.shortname;
            $scope.selectedQty = '';
            $scope.selectedPrice = item.price;
            $scope.selectedPriceBuy = item.price_buy;
            $scope.selectedPoints = item.book_type == 0 ? 0 : item.book_type == 1 ? 2 : item.book_type == 2 ? 1 : item.book_type == 3 ? 0.5 : item.book_type == 4 ? 0.25 : 0;
            $scope.showSearch = 1;
        }
        function keyUppp(event) {
            if(event.keyCode == 13 || event.keyCode == 9) {
                if($scope.showSearch == 1) {
                    $scope.showSearch = 2;
                } else if($scope.showSearch == 2) {
                    $scope.books.unshift({ id: $scope.selectedId, name: $scope.selectedName, shortname: $scope.selectedShortname, bookgroup_name: $scope.selectedGroup, qty: $scope.selectedQty, price: $scope.selectedPrice, price_buy: $scope.selectedPriceBuy, points: $scope.selectedPoints});
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
            $timeout(function() {
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
                if(this.book_ids.indexOf(state.id) != -1) return null;
                var lowercaseState = angular.lowercase(state.shortname+':'+state.name);
                var idx = lowercaseState.indexOf(lowercaseQuery);
                if(idx !== -1) {
                    state.orderby = idx;
                    return state;
                } else return null;1
            };
        }

        setFocus();
    }
})();