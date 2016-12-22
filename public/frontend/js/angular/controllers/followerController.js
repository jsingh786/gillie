/**
 * Created by rawatabhishek on 11/3/2016.
 */

/*Follow section*/
//get
gillieNetFrontApp.controller('follower',['$scope','ngDialog','CSRF_TOKEN','$http', function($scope,ngDialog,CSRF_TOKEN,$http) {

    angular.element(document).ready(function ()
    {
        $scope.addfollower();
        $scope.removefollower();
        
    });
      $scope.addfollower = function() {
          $http.post('profile/update', $('input[name=userId]').val())
              .success(function(response) {
                  //Disable button and change text.
                  $('#follow').attr('disabled',false);
                  $('#follow').text('UPDATE');

                  console.log(response);
                  flash.pop({title: 'Success', body: constants['profile_updated'], type: 'success'});
              })
     }



}]);

