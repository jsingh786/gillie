<div class="login_popup" ng-controller="frgtPwdController">

    <a href="javascript:;" class="popup_close_btn" ng-click="ngDialog.close()">X</a>
    <p> <img src="{{ asset('frontend/images/logo-3.png')  }}" alt=""></p>
    <div class="login-form">
        <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>
        <div style="color: green" ng-show="success_msg">[[successMsg]]</div>
        <div ng-show="error_msg" class="text-danger">[[errorMsg]]</div>
        <div class="form-group">
            <input type="text" placeholder="Email"  class="email" ng-model="pData.email">
            <span class="text-danger"  ng-repeat='error in errors.email'>[[ error ]]</span>
        </div><!--form-group-->
        <input type="button" class="gillie-btn" value="Submit" ng-click="frgtPwdSubmit()">
    </div><!--login-form-->
</div><!-- login_popup -->