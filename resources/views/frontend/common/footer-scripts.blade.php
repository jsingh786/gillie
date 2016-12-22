

<script>
    $(function () {
        // Slideshow 4
        $("#slider").responsiveSlides({
            auto: true,
            pager: false,
            nav: true,
            speed: 500,
            namespace: "callbacks",
            before: function () {
                $('.events').append("<li>before event fired.</li>");
            },
            after: function () {
                $('.events').append("<li>after event fired.</li>");
            }
        });

    });






    //common header controller initialisation
    gillieNetFrontApp.controller('headerController',['$scope','CSRF_TOKEN','$http','$rootScope','forumSearch','forumFactory','$window', function($scope,CSRF_TOKEN,$http,$rootScope,forumSearch,forumFactory,$window) {

//        angular.element(document).ready(function ()
//        {
//            $scope.profileImg;
//            $scope.getProfileImg();
//        });
       $rootScope.loading = true;
        $scope.open = false;

        $scope.toggleHeader = function() {

            $rootScope.loading = false;
            $scope.open = ! $scope.open;

            $scope.$broadcast('event:toggle');

        }

        $(document).mouseup(function (e)
        {
            var container = $("div#sideClickNav");

            if (!container.is(e.target) // if the target of the click isn't the container...
             && container.has(e.target).length === 0 && $scope.open === true) // ... nor a descendant of the container

            {
                $scope.open = false;
                $scope.$broadcast('event:toggle');
            }

        });


        $scope.upper_search = '';
        $scope.upperSearchClk = function()
        {
            $rootScope.loading = false;
            if( $('#local_tab_active').val() == 0) {
                //manage factory for forum search
                forumSearch.upper_search_text = $scope.upper_search;


                var url_path = PUBLIC_PATH + 'library#' + $scope.upper_search;
                window.location.href = url_path;
            }
        }

        $scope.clearSearchClk = function()
        {
            //remove hash value from url
            parent.location.hash = '';
            //empty upper search scope value
            forumSearch.upper_search_text = '';
            $('#upper_search').val('');
        }

    }]);

//    $scope.getProfileImg = function() {
//        var data = {'userId': $('input[name=loggedUserId]').val()};
//        $http.post(PUBLIC_PATH+'profile-image', data)
//                .success(function(response) {
//                    console.log(response);
//                    if (response)
//                    {
//                        $scope.profileImg = response;
//                    }
//                    else {
//                        $scope.profileImg = "";
//                    }
//                });
//    }

</script>
