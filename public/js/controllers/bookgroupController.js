(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('BookgroupController', BookgroupController);

    function BookgroupController($http, $auth, $rootScope, $state, $stateParams, $filter) {

        var self = this;
        self.submit = submit;
        self.submiting = false;

        $rootScope.$watch('isLoadingBooks', function(newVal, oldVal) {
            if(!newVal) {
                if($stateParams.id) self.bookgroup = $filter('filter')($rootScope.bookgroups, {id: $stateParams.id})[0];
                else self.bookgroup = {
                    name: ''
                };
            }
        });

        function submit() {
            $http.post('admin/bookgroup', self.bookgroup).then(function(response) {
                if(!self.bookgroup.id) {
                    self.bookgroup = response.data;
                    $rootScope.bookgroups.push(self.bookgroup);
                    $rootScope.showMessage('Book group was created!');
                } else {
                    $rootScope.showMessage('Book group was updated!');
                }
                $state.go('bookgroups');
            }, function(response) {

            });
            self.submiting = true;
        }

    }

})();