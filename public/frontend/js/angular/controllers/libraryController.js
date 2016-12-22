/**
 * Created by rkaur3 on 8/31/2016.
 */
gillieNetFrontApp.controller('libraryController', ['$scope', 'ngDialog', 'CSRF_TOKEN', '$http', 'forumFactory', '$rootScope', 'timeAgo', '$window', 'authFactory', 'forumSearch',
    function($scope, ngDialog, CSRF_TOKEN, $http, forumFactory, $rootScope, timeAgo, $window, authFactory, forumSearch)
    {


    //initialize scope variables
    $scope.forumCategoies = [];
    $scope.forum_listing = [];
    $scope.forumData = {};
    $scope.localSearchData = {};

    //By default set page number to 1
    $scope.pageNumber = 1; // initialize page no to 1

    //By default set items per page to 10
    $scope.itemsPerPage = 10; // this should match however many results your API puts on one page

    $scope.pagination = {
        current: 1
    };

    //by default latest should be highlighted
    $scope.activeMenu = 'latest';

    forumSearch.sort_order = $scope.activeMenu;

    //By default forum listing
    angular.element(document).ready(function(){
        $scope.localsInfo = [];

        //dynamic categories for filter and for adding new forum
        $http.get(PUBLIC_PATH+'library/forum-categories')
            .success(function(response) {

                $rootScope.loading = false;
                //$rootScope.loading = false;
                $scope.forumCategoies = response;

                //set default select value for view
                $scope.forumData.filter_category = response[0];

                //set factory variable for category filter
                forumSearch.category_filter = response[0].value;

                //get hash value from url,the one typed from main page search
                var searchText =   decodeURI(window.location.hash.substr(1));

                $('#upper_search').val(searchText);

                //assigns forum upper text factory value,which triggers list forum ajax call
                forumSearch.upper_search_text = searchText;

                //by default send first option as category value
                if(forumSearch.upper_search_text == '') {
                    forumListing({
                        'search_text': searchText,
                        'sort': forumSearch.sort_order,
                        'by_cat_id': forumSearch.category_filter
                    });
                }
            });

        //Get states for dropdown.
        $http.get(PUBLIC_PATH+'get-states')
            .success(function(response) {
                $scope.states = response;
                // console.log(response);
            });

        //Get cities on change of states dropdown.
        $('body').on('change','#states',function(){
            $scope.getCities($(this).val());
        });

    });


    /**
     * Get cities of state
     * @param id (state id)
     * @author hkaur5
     * @return json of cities ({id:1,name:'Rome'})
     */
    $scope.getCities = function(id){
        if(id != '0'){
            $http.get(PUBLIC_PATH+'get-cities/'+id)
                .success(function(response) {
                    $scope.cities = response;
                });
        }
        else
        {
            $scope.cities = '';
        }
    }


    /**
     * Shows popup to add forum.
     * @author rkaur3
     * @author jsingh7 [No need to delay popup to open, simply checkAuthentication and if it fails, just logout user
     * from app, secondly added description box.]
     * @version 1.1
     */
    $scope.newForumPopup = function()
    {
        $scope.ngDialog = ngDialog;
        ngDialog.open({
            animation: true,
            scope:$scope,
            template: 'newForumTemplate',
            controller: 'newForumController',
            closeByDocument: false
        });

        authFactory.checkAuthentication().then(function(response){
            if(response.status == false)
            {
                ngDialog.close();
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
            }
        });
    }


    //check if upper search text for forum has changed
    $scope.$watch(function(){
        return forumSearch.upper_search_text;
    }, function(newValue, oldValue){
        /*console.log(newValue + ' ' + oldValue);
         console.log(forumSearch.upper_search_text);*/

        if(oldValue == '' && newValue == '') {
            $rootScope.loading = false;
            return false;
        }
        else {
            //get forum listing if upper search text is changed
            forumListing({'search_text': newValue,'sort':forumSearch.sort_order,'by_cat_id':forumSearch.category_filter});
            $scope.pagination = {
                current: 1
            };
        }

    });


    //common function  to get latest or trending forums
    $scope.getLatestTrendingForums = function(classActive)
    {
        $scope.activeMenu = classActive;
        forumSearch.sort_order = $scope.activeMenu;

        var data = {'by_cat_id':  forumSearch.category_filter,
            'sort':forumSearch.sort_order,
            'search_text':forumSearch.upper_search_text,
            'from':0};
        forumListing(data);
        $scope.pagination = {
            current: 1
        };

    }

    //get forums acc to category fiter on change
    $scope.getForumsAccCat = function()
    {
        forumSearch.category_filter = $scope.forumData.filter_category.value;
        var data ={'by_cat_id':forumSearch.category_filter,
            'sort':forumSearch.sort_order,
            'search_text':forumSearch.upper_search_text,
            'from':0};
        forumListing(data);

        $scope.pagination = {
            current: 1
        };

    }

    $rootScope.$on("Update", function(event, message, newfcategory ) {
        //check if newly added forum added is of same category selected as filter,then refresh the list on run time
        if(newfcategory == forumSearch.category_filter) {


            $scope.forum_listing = message;

        }

    });


    //common function to get all forums
    function forumListing(data)
    {
        forumFactory.getList(data)
            .then(function (response) {
                $scope.forum_listing = response;
                console.log($scope.forum_listing);
                $scope.totalCount = response.total_count;

            });
    }

    $scope.forumDtlClk = function(forumId)
    {
        authFactory.checkAuthentication().then(function(response){
            $rootScope.loading= false;

            if(response.status == true)
            {
                $window.location.href = 'library/forum-detail/'+forumId;
            }
            else if(response.status == false)
            {
                $rootScope.loading= false;
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
            }
        });
    }

    //on change of pagenumber get forums
    $scope.pageChanged = function(pageNumber)
    {
        var offset = parseInt($scope.itemsPerPage) *(parseInt(pageNumber) - 1);
        var data = {'from':offset,'length':$scope.itemsPerPage,
            'search_text':forumSearch.upper_search_text,
            'by_cat_id':forumSearch.category_filter,
            'sort':forumSearch.sort_order};

        forumListing(data);
    }
}]);



