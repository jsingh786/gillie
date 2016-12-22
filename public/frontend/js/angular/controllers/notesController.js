gillieNetFrontApp.controller('notesController',['$scope','CSRF_TOKEN','$http','ngDialog','noteFactory','$rootScope','updateOffset', function($scope,CSRF_TOKEN,$http,ngDialog,noteFactory,$rootScope,updateOffset) {

    $rootScope.loading = true;
   // var vm = this;
    $rootScope.user_notes = [];
    $scope.counter = 0;
    $scope.errors = {};
    //$scope.offset = updateOffset.getOffset();


    $scope.offset = updateOffset.getOffset();
    $scope.limit = 3;

    angular.element(document).ready(function ()
    {
        var notes_data = {'offset':$scope.offset,'limit':$scope.limit};
        $http.post('profile/get-notes',notes_data).success(function(response){

            $rootScope.user_notes.is_more_records = response.is_more_records;
            $rootScope.loading = false;
            if(response.notes.length > 0) {
                $rootScope.user_notes = response.notes;

                //hide view more link if total count is less than or equal to limit specified
                if(response.total_count <= $scope.limit)
                {
                    $scope.hide_view_more = true;
                }
            }
            else  if(response.notes.length == 0)
            {
                $rootScope.loading = false;
                $scope.hide_view_more = true;

            }
        });


    });

    //view more click
    $scope.showMore = function()
    {

      var offset = updateOffset.getOffset() + $scope.limit;
        updateOffset.setOffset(offset);

        var notes_data = {'offset':offset,'limit':3};
        $http.post('profile/get-notes',notes_data).success(function(response){

            $rootScope.loading = false;
            $rootScope.user_notes.is_more_records = response.is_more_records;
            if(response.notes.length > 0) {
                var i;
                for (i = 0; i < response.notes.length; i++) {
                    $rootScope.user_notes.push(response.notes[i]);

                }
            }
            else  if(response.notes.length == 0)
            {
                $rootScope.loading = false;
                $scope.hide_view_more = true;
            }
        });
    }

    $scope.addNote = function()
    {
        $scope.ngDialog = ngDialog;
        ngDialog.open({
            animation: true,
            scope:$scope,
            templateUrl: 'notes/new-note-popup',
            controller: 'newNoteController',
        });
    }



}])

gillieNetFrontApp.controller('newNoteController',['$scope','CSRF_TOKEN','$http','ngDialog','$rootScope','noteFactory','updateOffset', function($scope,CSRF_TOKEN,$http,ngDialog,$rootScope,noteFactory,updateOffset) {

    $scope.userNoteData = {};
    $scope.newNoteClk = function(){
        var data = $scope.userNoteData;
       
        $http.post('notes/add-note',data).success(function(response){

            if(response.status == 200)
            {
                noteFactory.add(response.note);

                var offset = (parseInt(updateOffset.getOffset()) + 1);
                updateOffset.setOffset(offset);
                ngDialog.close();

            }

         }).error(function(response){

            $scope.errors = response;

        });
    }
}]);