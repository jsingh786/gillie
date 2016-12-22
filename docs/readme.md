CONFIGURATION
=============

###Dependencies
1.Run composer install to add all dependencies in vendor folder.

#####Do not  copy vendor folder as it will take several minutes to upload.

### CONFIG FILES and CONSTANTS

1. Below are the files where we have defined PHP and JS constants:
-index.php
-config/constants.php
-public/common/js/constant.js

While hosting project update constants in above 3 files accordingly.

### .htaccess file changes

While hosting project make below changes in htaccess file:
1. Add below line according to base URL:
RewriteEngine On
RewriteBase /gillienetwork/client/public/





### FFMPEG

1.Install ffmpeg on server for video thumbnail functionality. Install ffmpeg from https://ffmpeg.zeranoe.com/builds/
2.Give path of ffmpeg.exe in video controller -> postVideos() function.
3.Install ffmpeg according to windows architecture.




### ELASTIC SEARCH

##### Installation steps, version and all about elastic search.

1. Give correct host name in App/Service/Elastic/Main.php
2. Add network.bind_host: 0 in C:\elasticsearch-2.3.4\config\elasticSearch.yml (add this line under #network.host: 192.168.0.1)
3. Use below route to create new index after specifying index name constant 
    in App/Service/Elastic/Constants.php :
    dev/create-elastic-index

#####Version:
We are using elasticsearch-2.4.0

#####Server Setup Files:
gillie\assets\elasticsearch-2.4.0.zip

#####Documentation: 
https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_overview.html



### PUSHER for Push notifications

Please add these API keys into your ENV file.
PUSHER_APP_ID="268283"
PUSHER_KEY="68fdc4db98a39b3ee72c"
PUSHER_SECRET="72418069ed0a72f08e45"


###All About PHP and MYSQL we need for this project. PHP.ini settings.

Coming soon...



HELP SECTION
============

### COMMANDS
Clear Laravel cache files
* php artisan clear-compiled

Clear cache files due to blade templating engine.
* php artisan view:clear

https://laravel.com/docs/5.2/controllers#route-caching
* php artisan route:cache
* php artisan route:clear

https://laravel.com/docs/5.2/controllers#restful-resource-controllers
* php artisan make:controller PhotoController --resource

### JW PLAYER
We have used JW player in video section.
Jw player provide customized library for jwplayer plugin. Once you login to your account
and create custom settings they provide url for library. You can make changes to that library
by logging in again and go to players section in left panel -> go to manage -> go to 480X270 option -> Save
settings and copy url for library.

1.Login to https://dashboard.jwplayer.com/#/welcome
2. credentials: email:karansamra@gmail.com, password: mind@123


### Croppic Plugin
We have used croppic(www.croppic.net) for profile photo uploading.
For backend functions that work in combination of croppic we have used:
https://tuts.codingo.me/upload-and-edit-image-using-croppic-jquery-plugin -->
1. It provide two function for controller and used image intervention package for saving edited files to folders.
2. For more clear picture you may download whole project from https://github.com/codingo-me/laravel-croppic on system
and run:composer require intervention/image and run that project and check flow.

Warning: Do not replace croppic.js file from internet. As we have included customised code in this file:
1. To remove cross icon once picture is uplaoded. (onAfterCrop)
2. To add previous image on click of remove button. (onReset)
3. Added jquery UI dialogs instead of alert.


### GEDMO
1. Gedmo changes in doctrine entity classes. @Harsimer
--Users

### How to configure doctrine with Laravel? @Guneet Kaur


### Laravel and Doctrine
We are using laravel 5.2 and Doctrine 2



http://www.laraveldoctrine.org/docs/1.2/orm