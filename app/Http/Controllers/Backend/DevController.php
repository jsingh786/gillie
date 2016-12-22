<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repository\PostRepo as repo;
use Doctrine\ORM\EntityManager;
use App\Repository\userHuntingLandRepo as uhrepo;
use App\Repository\usersRepo as usersRepo;
use App\Repository\userWeaponsRepo as userWeaponsRepo;
use App\Repository\followwRepo as followwRepo;
use App\Service\Wallposts\Wallposts as wallpostsService;
use App\Repository\wallpostRepo as wallpostRepo;

use App\Service\Elastic\Users as elasticSearchUsers;
use App\Service\Elastic\Main as elasticSearchMain;
use App\Entity;
use Config;
use Auth;

//use App\Providers\ElasticServiceProvider as elasticServiceProvider;
use App\Service\Elastic\Forum as elasticSearch;


class DevController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;
    private $repo;

    private $elasticServiceProvider;
    private $elasticSearch;

    public function __construct(usersRepo $usersRepo,
                                repo $repo,
                                EntityManager $em,
                                uhrepo $uhrepo,
                                userWeaponsRepo $weapons,
                                elasticSearch $elasticSearch,
                                followwRepo $followwRepo,
                                elasticSearchUsers $elasticSearchUsers,
                                elasticSearchMain $elasticSearchMain,
                                wallpostsService $wallpostsService,
                                wallpostRepo $wallpostRepo

    )
    {
        $this->userRepo = $usersRepo;
        $this->repo = $repo;
        $this->em = $em;
        $this->uhrepo = $uhrepo;
        $this->weapons =  $weapons;

        $this->elasticSearch = $elasticSearch;
        $this->followwRepo = $followwRepo;
        $this->elasticSearchUsers = $elasticSearchUsers;
        $this->elasticSearchMain = $elasticSearchMain;
        $this->wallpostRepo = $wallpostRepo;
        $this->wallpostsService = $wallpostsService;


    }

    public function getIndex(){
        /*$obj = $this->repo->postOfId(1);
        echo $obj->getTitle();*/
    }

    public function getGenerateDB()
    {
        $entities_path = app_path('Entity');
        $d = dir($entities_path) or die("Wrong path: $entities_path");

        /*while (false !== ($entry = $d->read()))
        {
            if($entry != '.' && $entry != '..' && !is_dir($entry))
                $fileNames[] = str_replace(".php","",$entry);
        }*/

        // generate database from model files...
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
       /* foreach($fileNames as $key=>$v)
        {*/

            $filepaths = "App\\Entity\\wallpost_comments";
            $classes = array(
                $this->em->getClassMetadata($filepaths)
            );
            $tool->createSchema($classes);
        /*}*/

        $d->close();

        echo "<h2 style ='color:green'>Tables generated successfully.</h2><br>";
        echo "<img src = '".asset('backend/images/doctrine2.png')."'><br>";
        echo "<h3>Want to know more about Doctrine-2 ORM?<br> Visit : <a href ='http://doctrine-orm.readthedocs.org/en/latest/'>http://doctrine-orm.readthedocs.org/en/latest/</a><h3>";
        die;
    }

    public function getGenerateProxies()
    {
        $proxyFactory = $this->em->getProxyFactory();
        $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        $proxyFactory->generateProxyClasses($metadatas, storage_path('proxies'));

        echo "<h2 style ='color:green'>Your doctrine proxies has been created.</h2>";

        echo "<img src = '".asset('backend/images/doctrine.png')."'>";

        echo "<h3>What are doctrine proxies?";
        echo "<h3>A Doctrine proxy is just a wrapper that extends an entity class to provide Lazy Loading for it.

 By default, when you ask the Entity Manager for an entity that is associated with another entity, the associated entity won't be loaded from the database, but wrapped into a proxy object. When your application then requests a property or calls a method of this proxied entity, Doctrine will load the entity from the database (except when you request the ID, which is always known to the proxy).

 This happens fully transparent to your application due to the fact that the proxy extends your entity class.

 Doctrine will by default hydrate associations as lazy load proxies if you don't JOIN them in your query or set the fetch mode to EAGER.

    	<h3>";

        echo "<img src = '".asset('backend/images/doctrine_lazy_loading.png')."'>";

        echo "<h3>Want to know more about doctrine proxy classes?<br> Visit : <a href ='http://doctrine-orm.readthedocs.org/en/latest/reference/advanced-configuration.html'>http://doctrine-orm.readthedocs.org/en/latest/reference/advanced-configuration.html</a><h3>";

        die;
    }

    /**
     * Use this when using autogenerateproxies false,
     * After altering that database.
     *
     * @author jsingh7
     *
     */
    public function clearApcCache()
    {
        /*$cacheDriver = new \Doctrine\Common\Cache\ApcCache();
        $cacheDriver->flushAll();*/
        apcu_clear_cache();

        echo "<h2 style ='color:green'>Your APC cache has been cleared for doctrine.</h2>";

        echo "<h3>Want to know more about APC cache?<br> Visit : <a href ='http://php.net/manual/en/book.apc.php'>http://php.net/manual/en/book.apc.php</a><h3>";

        echo "<img src = '".asset('backend/images/php-apc-cache.png')."'>";
        die;
    }

    /**
     * Test code for debugging.
     */
    public function testing()
    {
        $obj = $this->wallpostRepo->getRowObject(['id',49]);
        $this->wallpostsService->getWallpostInfo($obj);
        die;

//        $result   = $this->followwRepo->remove(10);
//        die;

//        $user_obj =  $this->userRepo->getRowObject(array('id', 3));
//        $follows = $user_obj->getUserFollowed();
//        foreach ($follows as $follow)
//        {
//            echo $follow->getFollowerUser()->getId();
//            echo '<br>';
//        }
//        die;
      /*  $this->weapons->delete(2);
        die;*/
/*        $this->uhrepo->delete(2);

        die;
        echo Config::get('constants.PUBLIC_PATH').'/image.php?width=149&height=109&cropratio=2:1.4&image='.Config::get('constants.PUBLIC_PATH').'/frontend/images/add-new.png';
        die;
        phpinfo();
        die;
        
        //Don't remove this code!!!
       /* $video_name = 'video.simer_name.mp4';*/


     /*   $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');
        $logged_in_user_id = Auth::Id();
        $thumb_name = 'big_buck_bunny_720p_5mb__1475501719.jpg';*/
        /*echo $thumb_name;
        die;*/
        /*$cmd = 'C:\ffmpeg\bin\ffmpeg  -i  '.$server_public_path.'\frontend\videos\gallery\user_'.$logged_in_user_id.'\\' .$video_name.' -ss 00:00:15.435 -vframes 1 '.$server_public_path.'\frontend\videos\gallery\user_'.$logged_in_user_id.'\\thumbnails\\' .$thumb_name;
        //$cmd = 'ffmpeg -ss 00:03:00 -i video.mp4 -t 60 -c copy -avoid_negative_ts 1 cut.mp4';
       // $cmd = 'C:\ffmpeg\bin\ffmpeg -i '.$server_public_path.'\frontend\images\Wildlife__1475060959.wmv -ss 00:00:14.435 -vframes 1 '.$server_public_path.'\frontend\images\frame.jpg';
        exec($cmd." 2>&1", $out, $ret);
        if ($ret){
            echo "There was a problem!\n";
            echo "<pre>";
            print_r($out);
        }else{
            echo "Everything went better than expected!\n";
        }

        die;*/

        /* $obj = new \App\Repository\forumRepo($this->em);
         $result = $obj->forumListing(['by_forum_id'=>10]);
         \Doctrine\Common\Util\Debug::dump($result);
         die();*/
       // $this->elasticServiceProvider->test();
       /* $this->elasticSearch->test();*/
       // die('fdgd');
        return view('backend.dev.testing');

    }

    public function testingElastic()
    {
        echo '<pre>';
        $this->elasticSearch->get(86);
    }

    public function testingUserElastic()
    {
   /*     $this->elasticSearchUsers->getResponse();
            die;
        $user_obj = $this->userRepo->getRowObject(['id',1]);*/
        /*echo "<pre>";
        print_r($this->elasticSearchUsers->getResponse());*/

    }
    public function createElasticSearchIndex()
    {
        $this->elasticSearchMain->createIndex();
    }

}
?>