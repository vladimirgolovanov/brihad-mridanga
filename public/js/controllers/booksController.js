(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('BooksController', BooksController);

    function BooksController($http, $auth, $rootScope, $scope, $state, $filter) {

        var vm = this;

        $scope.searchQ = '';
        $scope.showSearch = false;
        $scope.searchIsFocused = false;
        $scope.toggleSearch = function() {
            $scope.showSearch = !$scope.showSearch;
        };
        $scope.highlightedItem = 0;

        $scope.$on('charPressed', function(event, data) {
            $scope.showSearch = true;
            if(!$scope.searchIsFocused) {
                $scope.searchQ += data.char;
            }
        });

        $scope.$watch('searchQ', function(newVal, oldVal) {
            $scope.highlightedItem = 0;
        });

        $scope.next = 0;

        $scope.$on('arrow', function(event, data) {
            if(data.direction == 'down' || data.direction == 'up') {
                if(data.direction == 'down') {
                    var items = $filter('filter')($filter('orderBy')($rootScope.books, ['bookgroup_id', 'name'], false), $scope.searchQ);
                } else {
                    var items = $filter('filter')($filter('orderBy')($rootScope.books, ['bookgroup_id', 'name'], true), $scope.searchQ);
                }
                $scope.next = 1;
                if($scope.highlightedItem) {
                    $scope.next = 0;
                }
                items.forEach(function(item, i, arr) {
                    if($scope.next == 1) {
                        $scope.highlightedItem = item.id;
                        $scope.next = 2;
                    } else if($scope.next == 0 && $scope.highlightedItem == item.id) $scope.next = 1;
                });
                if($scope.next != 2) $scope.highlightedItem = 0;
            } else if(data.direction == 'enter') {
                if($scope.highlightedItem) $state.go('book', {id: $scope.highlightedItem});
            } else if(data.direction == 'esc') {
                if($scope.showSearch) {
                    $scope.searchQ = '';
//  Пока тут непонятно
//                    $scope.toggleSearch();
                }
            }
        });

    }

})();