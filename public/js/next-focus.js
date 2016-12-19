/** Usage:
 <input next-focus tabindex="0">
 <input next-focus tabindex="1">
 <input tabindex="2">
 Upon pressing ENTER key the directive will switch focus to
 the next tabindex.
 The last field should not have next-focus directive to avoid
 focusing on non-existing element.
 Works for Web, iOS (Go button) & Android (Next button) browsers,
 **/
app.directive('nextFocus', [function() {
    return {
        restrict: 'A',
        link: function(scope, elem, attrs) {
            elem.bind('keydown', function(e) {
                var code = e.keyCode || e.which;
                if (code === 13) {
                    e.preventDefault();
                    try {
                        if (attrs.tabindex !== undefined) {
                            var currentTabeIndex = attrs.tabindex;
                            var nextTabIndex = parseInt(currentTabeIndex) + 1;
                            var elems = document.querySelectorAll("[tabindex]");
                            for (var i = 0, len = elems.length; i < len; i++) {
                                var el = angular.element(elems[i]);
                                var idx = parseInt(el.attr('tabindex'));
                                if (idx === nextTabIndex) {
                                    elems[i].focus();
                                    break;
                                }
                            }
                        }
                    } catch (e) {
                        console.log('Focus error: ' + e);
                    }
                }
            });
        }
    };
}]);