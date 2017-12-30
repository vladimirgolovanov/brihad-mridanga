(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('BooksController', BooksController);

    function BooksController($http, $auth, $rootScope, $scope, $state) {

        var vm = this;

        $scope.searchQ = '';
        $scope.showSearch = false;
        $scope.searchIsFocused = false;
        $scope.toggleSearch = function() {
            $scope.showSearch = !$scope.showSearch;
        };

        $scope.$on('charPressed', function(event, data) {
            $scope.showSearch = true;
            if(!$scope.searchIsFocused) {
                $scope.searchQ += data.char;
            }
        });

    }

})();