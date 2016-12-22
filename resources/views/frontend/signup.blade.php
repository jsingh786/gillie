
<div class="login_popup" id="signup-popup" role="dialog" ng-controller="modalSignupController">
    <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>
    <a href="javascript:;" class="popup_close_btn" ng-click="ngDialog.close()">X</a>
    <p> <img src="{{ asset('frontend/images/logo-3.png') }}" alt="">
    <form name="userSignUpFrm">
    <div class="login-form">
        <div class="form-group">
            <input type="text" placeholder="First Name" class="email" ng-model="signUpData.firstname" maxlength="20">
            <span class="text-danger"  ng-repeat='error in errors.firstname'>[[ error ]]</span>
        </div><!--form-group-->
        <div class="form-group">
            <input type="text" placeholder="Last Name" class="email" ng-model="signUpData.lastname" maxlength="20">
            <span class="text-danger"  ng-repeat='error in errors.lastname'>[[ error ]]</span>
        </div><!--form-group-->
      {{--  <div class="form-group">
            <input type="text" placeholder="Zipcode" class="Zipcode" ng-model="signUpData.zipcode">
            <span class="text-danger"  ng-repeat='error in errors.zipcode'>[[ error ]]</span>
        </div><!--form-group-->--}}
        <div class="form-group">
            <input type="text" id="geocomplete_locc" ng-keyup="geocom()"  placeholder="Location" class="Zipcode" ng-model="signUpData.address">
            <span class="text-danger"  ng-repeat='error in errors.address'>[[ error ]]</span>
        </div>
        <div class="form-group">
            <input type="text" placeholder="Email" class="envelop" maxlength="50" ng-model="signUpData.email">
            <span class="text-danger"  ng-repeat='error in errors.email'>[[ error ]]</span>
        </div><!--form-group-->
        <div class="form-group">
            <input type="password" placeholder="Password" class="password" ng-model="signUpData.password">
            <span class="text-danger"  ng-repeat='error in errors.password'>[[ error ]]</span>
        </div><!--form-group-->
        <div class="form-group">
            <input type="password" placeholder="Confirm Password" class="password" ng-model="signUpData.cpassword">
            <span class="text-danger"  ng-repeat='error in errors.cpassword'>[[ error ]]</span>
        </div><!--form-group-->

        <div class="signup">
            <input type="submit" ng-disabled="signUpDisableBtn"  class="gillie-btn" value="SIGNUP" ng-click="signUpSumbit()">
        </div><!--form-group-->

        <div class="form-group">
            <h5>"By clicking Sign Up, you agree to our Terms and that you have read our Data Policy, including our Cookie Use."</h5>
        </div><!--form-group-->

    </div><!--login-form-->
    </form>
</div><!-- login_popup -->
<form style="display: none" id="location_details">
    <input id = "loc_lat" name="lat" type="hidden">
    <input id = "loc_lng" name="lng" type="hidden">
    <input id = "loc_postal_code" name="postal_code" type="hidden">
    <input id = "loc_country" name="country" type="hidden">
    <input id = "loc_state" name="administrative_area_level_1" type="hidden">
    <input id = "loc_city" name="locality" type="hidden">
</form>