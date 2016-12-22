<div class="title_popup" id="forum_popup">
    <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>
    <a class="popup_close_btn" href="javascript:;" ng-click="ngDialog.close()">X</a>
    <h4> EDIT TOPIC </h4>
    <div class="gillie-form-title">
        <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>
        <form name="newForumFrm">
            <div class="form-group">
                <label>Title</label>
                <input type="text" class="email" ng-model="forum_details_to_be_edited.title">
                <span class="text-danger" ng-repeat='error in errors.title'>[[ error ]]</span>
            </div><!--form-group-->
            <div class="form-group">
                <label>Category</label>
                <select name = 'categories'>
                    @foreach ($forumCategories as $key=>$category)
                        <option value="{{$key}}">{{$category}}</option>
                    @endforeach
                </select>
                <span class="text-danger" ng-repeat='error in errors.category'>[[ error ]]</span>
            </div><!--form-group-->
            <div class="form-group">
                <label>Description</label>
                <text-angular ng-model="forum_details_to_be_edited.description"></text-angular>
            </div><!--form-group-->
            <input style="margin-top: 5px;" ng-disabled="editForumDisableBtn" type="button" value="Submit" class="gillie-btn"
                   ng-click="editForum()">
        </form>
    </div><!--form-title-->
</div><!--title_popup-form-->