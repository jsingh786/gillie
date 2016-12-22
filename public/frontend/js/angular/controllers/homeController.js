/**
 * Created by rkaur3 on 8/25/2016.
 */

gillieNetFrontApp.controller('homeController',['$scope','$http','$rootScope','ngDialog','CSRF_TOKEN','forumFactory','forumSearch','authFactory','$window', function($scope,$http,$rootScope,ngDialog,CSRF_TOKEN,forumFactory,forumSearch,authFactory,$window) {

    $scope.searchText = '';

    //open signup dialog
    $scope.openSignUp = function () {
        $scope.ngDialog = ngDialog;
        ngDialog.open({
            animation: true,
            scope:$scope,
            template: 'signupTemplate',
            controller: 'modalSignupController as modalSignupController',
            //className: 'ngdialog-theme-default',
            closeByDocument: false
            //backdrop : 'static'
        });
    }






    //open login dialog
    $scope.openLoginPopup = function () {


       // authFactory.checkAuthentication().then(function(response){
           /* if(response.status == true)
            {
                $window.location.href = 'library';
            }
            else
            {*/
                $scope.ngDialog = ngDialog;
                ngDialog.open({
                    animation: true,
                    scope:$scope,
                    template: 'loginTemplate',
                    controller: 'modalLoginController',
                    //className: 'ngdialog-theme-default',
                    closeByDocument: false
                    //backdrop : 'static'
                });
            //}
       // });

    }

    //forgot password submit
    $scope.forgotPwdPopup = function () {

        //closes login dialog and opens forgot pwd dialog
        ngDialog.close('ngdialog1');
        $scope.ngDialog = ngDialog;
         ngDialog.open({
         animation: true,
         scope:$scope,
         template: 'fwdTemplate',
         controller: 'frgtPwdController',
         //className: 'ngdialog-theme-default',
         closeByDocument: false
         //backdrop : 'static'
         });
    }

    $scope.toggle = function() {
       
        $scope.$broadcast('event:toggle');
    }

    //search button click
    $scope.searchBtnClk = function()
    {
        //forumSearch.upper_search_text = $scope.searchText;
        /*console.log(forumSearch.upper_search_text);
        return false;*/
         var url_path = PUBLIC_PATH+'library#'+encodeURI($scope.searchText);
         window.location.href = url_path;
    }

}]);




gillieNetFrontApp.controller('modalSignupController',['$scope','ngDialog','CSRF_TOKEN','$http','$rootScope','authFactory','$window', function($scope,ngDialog,CSRF_TOKEN,$http,$rootScope,authFactory,$window) {

    $scope.signUpData = {};
    $scope.errors = {};
    $scope.signUpDisableBtn = false;
    //$scope.thankyouMsg = '';

    $scope.geocom = function()
    {
        $("#geocomplete_locc").geocomplete({

            details: "form#location_details"
        });
    }
    
   
    $scope.signUpSumbit = function () {

        $rootScope.loading = true;
        authFactory.checkAuthentication().then(function(response){
            if(response.status == true)
            {
                $window.location.href = 'library';
            }
        });

        $scope.signUpData.is_active = 0;
        var data = $scope.signUpData;

        $scope.signUpDisableBtn = true;

        $scope.signUpData.longitude       = $("#loc_lng").val();
        $scope.signUpData.latitude       = $("#loc_lat").val();
        $scope.signUpData.zipcode  = $("#loc_postal_code").val();
        $scope.signUpData.state     = $("#loc_state").val();
        $scope.signUpData.country   = $("#loc_country").val();
        $scope.signUpData.city      = $("#loc_city").val();
        $scope.signUpData.address    = $("#geocomplete_locc").val();


        $http.post('home/save-user',data)
            .success(function(response) {

                $rootScope.loading = false;

                $scope.errors = {};

               if(response == 'true') {
                   $('#signup-popup').hide();

                   $scope.ngDialog = ngDialog;
                   $scope.thankyouMsg = "Thank you for registering with us. Please confirm your email to proceed.";
                   console.log($scope);
                   ngDialog.open({
                       animation: true,
                       scope: $scope,
                       templateUrl: 'home/thankyou',
                       controller: 'thankyouController',
                       //className: 'ngdialog-theme-default',
                       closeByDocument: false
                       //backdrop : 'static'
                   });
               }


            }).error(function(response){

                $rootScope.loading = false;
                $scope.signUpDisableBtn = false;
                $scope.errors = response;

        });

    }
}]);

gillieNetFrontApp.controller('modalLoginController',['$scope','ngDialog','CSRF_TOKEN','$http','$rootScope', function($scope,ngDialog,CSRF_TOKEN,$http,$rootScope) {

    $scope.loginData = {};
    $scope.errors = {};
        
    $scope.loginSumbit = function () {
        $rootScope.loading = true;

        var data = $scope.loginData;


        $scope.show_message = false;

        $http.post('auth/login',data)
            .success(function(response) {
                $rootScope.loading = false;
                if(response == 'true') {
                    location.href='library';
                }
                else if(response == 'false') {

                    $scope.show_message = true;
                    $scope.errorMessage = 'Oops! You have entered invalid email ID or password. Please try again';
                    $scope.errors = {};

                }
                else if(response == 'in_active')
                {
                    $scope.show_message = true;
                    $scope.errorMessage = 'Please confirm your email to login';
                    $scope.errors = {};
                }
            }).error(function(response){
                $scope.errors = response;
                console.log($scope.errors);
            });
    }



}]);
gillieNetFrontApp.controller('frgtPwdController',['$scope','ngDialog','CSRF_TOKEN','$http','$rootScope', function($scope,ngDialog,CSRF_TOKEN,$http,$rootScope) {


    $scope.pData = {};
    $scope.errors = {};
    $scope.successMsg = false;
    $scope.errorMsg = false;

    $scope.frgtPwdSubmit = function () {

        $scope.success_msg = false;
        $scope.error_msg = false;
        var data = $scope.pData;
        $rootScope.loading = true;
        $http.post('password/email',data)
            .success(function(response) {
                /*if(response == 'true') {
                    location.href='library';
                }*/
                $rootScope.loading = false;
                console.log(response);
                if(response.status == 200)
                {
                    $scope.success_msg = true;
                    $scope.error_msg = false;
                    $scope.successMsg = response.message;
                    $scope.errors = {};

                }
                else if(response.status == 301)
                {
                    $scope.error_msg = true;
                    //hide success messages if visible
                    $scope.success_msg = false;
                    $scope.errorMsg = response.message;
                    $scope.errors = {};
                }

                //ngDialog.close();
            }).error(function(response){
                $rootScope.loading = false;
                $scope.errors = response;
                $scope.success_msg = false;
                $scope.error_msg = false;

            });
    }

}]);
