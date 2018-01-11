(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('EditPersonController', EditPersonController);

    function EditPersonController($http, $auth, $rootScope, $scope, $state, $stateParams, $filter) {

        $scope.submit = submit;
        $scope.submiting = false;

        $rootScope.$watch('isLoadingPersons', function(newVal, oldVal) {
            if(!newVal) {
                if($stateParams.id) $scope.person = Object.assign({}, $rootScope.persons.find(findIndexById, $stateParams.id));
                else $scope.person = {
                    name: '',
                    hide: null
                };
            }
        });

        $scope.$on('back', function(event, data) {
            $state.go('persons');
        });

        function submit() {
            $http.post('admin/person', $scope.person).then(function(response) {
                if(typeof $scope.person.id === "undefined") {
                    $scope.person = response.data;
                    $rootScope.persons.push($scope.person);
                    $rootScope.showMessage('Person was created!');
                } else {
                    $rootScope.persons[$rootScope.persons.findIndex(findIndexById, $scope.person.id)] = response.data;
                    $rootScope.showMessage('Person was updated!');
                }
                $state.go('person', {id: $scope.person.id});
            }, function(response) {

            });
            $scope.submiting = true;
        }

        function findIndexById(el, idx, arr) {
            return el.id == this;
        }

    }

})();