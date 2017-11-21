(function() {

    'use strict';

    angular
        .module('bmApp', ['ngMaterial', 'ui.router', 'satellizer', 'focus-if'])
        .config(function($stateProvider,
                         $urlRouterProvider,
                         $authProvider,
                         $httpProvider,
                         $provide,
                         $animateProvider,
                         $mdIconProvider,
                         $mdThemingProvider) {

            $animateProvider.classNameFilter(/animate/);

            $mdThemingProvider.theme('default')
                .primaryPalette('blue-grey')
                .accentPalette('orange');
            $mdThemingProvider.theme('dark')
                .dark();

            $mdIconProvider.defaultIconSet('/static/mdi.svg');
            $mdIconProvider.iconSet('small', '/static/mdi.svg', 24);

            function redirectWhenLoggedOut($q, $injector) {

                return {

                    responseError: function(rejection) {

                        // Need to use $injector.get to bring in $state or else we get
                        // a circular dependency error
                        var $state = $injector.get('$state');

                        // Instead of checking for a status code of 400 which might be used
                        // for other reasons in Laravel, we check for the specific rejection
                        // reasons to tell us if we need to redirect to the login state
                        var rejectionReasons = ['token_not_provided', 'token_expired', 'token_absent', 'token_invalid'];

                        // Loop through each rejection reason and redirect to the login
                        // state if one is encountered
                        angular.forEach(rejectionReasons, function(value, key) {

                            if(rejection.data.error === value) {

                                // If we get a rejection corresponding to one of the reasons
                                // in our array, we know we need to authenticate the user so
                                // we can remove the current user from local storage
                                localStorage.removeItem('user');

                                // Send the user to the auth state so they can login
                                $state.go('auth');
                            }
                        });

                        return $q.reject(rejection);
                    }
                }
            }

            // Setup for the $httpInterceptor
            $provide.factory('redirectWhenLoggedOut', redirectWhenLoggedOut);

            // Push the new factory onto the $http interceptor array
            $httpProvider.interceptors.push('redirectWhenLoggedOut');

            // Satellizer configuration that specifies which API
            // route the JWT should be retrieved from
            $authProvider.loginUrl = '/api/authenticate';

            // Redirect to the auth state if any other states
            // are requested other than users
            $urlRouterProvider.otherwise('/auth');

            $stateProvider
                .state('auth', {
                    url: '/auth',
                    templateUrl: '/views/authView.php',
                    controller: 'AuthController as auth'
                })
                .state('settings', {
                    url: '/settings',
                    templateUrl: '/views/settingsView.php'/*,
                    controller: 'SettingsController as settings'*/
                })
                .state('persons', {
                    url: '/persons',
                    templateUrl: '/views/personsView.php',
                    controller: 'PersonsController as personsctrl'
                })
                .state('person', {
                    url: '/person/:id',
                    templateUrl: '/views/personView.php',
                    controller: 'PersonController as c'
                })
                .state('make', {
                    url: '/person/:id/make',
                    templateUrl: '/views/makeView.php',
                    controller: 'MakeController as c'
                })
                .state('users', {
                    url: '/users',
                    templateUrl: '/views/userView.php',
                    controller: 'UserController as user'
                });
        })
        .run(function($rootScope, $state, $http) {

            $rootScope.persons = [];
            $rootScope.isLoadingPersons = true;
            $rootScope.books = [];
            $rootScope.isLoadingBooks = true;

            $rootScope.preloadData = function() {

                $http.get('admin/persons/visible').then(function(persons) {
                    $rootScope.persons = persons.data;
                    $rootScope.isLoadingPersons = false;
                }, function(error) {
                    $rootScope.showError(error);
                    $rootScope.isLoadingPersons = false;
                });

                $http.get('admin/books').then(function(books) {
                    $rootScope.books = [];
                    for(var key in books.data) {
                        $rootScope.books.push(books.data[key]);
                    }
                    $rootScope.isLoadingBooks = false;
                }, function(error) {
                    $rootScope.showError(error);
                    $rootScope.isLoadingBooks = false;
                });
            };

            // $stateChangeStart is fired whenever the state changes. We can use some parameters
            // such as toState to hook into details about the state as it is changing
            $rootScope.$on('$stateChangeStart', function(event, toState) {

                // Grab the user from local storage and parse it to an object
                var user = JSON.parse(localStorage.getItem('user'));

                // If there is any user data in local storage then the user is quite
                // likely authenticated. If their token is expired, or if they are
                // otherwise not actually authenticated, they will be redirected to
                // the auth state because of the rejected request anyway
                if(user) {

                    if(!$rootScope.authenticated) $rootScope.preloadData();

                    // The user's authenticated state gets flipped to
                    // true so we can now show parts of the UI that rely
                    // on the user being logged in
                    $rootScope.authenticated = true;

                    // Putting the user's data on $rootScope allows
                    // us to access it anywhere across the app. Here
                    // we are grabbing what is in local storage
                    $rootScope.currentUser = user;

                    // If the user is logged in and we hit the auth route we don't need
                    // to stay there and can send the user to the main state
                    if(toState.name === "auth") {

                        // Preventing the default behavior allows us to use $state.go
                        // to change states
                        event.preventDefault();

                        // go to the "main" state which in our case is users
                        $state.go('persons');
                    }
                }
            });
        })
        .controller('MainCtrl', function($scope, $rootScope, $mdSidenav, $mdToast, $http, $auth, $state) {
            $scope.toggleSidenav = function() {
                $mdSidenav('left').toggle();
            };
            $scope.showPersons = function(parm) {
                $scope.isLoadingPersons = true;
                $http.get('admin/persons'+((parm == 'all')?'/all':'')).then(function(persons) {
                    $scope.persons = persons.data;
                    $scope.isLoadingPersons = false;
                }, function(error) {
                    $scope.showError(error);
                    $scope.isLoadingPersons = false;
                });
            };
            $scope.showError = function(text) {
                $mdToast.show(
                    $mdToast.simple()
                        .hideDelay(10000)
                        .position('top right')
                        .textContent(text)
                );
            };
            $scope.logout = function() {

                $auth.logout().then(function() {

                    // Remove the authenticated user from local storage
                    localStorage.removeItem('user');

                    // Flip authenticated to false so that we no longer
                    // show UI elements dependant on the user being logged in
                    $rootScope.authenticated = false;

                    // Remove the current user info from rootscope
                    $rootScope.currentUser = null;

                    // Redirect to auth (necessary for Satellizer 0.12.5+)
                    $state.go('auth');
                });
            };
        });
})();