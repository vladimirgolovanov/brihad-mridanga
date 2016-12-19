(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('PersonController', PersonController);

    function PersonController($http, $auth, $rootScope, $state, $stateParams) {

        var vm = this;
        vm.isLoading = true;
        vm.person;

        $http.get('admin/persons/show/'+$stateParams.id).success(function(person) {
            vm.person = person;
            vm.isLoading = false;
        }).error(function(error) {
            vm.error = error;
            vm.isLoading = false;
        });
    }

})();