<div class="title_popup" id="forum_popup">
    <a class="popup_close_btn" href="javascript:;" ng-click="ngDialog.close()">X</a>
    <h4> NEW TOPIC </h4>
    <div class="gillie-form-title">
        <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>
        <form name="newForumFrm">
            <div class="form-group">
                <label>Title</label>
                <input type="text" class="email" ng-model="forum.title">
                <span class="text-danger" ng-repeat='error in errors.title'>[[ error ]]</span>
            </div><!--form-group-->
            <div class="form-group">
                <label>Category</label>

                <select ng-model="forum.selected_category"
                        ng-options="obj.txt for obj in forumCategoies track by obj.value">
                    <option value="">Select Category</option>
                </select>
                <span class="text-danger" ng-repeat='error in errors.selected_category'>[[ error ]]</span>
            </div><!--form-group-->
            <div class="form-group">
                <label>Description</label>
             <text-angular ta-paste="stripFormat($html)" ng-model="forum.description"></text-angular>
            </div><!--form-group-->
            <input style="margin-top: 5px;" ng-disabled="addForumDisableBtn" type="button" value="Submit" class="gillie-btn"
                   ng-click="addForum()">
        </form>
    </div><!--form-title-->
</div><!--title_popup-form-->