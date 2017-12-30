(function() {
    'use strict';

    angular
        .module('bmApp')
        .directive('eopdEnter', function () {
            return function (scope, element, attrs) {
                element.bind("keydown keypress", function (event) {
                    if (event.which === 13) {
                        scope.$apply(function () {
                            scope.$eval(attrs.eopdEnter, {'event': event});
                        });

                        event.preventDefault();
                    }
                });
            };
        })
        .controller('AuthController', AuthController);

    function AuthController($auth, $state, $http, $rootScope) {

        var vm = this;

        vm.login = function() {

            var credentials = {
                email: vm.email,
                password: vm.password
            }

            // Use Satellizer's $auth service to login
            $auth.login(credentials).then(function(data) {
                return $http.get('api/authenticate/user');
            }, function(error) {
                switch(error.data.error) {
                    case 'invalid_credentials': $rootScope.showMessage('Invalid login/password combination', 'error'); break;
                    default: $rootScope.showMessage(error.data.error, 'error');
                }

                // Because we returned the $http.get request in the $auth.login
                // promise, we can chain the next promise to the end here
            }).then(function(response) {
                // Stringify the returned data to prepare it
                // to go into local storage
                var user = JSON.stringify(response.data.user);

                // Set the stringified user data into local storage
                localStorage.setItem('user', user);

                // The user's authenticated state gets flipped to
                // true so we can now show parts of the UI that rely
                // on the user being logged in
                $rootScope.authenticated = true;

                $rootScope.preloadData();

                // Putting the user's data on $rootScope allows
                // us to access it anywhere across the app
                $rootScope.currentUser = response.data.user;

                // Everything worked out so we can now redirect to
                // the users state to view the data
                $state.go('persons');
            });
        }

    }

})();