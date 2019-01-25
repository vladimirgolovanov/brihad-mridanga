(function() {

    'use strict';

    angular
        .module('bmApp')
        .controller('ReportController', ReportController);

    function ReportController($http, $auth, $rootScope, $scope, $state, $stateParams, $filter, $timeout) {

        $scope.submit = submit;
        $scope.recalcReport = recalcReport;
        $scope.dateSelected = dateSelected;
        $scope.getFieldSum = getFieldSum;
        $scope.report = new Object();
        $scope.submiting = false;
        $scope.editDate = false;
        $scope.from = null;
        $scope.till = null;
        $scope.state = 0;
        $scope.compileEnabled = false;
        $scope.showInfo = false;
        $scope.sortMenu = false;
        $scope.sortBy = 'name';

        $scope.textToCopy = "Name\tMaha\tBig\tMiddle\tSmall\tBooks\tPoints\tBuying price\tGain\tDonation\n";
        $scope.clipboardSupported = false;
        $scope.clipboardSuccess = clipboardSuccess;
        $scope.clipboardCopied = false;

        $rootScope.$watch('isLoadingReports', function(newVal, oldVal) {
            if(!newVal) {
                if($stateParams.from) {
                    $scope.from = $stateParams.from;
                    $scope.till = $stateParams.till;
                    recalcReport($scope.from, $scope.till);
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

        function clipboardSuccess() {
            $scope.clipboardCopied = true;
            $timeout(function() {
                $scope.clipboardCopied = false;
            }, 1000);
        }

        function getFieldSum(items, field) {
            return items
                .map(function(x) { return x[field]; })
                .reduce(function(a, b) { return a + b; });
        }

        function dateSelected() {
            $state.go('report', {'from':$scope.from, 'till':$scope.till});
        }

        function recalcReport(from, till) {
            var ps = new Array();
            $rootScope.reports.reverse();
            $scope.state = 0;
            for(var k in $rootScope.reports) {
                if($rootScope.reports[k].custom_date > from && $rootScope.reports[k].custom_date <= till) {
                    if($scope.state) {
                        for(var pk in $rootScope.reports[k].persons) {
                            $scope.state = 2;
                            for(var psk in ps) {
                                if(ps[psk].id == $rootScope.reports[k].persons[pk].id) {
                                    ps[psk].donation += $rootScope.reports[k].persons[pk].donation;
                                    ps[psk].debt = $rootScope.reports[k].persons[pk].debt;
                                    ps[psk].total += $rootScope.reports[k].persons[pk].total;
                                    ps[psk].points += $rootScope.reports[k].persons[pk].points;
                                    ps[psk].gain += $rootScope.reports[k].persons[pk].gain;
                                    ps[psk].buying_price += $rootScope.reports[k].persons[pk].buying_price;
                                    ps[psk].maha += $rootScope.reports[k].persons[pk].maha;
                                    ps[psk].big += $rootScope.reports[k].persons[pk].big;
                                    ps[psk].middle += $rootScope.reports[k].persons[pk].middle;
                                    ps[psk].small += $rootScope.reports[k].persons[pk].small;
                                    $scope.state = 3;
                                }
                            }
                            if($scope.state != 3) ps.push(JSON.parse(JSON.stringify($rootScope.reports[k].persons[pk])));
                        }
                        $scope.report.custom_date = $rootScope.reports[k].custom_date;
                    } else {
                        $scope.report = JSON.parse(JSON.stringify($rootScope.reports[k]));
                        ps = JSON.parse(JSON.stringify($rootScope.reports[k].persons));
                        $scope.state = 1;
                    }
                }
            }
            $rootScope.reports.reverse();
            $scope.report.persons = ps;
            $scope.report.id = $stateParams.till;
            $scope.compileEnabled = $scope.report.compiled?false:true;

            $scope.textToCopy += 'Goloka Dhama-Moscow';
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'maha');
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'big');
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'middle');
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'small');
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'total');
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'points');
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'buying_price');
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'gain');
            $scope.textToCopy += "\t";
            $scope.textToCopy += getFieldSum($scope.report.persons, 'donation');
            $scope.textToCopy += "\n";
            var groups = $filter('groupBy')($scope.report.persons, 'pgroup');
            for(var gk in groups) {
                $scope.textToCopy += "\n";
                $scope.textToCopy += gk!='null'?gk:"Другие";
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'maha');
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'big');
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'middle');
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'small');
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'total');
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'points');
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'buying_price');
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'gain');
                $scope.textToCopy += "\t";
                $scope.textToCopy += $scope.getFieldSum(groups[gk], 'donation');
                $scope.textToCopy += "\n";
                var group = $filter('orderBy')(groups[gk], 'points', true);
                for(var pk in group) {
                    $scope.textToCopy += group[pk].name;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].maha;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].big;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].middle;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].small;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].total;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].points;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].buying_price;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].gain;
                    $scope.textToCopy += "\t";
                    $scope.textToCopy += group[pk].donation;
                    $scope.textToCopy += "\n";
                }
            }
        }

        function submit() {
            $http.post('admin/report', $scope.report).then(function(response) {
                $rootScope.reports = response.data['reports'];
                $rootScope.lastCompiled = response.data['last_compiled'];
                $scope.submiting = false;
                if(!$scope.report.id) {
                    $state.go('reports');
                } else {
                    recalcReport($scope.from, $scope.till);
                }
            }, function(response) {

            });
            $scope.submiting = true;
        }
    }

})();