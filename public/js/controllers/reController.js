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

        $scope.empty = false;

        $scope.date = {prop:''}; // Hook to make md-select work

        $rootScope.$watch('isLoadingReports', function(newVal, oldVal) {
            if(!newVal) {
                if($state.current.data.optype == 'remains') {
                    if(!$rootScope.reports[0].compiled) {
                        $scope.date.prop = $rootScope.reports[0].custom_date;
                    }
                }
            }
        });

        if($stateParams.op) {
            $scope.op = $stateParams.op;
            $http.get('admin/operation/show/'+$stateParams.op).then(function(result) {
                $scope.books = result.data.books;
                $scope.descr = result.data.descr;
                $scope.date.prop = $scope.optype == 'remains'?result.data.date:new Date(result.data.date);
                $scope.isLoading = false;
                $scope.setFocus();
            }, function(error) {
                $rootScope.showMessage(error.statusText, 'error');
                $scope.isLoading = false;
                $scope.op = null;
            });
        } else {
            if($state.current.data.optype != 'remains') {
                $scope.date.prop = new Date();
                $scope.getCurrentBooksByDate();
            }
            $scope.setFocus();
        }

        $scope.payed = null;

        $scope.showDescr = showDescr;
        $scope.descr = '';
        $scope.descrPromise = null;
        $scope.descrIsOpen = false;

        $scope.$watch('date.prop', function(newVal, oldVal) {
            if(newVal && oldVal && ($scope.optype == 'remains'?(!$scope.op):(newVal.getDate()+newVal.getMonth()+newVal.getYear() != oldVal.getDate()+oldVal.getMonth()+oldVal.getYear()))) {
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
            if($scope.date.prop == $scope.lastdate) {
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
            console.log($rootScope.previousState);
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
            $scope.date.prop = $scope.lastdate;
        }
        function del() {
            $scope.books = [];
            $scope.submit();
        }
        function canSubmit() {
            return $scope.form.$valid && ($scope.optype == 'remains' || $scope.totalQty()) && !$scope.submiting && !$scope.isLoading && ($scope.optype != 'exchange' || $scope.exchangeId);
        }
        function submit() {
            $rootScope.lastdate = $scope.date.prop;
            var postdata = { 'empty': $scope.empty, 'datetime': $scope.op, 'operation_type': $scope.optypeNum, 'id': $scope.id, 'date': ($scope.optype == 'remains'?$scope.date.prop:$filter('date')($scope.date.prop, 'yyyy-MM-dd')), 'books': $scope.books, 'descr':$scope.descr, 'exchange_id':$scope.exchangeId };
            $http.post('admin/operation', postdata).then(function(response) {
                if($scope.optype == 'exchange') {
                    $rootScope.refreshPersonById($scope.exchangeId);
                } else if($scope.optype == 'remains') {
                    $rootScope.getReportById($scope.date.prop).persons.filter(el => el.id == $scope.id)[0].no_remains = false;
                    console.log($rootScope.reports);
                }
                if($rootScope.previousState == 'report') {
                    $state.go('report', $rootScope.previousParams);
                } else {
                    $state.go('person', {'id': $scope.id});
                }
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
            $http.get('admin/persons/show/'+$stateParams.id+'/currentbooks/'+($scope.optype == 'remains'?$scope.date.prop:$filter('date')($scope.date.prop, 'yyyy-MM-dd'))).then(function(result) {
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