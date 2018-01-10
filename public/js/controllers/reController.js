(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('ReController', ReController);

    function ReController($http, $auth, $scope, $rootScope, $log, $state, $stateParams, $filter, $mdBottomSheet) {

        switch($state.current.data.optype) {
            case 'return': $scope.optype = 'return'; $scope.optypeNum = 4; break;
            case 'remains': $scope.optype = 'remains'; $scope.optypeNum = 10; break;
            case 'exchange': $scope.optype = 'exchange'; $scope.optypeNum = 4; break;
        }

        var self = this;

        $scope.simulateQuery = false;
        $scope.querySearch   = querySearch;
        $scope.selectedItemChange = selectedItemChange;
        $scope.searchTextChange   = searchTextChange;
        $scope.setFocus = setFocus;

        $scope.submit = submit;
        $scope.canSubmit = canSubmit;
        $scope.submiting = false;
        $scope.del = del;
        $scope.getCurrentBooksByDate = getCurrentBooksByDate;
        $scope.totalQty = totalQty;

        $scope.books = [];

        $scope.id = $stateParams.id;
        $scope.op = null;
        $scope.exchangeId = null;

        $scope.lastdate = $rootScope.lastdate;
        $scope.setDateToLast = setDateToLast;
        $scope.dateIsOpen = false;

        $scope.delete = 0;
        $scope.isLoading = true;

        if($stateParams.op) {
            $scope.op = $stateParams.op;
            $http.get('admin/operation/show/'+$stateParams.op).then(function(result) {
                $scope.books = result.data.books;
                $scope.descr = result.data.descr;
                $scope.date = new Date(result.data.date);
                $scope.isLoading = false;
                $scope.setFocus();
            }, function(error) {
                $rootScope.showMessage(error.statusText, 'error');
                $scope.isLoading = false;
                $scope.op = null;
            });
        } else {
            $scope.date = new Date();
            getCurrentBooksByDate();
            $scope.setFocus();
        }

        $scope.payed = null;

        $scope.showDescr = showDescr;
        $scope.descr = '';
        $scope.descrPromise = null;
        $scope.descrIsOpen = false;

        $scope.$watch('date', function(newVal, oldVal) {
            if(newVal && oldVal && (newVal.getDate()+newVal.getMonth()+newVal.getYear() != oldVal.getDate()+oldVal.getMonth()+oldVal.getYear())) {
                $scope.isLoading = true;
                $scope.getCurrentBooksByDate();
            }
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

        function setDateToLast() {
            $scope.date = $scope.lastdate;
        }
        function del() {
            $scope.books = [];
            $scope.submit();
        }
        function canSubmit() {
            return $scope.form.$valid && $scope.totalQty() && !$scope.submiting && !$scope.isLoading && ($scope.optype != 'exchange' || $scope.exchangeId);
        }
        function submit() {
            $rootScope.lastdate = $scope.date;
            var postdata = { 'datetime': $scope.op, 'operation_type': $scope.optypeNum, 'id': $scope.id, 'date': $filter('date')($scope.date, 'yyyy-MM-dd'), 'books': $scope.books, 'descr':$scope.descr, 'exchange_id':$scope.exchangeId };
            $http.post('admin/operation', postdata).then(function(response) {
                $state.go('person', {'id': $scope.id});
            }, function(response) {

            });
            $scope.submiting = true;
        }
        function totalQty() {
            return Object.keys($scope.books).reduce(function(total, key) {
                return total + ($scope.books[key].qty?parseInt($scope.books[key].qty):0);
            }, 0);
        }
        function getCurrentBooksByDate() {
            $http.get('admin/persons/show/'+$stateParams.id+'/currentbooks/'+$filter('date')($scope.date, 'yyyy-MM-dd')).then(function(result) {
                angular.forEach($scope.books, function(v, k) {
                    if(typeof result.data[k] !== 'undefined') {
                        result.data[k].qty = $scope.books[k].qty;
                    }
                });
                $scope.books = result.data;
                $scope.isLoading = false;
            }, function(error) {
                $rootScope.showMessage(error.statusText, 'error');
                $scope.isLoading = false;
                $scope.op = null;
            });
        }

        function querySearch (query) {
            if(query) {
                var ps = $rootScope.persons.map(createFilterFor(query));
                return ps.filter(function(value) {
                    return value !== null;
                });
            } else return null;
        }
        function searchTextChange(text) {
        }
        function selectedItemChange(item) {
            if(typeof(item) === 'undefined') $scope.exchangeId = null;
            else $scope.exchangeId = item.id;
        }
        function setFocus() {
            setTimeout(function() {
                document.querySelector("#autoCompleteId").focus();
            }, 0);
        }
        function createFilterFor(query) {
            var lowercaseQuery = angular.lowercase(query);
            return function filterFn(state) {
                var lowercaseState = angular.lowercase(state.shortname+':'+state.name);
                var idx = lowercaseState.indexOf(lowercaseQuery);
                if(idx !== -1) {
                    state.orderby = idx;
                    return state;
                } else return null;1
            };
        }

    }
})();