(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('PersonsController', PersonsController);

    function PersonsController($http, $auth, $rootScope, $state) {

        var vm = this;
        vm.personsOrderBy = 'name';
        vm.personsReverse = false;

        vm.personsOrder = function(parm) {
            vm.personsOrderBy = parm;
            vm.personsReverse = (parm == 'debt');
        };

        //vm.persons;
        //vm.error;
        //vm.isLoading = true;
        //
        //$http.get('admin/persons').success(function(persons) {
        //    vm.persons = persons;
        //    vm.isLoading = false;
        //}).error(function(error) {
        //    vm.error = error;
        //    vm.isLoading = false;
        //});
    }

})();