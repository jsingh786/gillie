<div class="gallery_popup glry_caret">
    <a class="popup_close_btn" href="javascript:;" ng-click="ngDialog.close()">X</a>
    <h2>Add Notes</h2>
    <text-angular ta-paste="stripFormat($html)" ng-model="userNoteData.note"></text-angular>
    <span class="text-danger" ng-repeat='error in errors.note' style="margin-top: 2px;">[[ error ]]</span>
    <div class="upload">
        <button class="ca_btn" ng-click="newNoteClk()">Submit</button>
    </div>
</div>