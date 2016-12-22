@extends('layouts.frontend.app')
@section('head')
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
    <style>
        #about_info.about_info {
            z-index: 0 !important;
        }
        span#spinloader {
            top: 70% !important;
        }
    </style>
@endsection

@section('content')

<div ng-controller = "aboutMeEditCtrl" class="detail_box2">

    <div id="about_info" class="about_info">
        <h1>About Me </h1>
        <p><img alt="" src="{{ asset('frontend/images/black-drop.png') }}"></p>
    </div><!--about_info-->
    <form name="userForm" id="user_info" novalidate>
        <span ng-cloak ng-show="loading_about_me_form" my-loader="60" id="spinloader"></span>
        <input type="hidden" value="" id="store_fav_weapon_id">
        <input type="hidden" value="" id="store_fav_hunting_land_id">
        <div class="detail_form">

            <ul>
                <li>
                    <span>First name </span>
                    <div class="form-input">
                        <input
                                ng-model="formData.firstname"
                                name = "firstname"
                                class="form-control"
                                type="text"
                                value=[[userInfo.firstname]]
                                maxlength="20">
                        <span class="error"  ng-repeat='error in errors.firstname'>[[ error ]]</span>

                    </div>

                </li>
                <li>
                    <span>Last name </span>
                    <div class="form-input">
                        <input
                                ng-model="formData.lastname"
                                class="form-control"
                                type="text"
                                value=[[userInfo.lastname]]
                                maxlength="20">
                        <span class="error"  ng-repeat='error in errors.lastname'>[[ error ]]</span>
                    </div>
                </li>
                <li>
                    <span>Location </span>
                    <div class="form-input">
                        <input id="geocomplete_loc" value = "[[userInfo.address]]" class="form-control" type="text" placeholder="">
                    </div>
                </li>

                <li>
                    <span>Date of birth</span>
                    <div class="form-input">
                        <input  max-date="maxDate" ui-date="dateOptions" ng-model="aDate"
                                class="dob_input form-control" type="text" value=[[userInfo.dob]]>
                    </div>
                </li>
                <li>
                    <span>Gender </span>
                    <div class="form-input">
                        <label>
                            <input class="gender" rel = "gender_1" value="1"  type="radio"  name="gender">

                            Male
                        </label>
                        <label>
                            <input class="gender" rel = "gender_2" value="2" type="radio"  name="gender">
                            Female
                        </label>
                    </div>
                </li>
                <li>
                    <span>Marital Status </span>
                    <div class="form-input">
                        <label>
                            <input class="marital_status" value="1" rel = "marital_status_1" type="radio"  name="marital_status">

                            Single
                        </label>
                        <label>
                            <input class="marital_status" value="2" type="radio" rel = "marital_status_2"  name="marital_status">

                            Married
                        </label>
                    </div>
                </li>
                <li>
                    <span>Email </span>
                    <div class="form-input">
                        <input class="form-control" readonly type="text" value=[[userInfo.email]]>
                    </div>
                </li>
                <li>
                    <span>Phone </span>
                    <div class="form-input">
                        <input
                                ng-model="formData.phone"
                                class="form-control"
                                type="text"
                                value=[[userInfo.phone]]>
                        <span class="error"  ng-repeat='error in errors.phone'>[[ error ]]</span>
                    </div>
                </li>
                <li>
                    <span>Occupation </span>
                    <div class="form-input">
                        <input
                                ng-model="formData.occupation"
                                class="form-control"
                                type="text"
                                value=[[userInfo.occupation]]>
                        <span class="error"  ng-repeat='error in errors.occupation'>[[ error ]]</span>
                    </div>
                </li>
                <li>
                    <span>Work </span>
                    <div class="form-input">
                        <input
                                ng-model="formData.work"
                                class="form-control"
                                type="text"
                                value=[[userInfo.work]]>
                        <span class="error"  ng-repeat='error in errors.work'>[[ error ]]</span>
                    </div>
                </li>
                <li>
                    <span>College </span>
                    <div class="form-input">
                        <input
                                ng-model="formData.college"
                                class="form-control"
                                type="text"
                                value="[[userInfo.college]]">
                        <span class="error"  ng-repeat='error in errors.college'>[[ error ]]</span>
                    </div>
                </li>
                <li>
                    <span>High School </span>
                    <div class="form-input">
                        <input
                                class="form-control"
                                ng-model="formData.school"
                                type="text"
                                value="[[userInfo.school]]">
                        <span class="error"  ng-repeat='error in errors.school'>[[ error ]]</span>
                    </div>
                </li>
                <li>
                    <span>My Favorite Public Hunting Land </span>
                    <div class="form-input">
                        <select  id="favourite_hunting_land">
                            <option value =[[key]] ng-repeat="(key, value) in hunting_lands">[[value.name]]</option>
                        </select>
                    </div>
                </li>
                <li>
                    <span>My Favorite Weapon </span>
                    <div class="form-input">
                        <select  ng-click="markFavouriteWeaponChecked()" id="favourite_weapon">
                            <option  rel = "weapon_[[key]]" value =[[key]] ng-repeat="(key, value) in userInfo.all_weapons">
                                [[value.name]]
                            </option>
                        </select>
                    </div>

                </li>
            </ul>

            <ul class="list2">
                <span>Activity </span>
                <li class="activities_list">
                    <!-- <input ng-checked="[[userInfo.activities.ids.indexOf(3) != -1]]" type="checkbox">-->
                    <div class="checkbox">
                        <label>
                            <input id = "activities_checkbox_1" value = 1 type="checkbox">
                            Hunting
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input id = "activities_checkbox_2" value = 2 type="checkbox">
                            Freshwater Fishing
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value = 3 id = "activities_checkbox_3" type="checkbox">
                            Saltwater Fishing
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value = 4 id = "activities_checkbox_4" type="checkbox">
                            Camping
                        </label>
                    </div>
                </li>
            </ul><!--list2-->
            <ul class="list3">

                <span>Weapon</span>
                <li class=" weapon_list">
                    <div class="checkbox">
                        <label>
                            <input id="weapon_checkbox_1" value = 1 type="checkbox">
                            Rifle
                        </label></div>
                    <div class="checkbox">
                        <label>
                            <input value = 2 id = "weapon_checkbox_2" type="checkbox">
                            Pistol
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input id="weapon_checkbox_3" value = 3 type="checkbox">
                            Shotgun
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input id="weapon_checkbox_4" rel = 4 value = 4 type="checkbox">
                            Crossbow
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value = 5 id="weapon_checkbox_5" type="checkbox">
                            Compound Bow
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value = 6 id="weapon_checkbox_6" type="checkbox">
                            Long Bow
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value = 7 id="weapon_checkbox_7" type="checkbox">
                            Recurve Bow
                        </label>
                    </div>
                </li>
            </ul><!--list3-->

            <ul class="list4">
                <span>Species</span>
                <li class="species_list">
                    <div class="checkbox">
                        <label>
                            <input value=1 id="species_checkbox_1" type="checkbox">
                            Big Game
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value=2 id="species_checkbox_2" type="checkbox">
                            Small Game
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value=3 id="species_checkbox_3" type="checkbox">
                            Fur Bearers
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value=4 id="species_checkbox_4" type="checkbox">
                            Predators
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value =5 id="species_checkbox_5" type="checkbox">
                            Upland Game Birds
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value =6 id="species_checkbox_6" type="checkbox">
                            Waterfowls
                        </label>
                    </div>
                </li>
            </ul><!--list4-->

            <ul class="list5">
                <span>Property</span>
                <li class="properties_list">
                    <div class="checkbox">
                        <label>
                            <input value = 1 id="properties_checkbox_1" type="checkbox">
                            Own
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value = 2 id="properties_checkbox_2" type="checkbox">
                            Lease
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value = 3 id="properties_checkbox_3" type="checkbox">
                            Public Hunting
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input value = 4 id="properties_checkbox_4" type="checkbox">
                            Willing to Host
                        </label>
                    </div>
                </li>
            </ul><!--list3-->

            <div class="button_tabs">
                <button ng-click="updateUserinfo()" id="update_profile" type="submit" class="gillie-btn">
                  UPDATE
                </button>
              {{--  <button type="button" class="btn"><a href="javascript:;">CANCEL</a></button>--}}
            </div><!--button_tabs-->

        </div><!--detail_form-->
    </form>
</div><!--detail_box2-->
<form style="display: none" id="location_details">
    <input id = "loc_lat" name="lat" type="hidden" value="[[userInfo.latitude]]">
    <input id = "loc_lng" name="lng" type="hidden" value="[[userInfo.longitude]]">
    <input id = "loc_postal_code" name="postal_code" type="hidden" value="[[userInfo.zip_code]]">
    <input id = "loc_country" name="country" type="hidden" value="[[userInfo.country]]">
    <input id = "loc_state" name="administrative_area_level_1" type="hidden" value="[[userInfo.state]]">
    <input id = "loc_city" name="locality" type="hidden" value="[[userInfo.city]]">
</form>

@endsection
@section('footer')
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/profileController.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/aboutMeEditController.js') }}" ></script>
    {{--<script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/notesController.js') }}" ></script>--}}
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDS0gsUmaMUmdoiJSoFGKlOinKY6X3UegU&libraries=places"></script>
    <script  type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.7.0/jquery.geocomplete.min.js" >
    </script>
<script>
    // Location
    $("input#geocomplete_loc").geocomplete({
        details: "form#location_details"
    });
</script>
@endsection
