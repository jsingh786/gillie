<div class="login_popup" ng-controller="modalLoginController">
    <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>
    <a href="javascript:;" class="popup_close_btn" ng-click="ngDialog.close()">X</a>
    <p> <img src="{{ asset('frontend/images/logo-3.png') }}" alt="">
    <form name="loginFrm">
    <div class="login-form">
        <div ng-show ="show_message" style="color: red">[[errorMessage]]</div>
        <div class="form-group">
            <input type="text" placeholder="Email" class="email" ng-model="loginData.email">
            <span class="text-danger"  ng-repeat='error in errors.email'>[[ error ]]</span>
        </div><!--form-group-->
        <div class="form-group">
            <input type="password" placeholder="Password" class="password" ng-model="loginData.password">
            <span class="text-danger"  ng-repeat='error in errors.password'>[[ error ]]</span>
        </div><!--form-group-->
        <div class="form-group fgt_pswd">
            <label> <input ng-model="loginData.remember_me" type="checkbox"> Keep me logged in </label>
            <span> <a href="javascript:;" ng-dialog-close-previous ng-click="forgotPwdPopup()">Forgot Password ?</a></span>
        </div><!--form-group-->
        <input type="submit" class="gillie-btn" value="LOGIN" ng-click="loginSumbit()">
    </div><!--login-form-->
    </form>
</div><!-- login_popup -->