(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('PersonController', PersonController);

    function PersonController($http, $auth, $rootScope, $scope, $state, $stateParams, $filter, $timeout) {

        var vm = this;

        $rootScope.blurAllInputs();

        $scope.isLoading = true;
        $scope.person;
        $scope.booksCount = 0;
        $scope.booksTable = false;
        $scope.isIssue = false;
        $scope.lockedOp = null;
        $scope.lockTimeoutPromise = null;
        $scope.opIcon = {
            'remains': {icon:'checkbox-marked', 'class':'md-primary', color:'primary', bgcolor:'primary-100'},
            'Laxmi': {icon:'currency-rub', 'class':'md-primary md-hue-1', color:'primary', bgcolor:'grey-50-0.1'},
            'make': {icon:'plus-circle', 'class':'md-primary md-hue-1', color:'primary', bgcolor:'grey-50-0.1'},
            'order': {icon:'message-bulleted', 'class':'md-accent', color:'primary', bgcolor:'grey-50-0.1'},
            'return': {icon:'undo', 'class':'md-primary md-hue-1', color:'primary', bgcolor:'grey-50-0.1'}
        };
        $scope.data = [[], []];
        $scope.labels = [];
        $scope.series = ['Books', 'Points'];

        $http.get('admin/persons/show/'+$stateParams.id).then(function(person) {
            $scope.person = person.data;
            $rootScope.refreshPerson(person.data);
            $scope.booksCount = Object.keys($scope.person.books).length;
            $scope.isLoading = false;
        }, function(error) {
            $scope.error = error;
            $scope.isLoading = false;
        });

        $scope.$on('operation', function(event, data) {
            switch(data.num) {
                case '1': $state.go('make', { id: $scope.person.id}); break;
                case '2': $state.go('Laxmi', { id: $scope.person.id}); break;
                case '3': $state.go('remains', { id: $scope.person.id}); break;
                case '4': $state.go('exchange', { id: $scope.person.id}); break;
                case '5': $state.go('return', { id: $scope.person.id}); break;
            }
        });

        $scope.$on('back', function(event, data) {
            $state.go('persons', {id:$scope.id});
        });

        $scope.editOperation = function(os) {
            if(os.date > $rootScope.lastCompiled) {
                if (os.type == 'make') {
                    $state.go('editmake', {id: $scope.person.id, op: os.id});
                } else if (os.type == 'order') {
                    $state.go('editmake', {id: $scope.person.id, op: os.id});
                } else if (os.type == 'Laxmi') {
                    $state.go('editLaxmi', {id: $scope.person.id, op: os.id});
                } else if (os.type == 'return') {
                    $state.go('editreturn', {id: $scope.person.id, op: os.id});
                } else if (os.type == 'remains') {
                    $state.go('editremains', {id: $scope.person.id, op: os.id});
                }
            } else {
                $timeout.cancel($scope.lockTimeoutPromise);
                $scope.lockedOp = os.id;
                $scope.lockTimeoutPromise = $timeout(function() {
                    $scope.lockedOp = null;
                }, 1000);
            }
        }

        $scope.showAll = function() {
            $scope.isLoading = true;
            $scope.person;
            $scope.booksCount = 0;
            $http.get('admin/persons/show/'+$stateParams.id+'/showall').then(function(person) {
                $scope.person = person.data;
                $scope.booksCount = Object.keys($scope.person.books).length;
                $scope.isLoading = false;
            }, function(error) {
                $scope.error = error;
                $scope.isLoading = false;
            });
        }
    }

})();