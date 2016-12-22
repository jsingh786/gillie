/**
 * Created by rkaur3 on 9/23/2016.
 */

/*Notes section*/
//get notes for main page
gillieNetFrontApp.controller('mainNotesController',['$scope','ngDialog','CSRF_TOKEN','$http', function($scope,ngDialog,CSRF_TOKEN,$http) {


    //notes section
    $scope.notes = [];
    var note_data = {'offset':0,'limit':3};
    $http.post(PUBLIC_PATH+'profile/get-notes',note_data).success(function(response){
        $scope.notes = response.notes;
    });



}]);


/**
 * Controller for albums section in right
 * panel.
 * @author hkaur5
 */
gillieNetFrontApp.controller('photosVideoMenuItemCtrl',['$scope','ngDialog','CSRF_TOKEN','$http', function($scope,ngDialog,CSRF_TOKEN,$http) {

    $scope.get_albums = [];
    angular.element(document).ready(function ()
    {
        $scope.get_albums.offset = 0;
        $scope.get_albums.limit = 6;
        $scope.get_albums.is_more_records = 0 ;
        $scope.albums = [];
        $scope.loadAlbums_rightMenu();
        $scope.albums_exists_right_menu = false;
        $scope.no_albums_right_menu = false;


    });


    /**
     * Load albums using ajax call and push
     * data to albums array.
     * @author hkaur5
     *@author rawatabhishek(added userId from hidden input field)
     */
    $scope.loadAlbums_rightMenu = function () {
        $scope.loading_photos = true;
        $http({
            url: PUBLIC_PATH+'user-albums',
            method: "GET",
            params: {'offset': $scope.get_albums.offset,
                'limit': $scope.get_albums.limit,
                'userId': $('input[name=userId]').val(),
            }
        }).success(function(response) {
            $scope.loading_photos = false;
            if(response['albums'])
            {
                $scope.albums_exists_right_menu = true;

                angular.forEach(response.albums, function(value, key) {
                    var html = "";
                    
                    $scope.albums.push({'display_name':value.display_name,'photo_count':value.photo_count,
                        'last_photo_path':value.last_photo_path,'id':value.id})
                });
            }
            else
            {
                $scope.no_albums_right_menu = true;
            }

        });
    }
}]);