//Add new forum
gillieNetFrontApp.controller('newForumController',['$scope','ngDialog','CSRF_TOKEN','$http','forumFactory','$rootScope','forumSearch','flash', function($scope,ngDialog,CSRF_TOKEN,$http,forumFactory,$rootScope,forumSearch,flash) {

    $scope.forum = {};
    $scope.errors = {};
    $scope.forum.selected_category = {};
    $scope.addForumDisableBtn = false;

        //new forum submit click
        $scope.addForum = function()
        {
            $rootScope.loading = true;
            $scope.addForumDisableBtn = true;
            var data = {
                            'title': $scope.forum.title,
                            'description_formatted': $scope.forum.description,
                           // 'description_plain_text': extractContentFromHTML($scope.forum.description, true),
                            'selected_category': $scope.forum.selected_category.value
                        };

            $http({method: 'POST', url: "library/add-new-forum", data: data}).success(function(response){
                $scope.errors = {};
                console.log(response);
                if(response) {

                    $rootScope.loading = false;
                        var newforum = 'yes';
                        var fdata = {'by_cat_id': forumSearch.category_filter, 'sort': forumSearch.sort_order};

                        forumFactory.getList(fdata, newforum, $scope.forum.selected_category.value)
                            .then(function (response) {

                            }, function (error) {

                            });

                    ngDialog.close();
                    flash.pop({title: 'Success', body: constants['forum_saved'], type: 'success'});
                }
                }).error(function(response){
                    $rootScope.loading = false;
                    $scope.addForumDisableBtn = false;
                    $scope.errors = response;
                });


        }
}]);

gillieNetFrontApp.controller('modalLoginController',['$scope','ngDialog','CSRF_TOKEN','$http', function($scope,ngDialog,CSRF_TOKEN,$http) {

    $scope.loginData = {};
    $scope.errors = {};

    $scope.loginSumbit = function () {

        var data = $scope.loginData;


        $scope.show_message = false;

        $http.post('auth/login',data)
            .success(function(response) {
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

        });
    }
}]);

//============================================Jquery Code================================================//


/**
 * Created by hkaur5 on 11/23/2016.
 */
$(document).ready(function () {


    //On click of search button in header
    $('#upper_search_btn').click(function(){

        //if lcoals tab is active then call search local.
        if($('#local_tab_active').val()==1){
            searchLocals(0, 1);
        }
    });

    $('.page-link')
})

/**
 * On clicking local, bussiness and library tabs showing
 * and hiding divs and performing respective functionality
 * on basis of which tab is clicked.
 * @param current
 * @param max
 * @author hkaur5
 * @author rawatAbhishek
 */
function showTab(current,max){

    //If local tab is clicked then make http call
    // for local search.
    if(current == 3){

        //Set input field value 1 so that
        // functions related to local search can be triggered.
        $('#local_tab_active').val(1);
        searchLocals(0);
    }
    else{
        $('#local_tab_active').val(0, 1);
    }
    for (i=1;i<=max;i++){
        // console.log('tab'+i);

        // getMyHTMLElement('tab'+i).style.display = 'none';
        $('#tab'+i).css('display','none');
        //  getMyHTMLElement('mtab'+i).className = '';
        $('#mtab'+i).removeClass('selected');
    }
    // console.log('tab'+current);
    //   getMyHTMLElement('tab'+current).style.display = '';
    $('#tab'+current).css('display','block');
    // getMyHTMLElement('mtab'+current).className = 'selected';
    $('#mtab'+current).addClass('selected');
}

/**
 * Add/Remove following user.
 * using ajax call
 * @author rawatabhishek
 */
