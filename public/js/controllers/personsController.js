(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('PersonsController', PersonsController);

    function PersonsController($http, $scope, $auth, $rootScope, $state, $filter) {

        var vm = this;

        $scope.personsOrderBy = 'name';
        $scope.personsReverse = false;

        $scope.personsOrder = function(parm) {
            $scope.personsOrderBy = parm;
            $scope.personsReverse = (parm == 'debt');
        };

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
                    var items = $filter('filter')($filter('orderBy')($rootScope.persons, $scope.personsOrderBy, $scope.personsReverse), $scope.searchQ);
                } else {
                    var items = $filter('filter')($filter('orderBy')($rootScope.persons, $scope.personsOrderBy, !$scope.personsReverse), $scope.searchQ);
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
                if($scope.highlightedItem) $state.go('person', {id: $scope.highlightedItem});
            } else if(data.direction == 'esc') {
                if($scope.showSearch) {
                    $scope.searchQ = '';
//  Пока тут непонятно
//                    $scope.toggleSearch();
                }
            }
        });

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