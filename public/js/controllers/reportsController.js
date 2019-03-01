(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('ReportsController', ReportsController);

    function ReportsController($http, $auth, $rootScope, $scope, $state, $filter) {

        var vm = this;
        $scope.showInfo = false;

        $scope.totalProp = function(prop) {
            var total = 0;
            for(var k in $rootScope.reports) {
                if($rootScope.reports[k].compiled)
                    total += $rootScope.reports[k][prop];
            }
            return total;
        }

        $scope.lastProp = function(prop) {
            for(var k in $rootScope.reports) {
                if($rootScope.reports[k].compiled)
                    return $rootScope.reports[k][prop];
            }
            return 0;
        }
    }

})();