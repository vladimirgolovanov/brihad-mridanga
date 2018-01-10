(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('DescrController', DescrController);

    function DescrController($scope, $mdBottomSheet, descr) {

        $scope.descr = descr;
        $scope.submit = function() {
            $mdBottomSheet.hide($scope.descr);
        };

    }

})();