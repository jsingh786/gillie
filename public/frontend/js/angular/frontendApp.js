/**
 * Created by rkaur3 on 8/25/2016.
 */
'use strict';

var gillieNetFrontApp = angular.module('gillieNetworkFrontApp',
    ['ngAnimate','ngDialog','angularUtils.directives.dirPagination','yaru22.angular-timeago','ui.date','textAngular'])

    .constant('CSRF_TOKEN', '{{ csrf_token() }}')

    .config(function($interpolateProvider,$provide){
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');    $provide.decorator('taOptions', ['$delegate', function(taOptions){
            taOptions.toolbar = [
                // ['h2', 'h3'],
                ['p'],
                ['bold', 'italics'],
                ['redo', 'undo', 'clear'],
                ['insertLink'],
                ['wordcount', 'charcount']
            ];
            return taOptions;
        }]);
    })

.animation('.slide-toggle', ['$animateCss', function($animateCss) {
    return {
        addClass: function(element, className, doneFn) {
            if (className == 'ng-hide') {
                var animator = $animateCss(element, {
                    to: {height: '0px', opacity: 0}
                });
                if (animator) {
                    return animator.start().finally(function() {
                        element[0].style.height = '';
                        doneFn();
                    });
                }
            }
            doneFn();
        },
        removeClass: function(element, className, doneFn) {
           
            if (className == 'ng-hide') {
                var height = element[0].offsetHeight;

                var animator = $animateCss(element, {
                    from: {height: '0px', opacity: 0},
                    to: {height: height + 'px', opacity: 1}
                });
                if (animator) {
                    return animator.start().finally(doneFn);
                }
            }
            doneFn();
        }
    };
}])
   /* .factory('forumFactory', ['$http','$q','$injector', function($http,$q,$injector) {

        return{
            getList : function (data,newforum){
                var deferred = $q.defer();
                var rScope = $injector.get('$rootScope');

               rScope.loading = true;
                //return false;
                $http.post('library/forum-listing', data).then(function successCallback(response) {

                    rScope.loading = false;

                    deferred.resolve(response.data);
                    //check if new forum is added then only update listing of forums
                    if(newforum === 'yes') {
                        rScope.$broadcast("Update", response.data);
                    }
                });
                /*, function errorCallback(response) {
                    //return {latitude: 51.536353, longitude: -0.139604, i: i};
                });*/

              /*  return deferred.promise;
            }
        }

        }])*/
    .factory('forumFactory', ['$http','$q','$injector', function($http, $q, $injector) {

        return{
            getList : function (data,newforum,newcategodry){
                var deferred = $q.defer();
                var rScope = $injector.get('$rootScope');

                var url_path = PUBLIC_PATH+'library/forum-listing';

                rScope.loading = true;
                //return false;
                $http.post(url_path, data).then(function successCallback(response) {

                    rScope.loading = false;

                    deferred.resolve(response.data);
                    //check if new forum is added then only update listing of forums
                    if(newforum === 'yes') {
                        rScope.$broadcast("Update", response.data,newcategory);
                    }
                });
                /*, function errorCallback(response) {
                 //return {latitude: 51.536353, longitude: -0.139604, i: i};
                 });*/

                return deferred.promise;
                 }
        }

                 }])

.factory("flash", function($rootScope, $window) {
    var queue = [], currentMessage = {};

    return {
        set: function(message) {
            var msg = message;
            queue.push(msg);

        },
        get: function(message) {
            return message;
        },
        pop: function(message) {
            switch(message.type) {
                case 'success':
                    toastr.success(message.body, message.title);
                    break;
                case 'info':
                    toastr.info(message.body, message.title);
                    break;
                case 'warning':
                    toastr.warning(message.body, message.title);
                    break;
                case 'error':
                    toastr.error(message.body, message.title);
                    break;
            }
        },
        clear: function()
        {
            toastr.clear();
        }
    };
})

.factory('authFactory',['$http', function($http){
    var auth_response = {};
    return {
        checkAuthentication:function(){
           
            return $http.get(PUBLIC_PATH+'auth/check-auth').then(function(result){
                //send authentication response
                auth_response = result.data;
                return auth_response; // this is data ^^ in the controller
            });
        }
    }

}])

    .filter('htmlToPlaintext', function($sce) {
        return function(text) {
           // return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
            return angular.element(text).text();
        };

    })

.factory('noteFactory',['$http','$injector', function($http,$injector){
    //var user_notes = [];

    return {
        add: function (item) {

           var rScope = $injector.get('$rootScope');
             var usernotes =  rScope.user_notes.unshift(item);
            var rScope = $injector.get('$rootScope');
            rScope.$broadcast("Update", usernotes);
        }

        /*get: function (notes_data) {
             $http.post('profile/get-notes',notes_data).success(function(response){

                return list = response;
            });

        }*/
    }
}])

    .service('updateOffset', function () {
        var offset = 0;

        return {
            getOffset: function () {
                return offset;
            },
            setOffset: function (value) {
                offset = value;
            }
        }
    })

.filter('preserveHtml', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
})

.directive('toggle', function() {
        return function(scope, elem, attrs) {
            scope.$on('event:toggle', function() {
                elem.slideToggle();
            });
        };
    })
.factory('forumSearch', function(){

        return {

            upper_search_text: '',
            category_filter:'',
            sort_order:''
        };
    })
//on click enter submit function
.directive('myEnter', function () {
        return function (scope, element, attrs) {
            element.bind("keypress", function (event) {
                if(event.which === 13) {

                    scope.$apply(function (){
                        scope.$eval(attrs.myEnter);
                    });

                    event.preventDefault();
                }
            });
        };
    })
//create dynamic loader
    .directive('myLoader',function(){
      
        var directive ={};

        directive.compile = function(element, attributes) {
         var linkFunction = function($scope, element, attributes) {
             element.html('<img src="'+IMAGE_PATH_FRONTEND+'/processing.svg" width="'+attributes.myLoader+'">');
            }
            return linkFunction;
        }

        return directive;
    })


/**
 * This filter is to get the substring of the string 
 * according to given parameter
 * @param value (string)
 * @param word wise (true or false)
 * @param max (length in integer)
 * @param tail (endding the string to show ontinuity line '...' or '>>>')
 * @author hkaur5
 */
    .filter('cut', function () {
    return function (value, wordwise, max, tail) {
        if (!value) return '';

        max = parseInt(max, 10);
        if (!max) return value;
        if (value.length <= max) return value;

        value = value.substr(0, max);
        if (wordwise) {
            var lastspace = value.lastIndexOf(' ');
            if (lastspace != -1) {
                //Also remove . and , so its gives a cleaner result.
                if (value.charAt(lastspace-1) == '.' || value.charAt(lastspace-1) == ',') {
                    lastspace = lastspace - 1;
                }
                value = value.substr(0, lastspace);
            }
        }

        return value + (tail || ' …');
    };
});

//common controller declaration for thank you dialog/popup
gillieNetFrontApp.controller('thankyouController',['$scope','ngDialog','CSRF_TOKEN','$http', function($scope,ngDialog,CSRF_TOKEN,$http) {

}]);



