/**
 * Created by RawatAbhishek on 11/9/2016.
 */

gillieNetFrontApp.controller('FollowingController',['$scope', 'ngDialog', 'CSRF_TOKEN', '$http',
    function($scope, ngDialog, CSRF_TOKEN, $http) {

    angular.element(document).ready(function ()
    {
        $scope.limit = 3;
        $scope.offset = 0;
        $scope.loadFollowing();
        $scope.followingInfo = [];
        $scope.hasMoreRecords;
        $scope.ImgInfo = [];


        $('#search').keyup(function(event){
            if(event.which === 13)
            {
                $scope.followingInfo = [];
                $scope.limit = 3;
                $scope.offset = 0;
                $scope.search(0);
            }
    });
    });
    /**
     * Get Following users details through an ajax call
     * @author rawatabhishek
     */
    $scope.loadFollowing = function() {
        $scope.loading=true;
        $("#loadmore").hide();
            var params={
                'userId': $('input[name=userId]').val(),
                'limit': $scope.limit,
                'offset': $scope.offset
            };

        $http.post(PUBLIC_PATH+'following-details', params)
            .success(function (response) {
                // console.log(response);
                $scope.loading=false;
                if(response)
                {
                    angular.forEach(response.following, function(value, key) {
                        console.log(response.id);
                        $scope.followingInfo.push({'name':value.name,'place':value.place,'id':value.id,
                            'path':value.path, 'fid': value.fid, 'followingstatus': value.followingstatus,'followedRowId':value.followedRowId})
                    });

                    $scope.hasMoreRecords=response.moreRecords;
                    $("#loadmore").show();
                }

                if($scope.hasMoreRecords == 0)
                {
                    $("#loadmore").hide();
                }


                if(response == 0){
                $('#searchdiv').hide();
                $scope.msg=false;
                $("#loadmore").hide();
                }

        });
    }


    /**
     * Add and remove follower through an ajax call
     * @param integer id(id of followed user)
     * @param integer fid(id of row to be deleted)
     * @author rawatabhishek
     * @param integer id(id of followed user id)
     * @param integer fid(id of row to be deleted )
     * @param integer fwrId(follower_id)
     **/
        $scope.addAndRemoveFollower = function(fid, id) {
            console.log('id:'+id);
            console.log('fid:'+fid);
            // return;
            var follow_id = $('[rel1=following_user_'+id+']').attr('rel');
            // console.log($('button#following_' + fid).attr("followed"));
            console.log('this' +follow_id);
            $('button#following_' + id).attr("disabled","true");

            if($('button#following_' + id).attr("followed") == "true")
            {
                var data={'rowId': follow_id};
                $http.post(PUBLIC_PATH+'follower/remove',data)
                    .success(function(response)
                    {
                        $('button#following_' + id).attr("followed","false");
                        $('button#following_' + id).text('Follow');
                        $('button#following_' + id).removeAttr("disabled");
                    });
            }
            else
            {
                    var data = {'followerId': id};
                    $http.post(PUBLIC_PATH + 'follower/add', data)
                        .success(function (response) {
                            if (response) {
                                $('button#following_' + id).removeAttr("disabled");
                                $('button#following_' + id).text('Unfollow');
                                // $scope.followed = true;
                                $('button#following_' + id).attr("followed", "true");
                                $('button#following_' + id).attr("rel", response);

                            }
                        });
            }
        }


    /**
     * Show more following users in listing through an ajax call
     * @author rawatabhishek
     */
    $scope.showMoreRecords = function()
    {
        if($('#search').val())
        {
            $scope.offset = parseInt($scope.offset) + parseInt($scope.limit);
            $scope.search(1);
        }
            // $scope.loading="true";
        else
        {
            if ($scope.hasMoreRecords) {
                $scope.offset = parseInt($scope.offset) + parseInt($scope.limit);
                $scope.loadFollowing();
            }
            else {
                $("#loadmore").hide();
            }
        }
    }
        /*
         * search the users through an ajax call
         * @author rawatabhishek
        */
    $scope.search = function(load_more) {
        // $scope.searchmsg = false;

            if (load_more == 0) {
                // $scope.searchmsg = false;
                $scope.followingInfo = [];
            }

        if ($('#search').val() == '') {
            $('#message').hide();
            $scope.followingInfo = [];
            $scope.limit = 3;
            $scope.offset = 0;
            $scope.loadFollowing();
        }

            var data = {
                'pattren': $('#search').val(), 'userId': $('input[name=userId]').val(), 'offset': $scope.offset,
                'limit': $scope.limit};

            // $('#search').attr('disabled', 'disabled');
            $http.post(PUBLIC_PATH + 'following-search', data)
                .success(function (response) {
                    // $('#search').removeAttr('disabled');
                    // console.log(response.following);
                    if (response) {
                        $scope.searchmsg = false;
                        angular.forEach(response.following, function (value, key) {
                            $scope.followingInfo.push({
                                'name': value.name,
                                'place': value.place,
                                'id': value.id,
                                'path': value.path,
                                'fid': value.fid
                            })
                        });

                    }
                    if (response == 0) {
                        $scope.searchmsg = true;
                        $('#loadmore').hide();

                    }

                    if (response.moreRecords == 1) {
                        $scope.searchmsg = false;
                        $('#loadmore').hide();

                    }

                    if(response.moreRecords == 0) {
                        $scope.searchmsg = false;
                        $('#loadmore').hide();
                        }

                });
        }
}]);