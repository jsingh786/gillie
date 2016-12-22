gillieNetFrontApp.directive('starRating', function () {

    return {
        scope: {
            rating: '=',
            maxRating: '@',
            readOnly: '@',
            click: "&",
            mouseHover: "&",
            mouseLeave: "&"
        },
        restrict: 'EA',
        template:
            '<div style="display: inline-block; margin: 0; padding: 0; cursor:pointer;" \
                    ng-repeat="idx in maxRatings track by $index"> \
                    <img ng-src="{{((hoverValue + _rating) <= $index) && \''+PUBLIC_PATH+'frontend/images/star-s.png\' || \''+PUBLIC_PATH+'frontend/images/star-g.png\'}}" \
                    ng-Click="isolatedClick($index + 1)" \
                    ng-mouseenter="isolatedMouseHover($index + 1)" \
                    ng-mouseleave="isolatedMouseLeave($index + 1)">\
            </div>',

        compile: function (element, attrs) {
            if (!attrs.maxRating || (Number(attrs.maxRating) <= 0)) {
                attrs.maxRating = '5';
            };
            /*console.log(scope.rating);
            scope.$watch(scope.rating, function(value) {
                console.log(value);
                scope.rating = value;
            });*/

        },
        controller: function ($scope, $element, $attrs) {
            $scope.maxRatings = [];

            for (var i = 1; i <= $scope.maxRating; i++) {
                $scope.maxRatings.push({});
            };

            $scope._rating = $scope.rating;
            $scope.$watch($scope.rating, function(value) {
               $scope._rating = $scope.rating;
            });


            $scope.isolatedClick = function (param) {
                if ($scope.readOnly == 'true') return;

                $scope.rating = $scope._rating = param;
                $scope.hoverValue = 0;
                $scope.click({
                    param: param
                });
            };

            $scope.isolatedMouseHover = function (param) {
                if ($scope.readOnly == 'true') return;

                $scope._rating = 0;
                $scope.hoverValue = param;
                $scope.mouseHover({
                    param: param
                });
            };

            $scope.isolatedMouseLeave = function (param) {
                if ($scope.readOnly == 'true') return;

                $scope._rating = $scope.rating;
                $scope.hoverValue = 0;
                $scope.mouseLeave({
                    param: param
                });
            };
        }
    };
})

