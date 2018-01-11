(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('BookgroupController', BookgroupController);

    function BookgroupController($http, $auth, $rootScope, $scope, $state, $stateParams, $filter) {

        $scope.submit = submit;
        $scope.submiting = false;

        $rootScope.$watch('isLoadingBooks', function(newVal, oldVal) {
            if(!newVal) {
                if($stateParams.id) $scope.bookgroup = $filter('filter')($rootScope.bookgroups, {id: $stateParams.id})[0];
                else $scope.bookgroup = {
                    name: ''
                };
            }
        });

        $scope.$on('back', function(event, data) {
            $state.go('bookgroups');
        });

        function submit() {
            $http.post('admin/bookgroup', $scope.bookgroup).then(function(response) {
                if(!$scope.bookgroup.id) {
                    $scope.bookgroup = response.data;
                    $rootScope.bookgroups.push($scope.bookgroup);
                    $rootScope.showMessage('Book group was created!');
                } else {
                    $rootScope.showMessage('Book group was updated!');
                }
                $state.go('bookgroups');
            }, function(response) {

            });
            $scope.submiting = true;
        }

    }

})();