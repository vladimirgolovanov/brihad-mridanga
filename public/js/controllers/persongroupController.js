(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('PersongroupController', PersongroupController);

    function PersongroupController($http, $auth, $rootScope, $scope, $state, $stateParams, $filter) {

        $scope.submit = submit;
        $scope.submiting = false;

        $rootScope.$watch('isLoadingPersons', function(newVal, oldVal) {
            if(!newVal) {
                if($stateParams.id) $scope.persongroup = $filter('filter')($rootScope.persongroups, {id: $stateParams.id})[0];
                else $scope.persongroup = {
                    name: ''
                };
            }
        });

        $scope.$on('back', function(event, data) {
            $state.go('persongroups');
        });

        function submit() {
            $http.post('admin/persongroup', $scope.persongroup).then(function(response) {
                if(!$scope.persongroup.id) {
                    $scope.persongroup = response.data;
                    $rootScope.persongroups.push($scope.persongroup);
                    $rootScope.showMessage('Person group was created!');
                } else {
                    $rootScope.showMessage('Person group was updated!');
                }
                $state.go('persongroups');
            }, function(response) {

            });
            $scope.submiting = true;
        }

    }

})();