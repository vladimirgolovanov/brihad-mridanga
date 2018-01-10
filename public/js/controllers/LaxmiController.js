(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('LaxmiController', LaxmiController);

    function LaxmiController($http, $auth, $scope, $rootScope, $log, $state, $stateParams, $filter, $mdBottomSheet) {

        var self = this;

        $scope.submit = submit;
        $scope.submiting = false;
        $scope.del = del;

        $scope.id = $stateParams.id;
        $scope.op = null;

        $scope.date = new Date();
        $scope.lastdate = $rootScope.lastdate;
        $scope.setDateToLast = setDateToLast;
        $scope.dateIsOpen = false;

        $scope.payed = null;

        $scope.showDescr = showDescr;
        $scope.descr = '';
        $scope.descrPromise = null;
        $scope.descrIsOpen = false;

        if($stateParams.op) {
            $scope.op = $stateParams.op;
            $scope.isLoading = true;
            $http.get('admin/operation/show/'+$stateParams.op).then(function(result) {
                $scope.payed = result.data.payed;
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

        $scope.$on('submit', function(event, data) {
            if(!$scope.descrIsOpen && $scope.books.length) {
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
            $scope.payed = null;
            $scope.submit();
        }
        function submit() {
            $rootScope.lastdate = $scope.date;
            var postdata = { 'datetime': $scope.op, 'operation_type': '2', 'id': $scope.id, 'date': $filter('date')($scope.date, 'yyyy-MM-dd'), 'payed': $scope.payed, 'descr':$scope.descr };
            $http.post('admin/operation', postdata).then(function(response) {
                $state.go('person', {'id': $scope.id});
            }, function(response) {

            });
            $scope.submiting = true;
        }
    }
})();
