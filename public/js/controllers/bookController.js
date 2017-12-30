(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('BookController', BookController);

    function BookController($http, $auth, $rootScope, $state, $stateParams, $filter) {

        var self = this;
        self.submit = submit;
        self.submiting = false;

        $rootScope.$watch('isLoadingBooks', function(newVal, oldVal) {
            if(!newVal) {
                if($stateParams.id) self.book = $filter('filter')($rootScope.books, {id: $stateParams.id})[0];
                else self.book = {
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

        function submit() {
            $http.post('admin/book', self.book).then(function(response) {
                if(!self.book.id) {
                    self.book = response.data;
                    $rootScope.books.push(self.book);
                    $rootScope.showMessage('Book was created!');
                } else {
                    $rootScope.books[self.book.id] = response.data;
                    $rootScope.showMessage('Book was updated!');
                }
                $state.go('books');
            }, function(response) {

            });
            self.submiting = true;
        }

    }

})();