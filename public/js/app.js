(function() {

    'use strict';

    angular
        .module('bmApp', ['ngMaterial', 'ui.router', 'satellizer', 'focus-if', 'angular.filter', 'angularMoment'])
        .config(function($stateProvider,
                         $urlRouterProvider,
                         $mdDateLocaleProvider,
                         $authProvider,
                         $httpProvider,
                         $provide,
                         $animateProvider,
                         $mdIconProvider,
                         $mdThemingProvider,
                         $mdAriaProvider) {

            // TEMPORARY DISABLED
            $mdAriaProvider.disableWarnings();

            //$animateProvider.classNameFilter(/animate/);

            $mdThemingProvider.theme('error');
            $mdThemingProvider.theme('default')
                .primaryPalette('blue-grey')
                .accentPalette('orange');
            $mdThemingProvider.theme('dark')
                .dark();

            $mdIconProvider.defaultIconSet('/static/mdi.svg');
            $mdIconProvider.iconSet('small', '/static/mdi.svg', 24);

            $mdDateLocaleProvider.formatDate = function(date) {
                return moment(date).format('DD.MM.YYYY');
            };

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
                .state('bookgroups', {
                    url: '/bookgroups',
                    templateUrl: '/views/bookgroupsView.php',
                    controller: 'BookgroupsController as c'
                })
                .state('bookgroup', {
                    url: '/bookgroup/:id',
                    templateUrl: '/views/bookgroupView.php',
                    controller: 'BookgroupController as c'
                })
                .state('books', {
                    url: '/books',
                    templateUrl: '/views/booksView.php',
                    controller: 'BooksController as booksctrl'
                })
                .state('book', {
                    url: '/book/:id',
                    templateUrl: '/views/bookView.php',
                    controller: 'BookController as c'
                })
                .state('persons', {
                    url: '/persons',
                    templateUrl: '/views/personsView.php',
                    controller: 'PersonsController as personsctrl'
                })
                .state('addperson', {
                    url: '/person/add',
                    templateUrl: '/views/editPersonView.php',
                    controller: 'EditPersonController as c'
                })
                .state('person', {
                    url: '/person/:id',
                    templateUrl: '/views/personView.php',
                    controller: 'PersonController as c'
                })
                .state('editperson', {
                    url: '/person/:id/edit',
                    templateUrl: '/views/editPersonView.php',
                    controller: 'EditPersonController as c'
                })
                .state('make', {
                    url: '/person/:id/make',
                    templateUrl: '/views/makeView.php',
                    controller: 'MakeController as c'
                })
                .state('editmake', {
                    url: '/person/:id/make/:op',
                    templateUrl: '/views/makeView.php',
                    controller: 'MakeController as c'
                })
                .state('Laxmi', {
                    url: '/person/:id/Laxmi',
                    templateUrl: '/views/LaxmiView.php',
                    controller: 'LaxmiController as c'
                })
                .state('editLaxmi', {
                    url: '/person/:id/Laxmi/:op',
                    templateUrl: '/views/LaxmiView.php',
                    controller: 'LaxmiController as c'
                })
                .state('return', {
                    url: '/person/:id/return',
                    templateUrl: '/views/reView.php',
                    data: {
                        optype: 'return'
                    },
                    controller: 'ReController as c'
                })
                .state('editreturn', {
                    url: '/person/:id/return/:op',
                    templateUrl: '/views/reView.php',
                    data: {
                        optype: 'return'
                    },
                    controller: 'ReController as c'
                })
                .state('remains', {
                    url: '/person/:id/remains',
                    templateUrl: '/views/reView.php',
                    data: {
                        optype: 'remains'
                    },
                    controller: 'ReController as c'
                })
                .state('editremains', {
                    url: '/person/:id/remains/:op',
                    templateUrl: '/views/reView.php',
                    data: {
                        optype: 'remains'
                    },
                    controller: 'ReController as c'
                })
                .state('exchange', {
                    url: '/person/:id/exchange',
                    templateUrl: '/views/reView.php',
                    data: {
                        optype: 'exchange'
                    },
                    controller: 'ReController as c'
                })
                .state('users', {
                    url: '/users',
                    templateUrl: '/views/userView.php',
                    controller: 'UserController as user'
                });
        })
        .run(function($rootScope, $state, $http, $interval, $auth) {

            $rootScope.persons = [];
            $rootScope.isLoadingPersons = true;
            $rootScope.books = [];
            $rootScope.bookgroups = [];
            $rootScope.isLoadingBooks = true;
            $rootScope.lastdate = new Date();
            $rootScope.mousePresent = false;

            var refreshToken = function() {
                $http.get('admin/refresh')
                    .then(function(response) {
                        var refreshToken = response.headers('Authorization');
                        $auth.setToken(refreshToken.replace('Bearer ', ''));
                    });
            }

            $rootScope.blurAllInputs = function() {
                document.activeElement.blur();
                var inputs = document.querySelectorAll('input');
                for(var i=0; i < inputs.length; i++) {
                    inputs[i].blur();
                }
            }

            $rootScope.getPersonById = function(id) {
                var obj = {'id': id};
                return $rootScope.persons.find(function(el) {
                    return this.id == el.id;
                }, obj);
            }

            $rootScope.preloadData = function() {

                $rootScope.stop = $interval(refreshToken, 600000);

                $http.get('admin/persons/visible').then(function(persons) {
                    $rootScope.persons = persons.data.map(function(item, index, arr) {
                        item.last_remains_date_formated = item.last_remains_date?moment(item.last_remains_date).format('DD.MM.YY'):'';
                        return item;
                    });
                    $rootScope.isLoadingPersons = false;
                }, function(error) {
                    $rootScope.showMessage(error.data.error, 'error');
                    $rootScope.isLoadingPersons = false;
                });

                $http.get('admin/books').then(function(books) {
                    $rootScope.books = [];
                    $rootScope.bookgroups = [];
                    for(var key in books.data['books']) {
                        $rootScope.books.push(books.data['books'][key]);
                    }
                    for(var key in books.data['bookgroups']) {
                        $rootScope.bookgroups.push(books.data['bookgroups'][key]);
                    }
                    $rootScope.isLoadingBooks = false;
                }, function(error) {
                    $rootScope.showMessage(error.data.error, 'error');
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
        .controller('MainCtrl', function($scope, $rootScope, $mdSidenav, $mdToast, $http, $auth, $state, $interval) {
            $scope.mouseMove = function(event) {
                $scope.mousePresent = true;
            }
            $scope.keyPressEvent = function(event) {
                if(event.ctrlKey && event.altKey) {
                    switch(event.key) {
                        case 'з':
                        case 'p': $state.go('persons'); event.stopPropagation(); break;
                        case 'и':
                        case 'b': $state.go('books'); event.stopPropagation(); break;
                        case 'd':
                        case 'в': $scope.$broadcast('date'); event.stopPropagation(); break;
                        case 'l':
                        case 'д': $scope.$broadcast('payed'); event.stopPropagation(); break;
                        case 's':
                        case 'ы': $scope.$broadcast('descr'); event.stopPropagation(); break;
                        case '1': $scope.$broadcast('operation', { num: '1' }); break;
                        case '2': $scope.$broadcast('operation', { num: '2' }); break;
                        case '3': $scope.$broadcast('operation', { num: '3' }); break;
                        case '4': $scope.$broadcast('operation', { num: '4' }); break;
                        case '5': $scope.$broadcast('operation', { num: '5' }); break;
                        case 'Backspace': $scope.$broadcast('back'); event.stopPropagation(); break;
                        case 'Enter': $scope.$broadcast('submit'); event.stopPropagation(); break;
                    }
                } else if(!event.ctrlKey && !event.altKey && ['books', 'persons', 'person', 'make', 'Laxmi'].indexOf($state.current.name) != -1) {
                    if((event.charCode >= 1040 && event.charCode <= 1103) || event.charCode == 1105 || event.charCode == 1025) {
                        $scope.$broadcast('charPressed', {
                            char: event.key
                        });
                    } else if(event.key == 'ArrowDown') {
                        $scope.$broadcast('arrow', { direction: 'down' });
                    } else if(event.key == 'ArrowUp') {
                        $scope.$broadcast('arrow', { direction: 'up' });
                    } else if(event.key == 'Enter') {
                        $scope.$broadcast('arrow', { direction: 'enter' });
                    }
                }
//                console.log(event);
            }
            $rootScope.toggleSidenav = function() {
                $mdSidenav('left').toggle();
            };
            $rootScope.showPersons = function(parm) {
                $rootScope.isLoadingPersons = true;
                $http.get('admin/persons'+((parm == 'all')?'/all':'')).then(function(persons) {
                    $rootScope.persons = persons.data;
                    $rootScope.isLoadingPersons = false;
                }, function(error) {
                    $rootScope.showMessage(error.data.error, 'error');
                    $rootScope.isLoadingPersons = false;
                });
            };
            $rootScope.showMessage = function(text, theme) {
                $mdToast.show(
                    $mdToast.simple()
                        .hideDelay(5000)
                        .position('top right')
                        .theme(theme)
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

                    $interval.cancel($rootScope.stop);

                    // Redirect to auth (necessary for Satellizer 0.12.5+)
                    $state.go('auth');
                });
            };
        });
})();