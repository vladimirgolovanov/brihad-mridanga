(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('PersonController', PersonController);

    function PersonController($http, $auth, $rootScope, $state, $stateParams) {

        var vm = this;
        vm.isLoading = true;
        vm.person;
        vm.opIcon = {
            'remain': 'checkbox-marked-circle'
        };

        $http.get('admin/persons/show/'+$stateParams.id).then(function(person) {
            vm.person = person.data;
            vm.isLoading = false;
        }, function(error) {
            vm.error = error;
            vm.isLoading = false;
        });
    }

})();