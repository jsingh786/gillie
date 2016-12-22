/**
 * Created by rawatabhishek on 11/3/2016.
 */

/**
 * Follow section
 * Follow/Unfollow user
 * @author rawatabhishek
 *
 */
gillieNetFrontApp.controller('profileHeaderController',['$scope','ngDialog','CSRF_TOKEN','$http', function($scope,ngDialog,CSRF_TOKEN,$http) {

    angular.element(document).ready(function ()
    {
        /**
         * Get users basic info.
         * using ajax call
         * @author hkaur5
         */
        $http.get(PUBLIC_PATH+'profile/basic-info/'+$('input[name=userId]').val())
            .success(function (response) {
                // console.log(response.profile_photo);
                $scope.userInfo_prof_header = response;
                $scope.image = response.profile_photo;
            });
    });


    /**
     * Add/Remove following user.
     * using ajax call
     * @author rawatabhishek
     */
    $scope.addAndRemoveFollower = function() {
        $('#follow').attr("disabled","true");
      //  var followed = $('button#follow').attr('follow');
        if($('button#follow').attr('follow') == "1")
        {
            // $('#follow').attr("disabled","true");
            var id={'rowId': $('#follow').attr("insertId")};

            $http.post(PUBLIC_PATH+'follower/remove',id)
            .success(function(response)
            {
                // alert(response);
                $('#follow').attr("insertId","");
                $('#follow').text('Follow');
                $scope.followed = false;
                $('#follow').removeAttr("disabled");
                $('button#follow').attr('follow',"0");
            })
        }
        else if($('button#follow').attr('follow') == "0")
        {

            var data = {'followerId': $('input[name=userId]').val()};
            $http.post(PUBLIC_PATH+'follower/add', data)
                .success(function(response) {
                    if(response)
                    {
                        // $('#follow').attr("disabled","true");
                        $('#follow').text('Unfollow');
                        $('#follow').attr("insertId", response);
                        $('#follow').removeAttr("disabled");
                        $('button#follow').attr('follow',"1");
                    }
                })
        }


    }
}]);