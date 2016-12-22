/**
 * Created by hkaur5 on 9/16/2016.
 */


gillieNetFrontApp.controller('aboutMeEditCtrl',['$scope','ngDialog','CSRF_TOKEN','$http','flash','$rootScope','$timeout', function($scope,ngDialog,CSRF_TOKEN,$http, flash, $rootScope, $timeout) {


    angular.element(document).ready(function ()
    {

        //On change checkbox if unchecked then reset favorite weapon
        //drop down.
        $("li.weapon_list input").change(function()
        {
            
            if(! $(this).is(':checked'))
            {
                $scope.resetFavoriteWeaponSelection();
            }
        });

        //Declarations.
        $scope.weapons = [];
        $scope.formData = {};
        $scope.getUserProfileInfo();
        $timeout(
        function()
        {
            $('#favourite_weapon').val($('#store_fav_weapon_id').val())
        }
        , 5000
        );

        $timeout(
        function()
        {
            $('#favourite_hunting_land').val($('#store_fav_hunting_land_id').val())
        }
        , 5000
        );
    });


    /**
     * Get user profile information
     * using ajax call
     * @author hkaur5
     */
    $scope.getUserProfileInfo = function() {

        $scope.loading_about_me_form = true;//Show laoding.
        $('#update_profile').attr('disabled',true); //Disable form update button (button to update profile).

        $http.get(PUBLIC_PATH+'user-info')
            .success(function (response) {

                $scope.loading_about_me_form = false;//Hide loading
                $('#update_profile').attr('disabled',false);//Enable form update button (button to update profile)

                $rootScope.loading = false;
                $scope.userInfo = response;
                $scope.hunting_lands = response.all_hunting_lands;
                $scope.weapons = response.all_weapons;
                $scope.hunting_lands = response.all_hunting_lands;
                $scope.formData.firstname = response.firstname;
                $scope.formData.lastname = response.lastname;
                $scope.formData.work = response.work;
                $scope.formData.phone = response.phone;
                $scope.formData.occupation = response.occupation;
                $scope.formData.college = response.college;
                $scope.formData.school = response.school;
                if (response.weapons != 0) {
                    angular.forEach(response.weapons.ids, function (value, key) {
                        $('input#weapon_checkbox_' + value).attr('checked', true);
                    });
                }

                if (response.activities) {
                    angular.forEach(response.activities.ids, function (value, key) {
                        $('input#activities_checkbox_' + value).attr('checked', true);
                    });
                }

                if (response.species) {
                    angular.forEach(response.species.ids, function (value, key) {
                        $('input#species_checkbox_' + value).attr('checked', true);
                    });
                }

                if (response.properties) {
                    angular.forEach(response.properties.ids, function (value, key) {
                        $('input#properties_checkbox_' + value).attr('checked', true);
                    });
                }

                if (response.gender) {
                    $('[rel = gender_' + response.gender + ']').prop('checked', true);
                }
                if (response.marital_status) {
                    $('[rel = marital_status_' + response.marital_status + ']').prop('checked', true);
                }

                $("#favourite_hunting_land").val('"' + response.fav_hunting_land_id + '"');
                $("#favourite_weapon").val(response.fav_weapon_id);

                $scope.fav_hunting_land_id = response.fav_hunting_land_id;
                $scope.aDate = response.dob;
                
                $('#store_fav_hunting_land_id').val(response.fav_hunting_land_id);
                $('#store_fav_weapon_id').val(response.fav_weapon_id);



            });
    }

    //=================================================================================

    /*$scope.dateOptions = {
     changeYear: true,
     changeMonth: true,
     yearRange: '1900:-0',
     };*/


    $scope.dateOptions = {
        dateFormat: 'M dd, yy',
        maxDate: new Date(),
    }


    //Reset Favorite weapon dropdown when weapons are unchecked.
    $scope.resetFavoriteWeaponSelection = function()
    {
        if(!$scope.weapons[1]){ //If it is checked
            $("#favourite_weapon").val('');
        }

    }

    //When User change favorite weapon dropdown selection then
    // mark corresponding checkbox checked in weapons list.
    $scope.markFavouriteWeaponChecked = function()
    {


        var favourite_weapon_id =  $("#favourite_weapon").val();

        $("#weapon_checkbox_"+favourite_weapon_id).prop("checked",true);

    }

    /**
     * Update user information/profile through an ajax call
     * @author hkaur5
     *
     */
    $scope.updateUserinfo = function()
    {
        //Disable button and change text.
        $('#update_profile').attr('disabled',true);
        $('#update_profile').text('UPDATING...');

        $scope.formData.fav_hunting_land = $("#favourite_hunting_land").val();
        $scope.formData.fav_weapon = $("#favourite_weapon").val();
        $scope.formData.dob = $(".dob_input").val();
       // $scope.formData.gender = $(".gender").val();
        $scope.formData.marital_status = $(".marital_status").val();
        $scope.formData.activities = [];
        $scope.formData.weapons = [];
        $scope.formData.species = [];
        $scope.formData.properties = [];
        $scope.formData.gender = $("input[name='gender']:checked").val();
        $scope.formData.marital_status = $("input[name='marital_status']:checked").val();

        //console.log($(".activities_list input:checked"));
        angular.forEach($(".activities_list input:checked"), function(value, key) {
            $scope.formData.activities[key] = $(value).val();
        });

        angular.forEach($(".weapon_list input:checked"), function(value, key) {
            $scope.formData.weapons[key] = $(value).val();
        });

        angular.forEach($(".properties_list input:checked"), function(value, key) {
            $scope.formData.properties[key] = $(value).val();
        });

        angular.forEach($(".species_list input:checked"), function(value, key) {
            $scope.formData.species[key] = $(value).val();
        });

        $scope.formData.lng       = $("#loc_lng").val();
        $scope.formData.lat       = $("#loc_lat").val();
        $scope.formData.zip_code  = $("#loc_postal_code").val();
        $scope.formData.state     = $("#loc_state").val();
        $scope.formData.country   = $("#loc_country").val();
        $scope.formData.city      = $("#loc_city").val();
        $scope.formData.address   = $("#geocomplete_loc").val();

        var formData = $scope.formData;
        $scope.errors = '';

        $http.post(PUBLIC_PATH+'profile/update', formData)
            .success(function(response) {
                //Disable button and change text.
                $('#update_profile').attr('disabled',false);
                $('#update_profile').text('UPDATE');
                flash.pop({title: 'Success', body: constants['profile_updated'], type: 'success'});
            }).error(function(response){
                //Enable button and change text.
                $('#update_profile').attr('disabled', false);
                $('#update_profile a').text('UPDATE');

                //If validation error.
                if (typeof response == 'object') {

                    $scope.errors = response;
                    flash.pop({title: 'Error', body: constants['your_form_has_some_errors_profile'], type: 'error'});
                }
                else
                {
                    flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});

                }
        });
    }






}]);