function addAndRemoveFollower(id) {

    $('button#follow'+id).attr("disabled","true");
    if($('button#follow'+id).attr('follow') == "1")
    {

        jQuery.ajax({
            url: PUBLIC_PATH+'follower/remove',
            type: "POST",
            data: {'rowId': $('button#follow' +id).attr("insertId")},
            dataType: "json",
            success:function(response)
            {
                // alert(response);
                $('button#follow' +id).attr("insertId","");
                $('button#follow' +id).text('Follow');
                $('button#follow' +id).removeAttr("disabled");
                $('button#follow' +id).attr('follow',"0");
            }
        })

    }
    else if($('button#follow' +id).attr('follow') == "0")
    {
        jQuery.ajax({
            url: PUBLIC_PATH+'follower/add',
            type: "POST",
            data: {'followerId': id},
            dataType: "json",
            success: function (response) {
                if(response)
                {
                    $('button#follow' +id).text('Unfollow');
                    $('button#follow' +id).attr("insertId", response);
                    $('button#follow' +id).removeAttr("disabled");
                    $('button#follow' +id).attr('follow',"1");
                }
            }
        })
        }


}


/**
 * Search locals ajax and append html.
 * Send all the required params for filtered
 * search.
 * @author hkaur5
 * @param boolean load_more
 *
 */
function searchLocals(offset, currentPage){

    $('span.local_search_loading').show();
    $('ul#locals_listing').html(''); // hide if previously search records are displaying.
    $('span.local_listing_no_records').hide();
    $('span.local_listing_no_search').hide(); // Hide previous no search message.
    var limit = 10;
    var weapons = [];
    var activities = [];
    var properties = [];
    var species = [];
    //console.log($(".activities_list input:checked"));

    $('li.activities_list input:checked').each(function() {
        activities.push($(this).val());
    });
    $('li.weapons_list input:checked').each(function() {
        weapons.push($(this).val());
    });
    $('li.properties_list input:checked').each(function() {
        properties.push($(this).val());
    });
    $('li.species_list input:checked').each(function() {
        // console.log('species:'+$(this).val());
        species.push($(this).val());
    })

    if(species.length == 0
        && activities.length == 0
        && properties.length == 0
        && weapons.length == 0
        && $('#upper_search').val() == ''
        && $('#cities').val() == '0'
        && $('#states').val() == '0')
        {
            $('span.local_search_loading').hide();//hide loading.
            $('.local_listing_no_search').show();
            $('div#local_search_pagination').pagination('destroy');
            return;
        }

    window.location.hash = $('#upper_search').val(); //Update hash value in url.

    // console.log(species);
    jQuery.ajax({
        url: PUBLIC_PATH + "locals/search",
        type: "POST",
        dataType: "json",
        data: {
            'offset': offset,
            'limit': limit,
            'weapons': weapons,
            'species': species,
            'properties': properties,
            'activities': activities,
            'city':$('#cities').val(),
            'state':$('#states').val(),
            'search_text':$('#upper_search').val(),
            'currentPage':currentPage

        },
        timeout: 50000,
        success: function (jsonData) {

            $('span.local_search_loading').hide();//hide laoding.



            if(jsonData['users_info']){

                // console.log(jsonData['users_info']);

                var html = '';
                for(i in jsonData['users_info'])
                {
                    console.log(jsonData['users_info'][i]['fid']);
                    html += '<li>';
                    html += '<div class="local-user-sec">';
                    html += '<a class="profile_photo" href="'+PUBLIC_PATH+'p/'+jsonData['users_info'][i]['id']+'"><img src="'+jsonData['users_info'][i]['image']+'"></a>';
                    html += '<a class="user_name" href="'+PUBLIC_PATH+'p/'+jsonData['users_info'][i]['id']+'">';
                    html += '<p>'+jsonData['users_info'][i]['name']+'</p>';
                    html += '</a>';
                    html += '<p>'+jsonData['users_info'][i]['address']+'</p>';
                    html += '</div>';


                    if(jsonData['users_info'][i]['is_followed'] != 3 && jsonData['users_info'][i]['is_followed'] != 4){

                        html +='<button id="follow'+jsonData['users_info'][i]['id']+'" class="usr_btn btn btn-default"';
                        html += 'onclick="addAndRemoveFollower('+jsonData['users_info'][i]['id']+')"';

                        if(jsonData['users_info'][i]['is_followed'] == 1 ) {
                            html += 'insertid="'+jsonData['users_info'][i]['fid']+'" follow="1">';
                            html += 'UNFOLLOW';
                        }

                        if(jsonData['users_info'][i]['is_followed'] == 0 ) {
                            html += 'insertid="" follow="0">';
                            html += 'FOLLOW';
                        }

                        html += '</button>';
                    }
                    html += '</div>';
                    html += '</li>';

                }

                $('ul#locals_listing').html('');
                $('ul#locals_listing').append(html);

                //Initiate pagination.
                $('div#local_search_pagination').pagination({
                    items: jsonData['total_count'],
                    itemsOnPage: limit,
                    currentPage:jsonData['currentPage'],
                    hrefTextPrefix:'javascript:void(',
                    hrefTextSuffix:');',
                    cssStyle: 'light-theme',
                    onPageClick: function(pageNumber, event){
                        offset = (pageNumber-1)*limit;
                        searchLocals(offset, pageNumber);
                    },
                });
            }else{

                $('ul#locals_listing').html('');
                $('.local_listing_no_records').show();
                $('div#local_search_pagination').pagination('destroy');

            }


        }
    });
}

//============================================Jquery Code ENDS================================================//


