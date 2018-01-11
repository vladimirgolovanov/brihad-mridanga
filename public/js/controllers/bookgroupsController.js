(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('BookgroupsController', BookgroupsController);

    function BookgroupsController($http, $auth, $rootScope, $scope, $state) {

        $scope.$on('back', function(event, data) {
            $state.go('books');
        });

    }

})();