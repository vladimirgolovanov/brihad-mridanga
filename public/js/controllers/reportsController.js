(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('ReportsController', ReportsController);

    function ReportsController($http, $auth, $rootScope, $scope, $state, $filter) {

        var vm = this;

        $scope.totalProp = function(prop) {
            var total = 0;
            for(var k in $rootScope.reports) {
                total += $rootScope.reports[k][prop];
            }
            return total;
        }

    }

})();