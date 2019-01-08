(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('PersongroupsController', PersongroupsController);

    function PersongroupsController($http, $auth, $rootScope, $scope, $state) {

        $scope.$on('back', function(event, data) {
            $state.go('persons');
        });

    }

})();