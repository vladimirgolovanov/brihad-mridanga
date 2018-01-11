(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('BookController', BookController);

    function BookController($http, $auth, $rootScope, $scope, $state, $stateParams, $filter) {

        $scope.submit = submit;
        $scope.submiting = false;

        $rootScope.$watch('isLoadingBooks', function(newVal, oldVal) {
            if(!newVal) {
                if($stateParams.id) $scope.book = Object.assign({}, $rootScope.books.find(findIndexById, $stateParams.id));
                else $scope.book = {
                    name: '',
                    shortname: '',
                    pack: null,
                    price_buy: null,
                    price: null,
                    price_shop:	null,
                    book_type: 0,
                    bookgroup_id: null
                };
            }
        });

        $scope.$on('back', function(event, data) {
            $state.go('books');
        });

        function submit() {
            $http.post('admin/book', $scope.book).then(function(response) {
                if(typeof $scope.book.id === "undefined") {
                    $scope.book = response.data;
                    $rootScope.books.push($scope.book);
                    $rootScope.showMessage('Book was created!');
                } else {
                    $rootScope.books[$rootScope.books.findIndex(findIndexById, $scope.book.id)] = response.data;
                    $rootScope.showMessage('Book was updated!');
                }

                $state.go('books');
            }, function(response) {

            });
            $scope.submiting = true;
        }

        function findIndexById(el, idx, arr) {
            return el.id == this;
        }
    }

})();