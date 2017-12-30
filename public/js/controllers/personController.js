(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('PersonController', PersonController);

    function PersonController($http, $auth, $rootScope, $scope, $state, $stateParams) {

        var vm = this;

        $scope.isLoading = true;
        $scope.person;
        $scope.opIcon = {
            'remain': 'checkbox-marked-circle'
        };

        $http.get('admin/persons/show/'+$stateParams.id).then(function(person) {
            $scope.person = person.data;
            $scope.isLoading = false;
        }, function(error) {
            $scope.error = error;
            $scope.isLoading = false;
        });

        $scope.$on('operation', function(event, data) {
            switch(data.num) {
                case '1': $state.go('make', { id: $scope.person.id}); break;
            }
        });
    }

})();