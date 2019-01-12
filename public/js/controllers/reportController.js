(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('ReportController', ReportController);

    function ReportController($http, $auth, $rootScope, $scope, $state, $stateParams, $filter) {

        $scope.submit = submit;
        $scope.submiting = false;
        $scope.editDate = false;

        $rootScope.$watch('isLoadingReports', function(newVal, oldVal) {
            if(!newVal) {
                if($stateParams.id) {
                    $scope.report = Object.assign({}, $rootScope.reports[$stateParams.id]);
                    $scope.report.custom_date = $stateParams.id;
                    $scope.report.id = $stateParams.id;
                }
                else $scope.report = {
                    id: null,
                    custom_date: new Date(),
                    compiled: null
                };
            }
        });

        $scope.$on('back', function(event, data) {
            $state.go('reports');
        });

        function submit() {
            $http.post('admin/report', $scope.report).then(function(response) {
                if(!$scope.report.id) {
                    response.data.custom_date_formated = response.data.custom_date?moment(response.data.custom_date).format('DD.MM.YY'):'';
                    $rootScope.reports.push(response.data);
                    $rootScope.showMessage('Report was created!');
                } else {
                    response.data.custom_date_formated = response.data.custom_date?moment(response.data.custom_date).format('DD.MM.YY'):'';
                    $rootScope.reports[$rootScope.reports.findIndex(findIndexById, $scope.report.id)] = response.data;
                    $rootScope.showMessage('Report was updated!');
                }

                $state.go('reports');
            }, function(response) {

            });
            $scope.submiting = true;
        }

        function findIndexById(el, idx, arr) {
            return el.custom_date == this;
        }
    }

})();