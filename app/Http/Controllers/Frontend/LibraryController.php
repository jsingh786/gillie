<?php
/**
 * Created by PhpStorm.
 * User: rkaur3
 * Date: 8/30/2016
 * Time: 10:36 AM
 */
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Repository\forumCategoriesRepo as forumCategoriesRepo;
use App\Repository\forumRepo as forumRepo;
use App\Repository\usersRepo as usersRepo;
use Auth;
use Validator;
use Illuminate\Support\Facades\Input;
use App\Repository\forumCommentsRepo as forumCommentsRepo;
use App\Repository\forumRatingsRepo as forumRatingsRepo;

use App\Repository\activitiesRepo as activitiesRepo;
use App\Repository\propertiesRepo as propertiesRepo;
use App\Repository\speciesRepo as speciesRepo;
use App\Repository\weaponsRepo as weaponsRepo;

use App\Service\Elastic\Forum as elasticSearchForum;
use App\Service\Users\Profile as userProfileService;

class LibraryController extends Controller{

    private $forumCategoriesRepo;
    private $forumRepo;
    private $forumCommentsRepo;
    private $forumRatingsRepo;
    private $elasticSearchForum;

    public function __construct(forumCategoriesRepo $forumCategoriesRepo, forumRepo $forumRepo,
                                forumCommentsRepo $forumCommentsRepo, forumRatingsRepo $forumRatingsRepo,
                                elasticSearchForum $elasticSearchForum,
                                usersRepo $usersRepo,
                                userProfileService $userProfileService,
                                activitiesRepo $activitiesRepo,
                                propertiesRepo $propertiesRepo,
                                speciesRepo $speciesRepo,
                                weaponsRepo $weaponsRepo)
    {
        parent::__construct();
        $this->forumCategoriesRepo = $forumCategoriesRepo;
        $this->forumRepo = $forumRepo;
        $this->usersRepo = $usersRepo;
        $this->forumCommentsRepo = $forumCommentsRepo;
        $this->forumRatingsRepo = $forumRatingsRepo;
        $this->elasticSearchForum = $elasticSearchForum;
        $this->activitiesRepo = $activitiesRepo;
        $this->propertiesRepo = $propertiesRepo;
        $this->speciesRepo = $speciesRepo;
        $this->weaponsRepo = $weaponsRepo;
        $this->userProfileService = $userProfileService;
    }

    public function index(Request $request)
    {
        //Get activities, properties, weapons and species
        $weapons    = $this->weaponsRepo->getAll();
        $properties = $this->propertiesRepo->getAll();
        $activities = $this->activitiesRepo->getAll();
        $species    = $this->speciesRepo->getAll();

        foreach ($weapons as $key=>$weapon)
        {
            $weapons_arr[$key]['id'] =  $weapon->getId();
            $weapons_arr[$key]['name'] =  $weapon->getName();
        }

        foreach ($properties as $key=>$property)
        {
            $properties_arr[$key]['id'] =  $property->getId();
            $properties_arr[$key]['name'] =  $property->getName();
        }

        foreach ($activities as $key=>$activity)
        {
            $activities_arr[$key]['id'] =  $activity->getId();
            $activities_arr[$key]['name'] =  $activity->getName();
        }

        foreach ($species as $key=>$species_item)
        {
            $species_arr[$key]['id'] =  $species_item->getId();
            $species_arr[$key]['name'] =  $species_item->getName();
        }
//dd($activities);
        return view('frontend.search')
            ->with('enable_profile_menu', false)
            ->with('species',$species_arr)
            ->with('activities',$activities_arr)
            ->with('properties',$properties_arr)
            ->with('weapons',$weapons_arr);
    }

    protected function validator(array $data , array $rules, array $messages)
    {
        return Validator::make($data, $rules, $messages);
    }

    /**
     * New forum topic popup
     *
     * @author rkaur3
     * @version 1.0
     * Dated 31-aug-201
     */
    public function addForumTopic()
    {
        return view('frontend.new-forum');
    }

    /**
     * Get forum/library categories
     *
     * @author rkaur3
     * @version 1.0
     * @return json
     * Dated 31-aug-2016
     */
    public function getCategories()
    {
        $categories = $this->forumCategoriesRepo->getAll();
        $catArr = array();
        foreach($categories as $key => $cat) {
            $catArr[$key]['value'] = $cat->getId();
            $catArr[$key]['txt'] = $cat->getName();
        }
        return $catArr;
    }

    /**
     * Saves forum to SQL DB as well as elastic server.
     *
     * @param Request $request
     * @throws \Illuminate\Foundation\Validation\ValidationException
     * @version 1.2
     * @author rkaur3
     * @author rkaur3 [Added ??? in version 1.1]
     * @author jsingh7 [Added forum description in version 1.2]
     * @return Boolean
     */
    public function addNewForum(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'selected_category' => 'required'
        ];
        $messages = [
            'title.required' => \Config::get('constants.forum_title_is_required'),
            'title.regex' => \Config::get('constants.forum_title_should_only_contain_alphabets_numbers_and_spaces'),
            'title.max' => \Config::get('constants.forum_title_should_not_exceed_255_characters'),
            'selected_category.required' => \Config::get('constants.forum_category_is_required'),
        ];

        //call to validator function
        $validator = $this->validator($request->all(), $rules, $messages);

        //If validation fails it through an exception
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $forumData = array();
        $forumData = $request->all();


        $forumData['user_id'] = Auth::user()->getId();
        $forumId = $this->forumRepo->add($forumData);

        //elastic search add forum array.
        $this->elasticSearchForum->addForum($forumId);

        if ($forumId) {
            return json_encode(true);
        } else {
            return json_encode(false);
        }
    }

    /**
     * Saves forum to SQL DB as well as elastic server.
     *
     * @param Request $request
     * @throws \Illuminate\Foundation\Validation\ValidationException
     * @version 1.0
     * @author jsingh7
     * @return Boolean
     */
    public function saveForum(Request $request)
    {
        $rules = [
            'title'                         => 'required|max:255',
            'category'                      => 'required'
        ];
        $messages = [
            'title.required'                => \Config::get('constants.forum_title_is_required'),
            'title.regex'                   => \Config::get('constants.forum_title_should_only_contain_alphabets_numbers_and_spaces'),
            'title.max'                     => \Config::get('constants.forum_title_should_not_exceed_255_characters'),
            'category.required'    => \Config::get('constants.forum_category_is_required'),
        ];

        //call to validator function.
        $validator = $this->validator($request->all(), $rules, $messages);

        //If validation fails it through an exception.
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }


        $params = $request->all();
       // $description_decoded = html_entity_decode($params['description'], null, 'utf-8');
        $forumData['id']                = isset($params['id'])              ? $params['id']             : null;
        $forumData['title']             = isset($params['title'])           ? $params['title']          : null;
        $forumData['description']       = isset($params['description'])     ? $params['description']    : null;
        $forumData['category']          = isset($params['category'])        ? $params['category']       : null;
        $forumData['status']            = isset($params['status'])          ? $params['status']         : null;
        $forumData['activity_at']       = isset($params['activity_at'])     ? $params['activity_at']    : null;
        $forumData['trending']          = isset($params['trending'])        ? $params['trending']       : null;

        if (isset($forumData['id']))
        {
            $forumId = $this->forumRepo->update($forumData);
            //elastic search add forum array.
            $this->elasticSearchForum->addForum($forumId, true);
        }
        else
        {
            $forumId = $this->forumRepo->add($forumData);
            //elastic search add forum array.
            $this->elasticSearchForum->addForum($forumId);
        }

        if ($forumId) {

            $forum_obj = $this->forumRepo->getRowObject(['id',$forumId]);
            $forum_array['title'] = $forum_obj->getTitle();
            $forum_array['title_cropped'] = \Helper_common::showCroppedText($forum_obj->getTitle(), 20);
            $forum_array['description'] = $forum_obj->getDescription();
            $forum_array['category'] = $forum_obj->getForumCategories()->getName();

            return json_encode($forum_array);
        } else {
            return json_encode(false);
        }
    }

    /**
     * Get all forums
     *
     * @return json
     * @author rkaur3
     * @version 1.2
     * Dated 5-oct-2016
     */
    public function forumListing(Request $request){
    
       $forumArr = array();
//       check if listing is to be displayed through main search
//       $main = new \App\Service\Elastic\Main();
//       $elastic_search = new \App\Service\Elastic\Forum(
//           $this->forumCommentsRepo,
//           $this->forumRepo,
//           $this->forumRatingsRepo,
//           $main);


//        $params = Array
//        (
//            'search_text' => '',
//            'sort' => 'latest',
//            'by_cat_id' => 1
//        );
       $searchedForums = $this->elasticSearchForum->searchForumTab($request->all());


       if($searchedForums['total_count'] > 0) {
           foreach ($searchedForums['forums'] as $key => $fl) {
               $forumArr[$key]['id'] = $fl['_id'];
               $forumArr[$key]['title'] = $fl['_source']['title'];
               $time = strtotime($fl['_source']['date_posted']);
               $newformat = date('M d, Y',$time);
               $forumArr[$key]['date_posted'] = $newformat;

               $forumArr[$key]['user_name'] = $fl['_source']['user_name'];
               $forumArr[$key]['user_address'] = $fl['_source']['user_address'];
               $forumArr[$key]['forumCommentsCount'] = $fl['_source']['forumCommentsCount'];
               $forumArr[$key]['rating_stars'] = $fl['_source']['rating_stars'];
               $forumArr[$key]['no_of_views'] = $fl['_source']['no_of_views'];
               $forumArr[$key]['activity_date'] = $fl['_source']['activity_date'];
               $forum_obj = $this->forumRepo->getRowObject(array('id', $fl['_id']));
               if($forum_obj){
               $forumArr[$key]['profileImg'] = $this->userProfileService->getUserProfilePhoto($forum_obj->getUsers()->getId());

               }
               else{
                   $public_path = Config::get('constants.PUBLIC_PATH');
                   $forumArr[$key]['profileImg'] =  $public_path.'/frontend/images/default_prof_photo_male.png';
               }
               if(array_key_exists('user_id',$fl['_source'] ))
               {
                   $forumArr[$key]['forum_user_id'] = $fl['_source']['user_id'];
               }
               else if($forum_obj)
               {
                   $forumArr[$key]['forum_user_id'] = $forum_obj->getUsers()->getId();
               }
               else{
                   $forumArr[$key]['forum_user_id'] = null;
               }
           }
       }
        return json_encode(array("forums"=>$forumArr,"total_count"=>$searchedForums['total_count']));
    }

    public function getAllCategories()
    {
        $forumArr = array();
//       check if listing is to be displayed through main search
//       $main = new \App\Service\Elastic\Main();
//       $elastic_search = new \App\Service\Elastic\Forum(
//           $this->forumCommentsRepo,
//           $this->forumRepo,
//           $this->forumRatingsRepo,
//           $main);


        $params = Array
        (
            'search_text' => '',
            'sort' => 'latest',
            'by_cat_id' => 1
        );
        $searchedForums = $this->elasticSearchForum->searchForumTab($params);
        if($searchedForums['total_count'] > 0) {
            foreach ($searchedForums['forums'] as $key => $fl) {
                $forumArr[$key]['id'] = $fl['_id'];
                $forumArr[$key]['title'] = $fl['_source']['title'];
                $time = strtotime($fl['_source']['date_posted']);
                $newformat = date('M d, Y',$time);
                $forumArr[$key]['date_posted'] = $newformat;
                $forumArr[$key]['user_name'] = $fl['_source']['user_name'];
                $forumArr[$key]['user_address'] = $fl['_source']['user_address'];
                $forumArr[$key]['forumCommentsCount'] = $fl['_source']['forumCommentsCount'];
                $forumArr[$key]['rating_stars'] = $fl['_source']['rating_stars'];
                $forumArr[$key]['no_of_views'] = $fl['_source']['no_of_views'];
                $forumArr[$key]['activity_date'] = $fl['_source']['activity_date'];

            }
        }
        return json_encode(array("forums"=>$forumArr,"total_count"=>$searchedForums['total_count']));
    }

    /**
     * Get forum detail page
     *
     * @author rkaur3
     * @version 1.1
     * @return view
     *
     */

    /**
     * Action to display forum detail page.
     *
     * @author rkaur3
     * @author jsingh7 (Extended the functionality as now we need data to know that is this logged in user's forum.)
     * @author hkaur5 (added code to return $forumDetailArr to blade)
     * @version 1.1
     * @param $forumId
     * @return $this
     */
    public function forumDetail($forumId)
    {
        $forumObj           = $this->forumRepo->getRowObject(['id', $forumId]);
        if(!$forumObj) {
            return redirect('page-not-found');
        }
        $loggedinUserObj    = $this->usersRepo->getRowObject(['id', Auth::user()->getId()]);

        if (count($forumObj->getForumRating()) > 0)
        {
            $forumDetailArr = array();
            $totalStars = 0;
            $numIterations = 0;
            foreach($forumObj->getForumRating() as $fr)
            {
                $rating = $fr->getRatingStars();
                $totalStars = $totalStars + $rating;
                $numIterations++;
            }
            $stars = ($totalStars/$numIterations);
        }
        else
        {
            $stars = 0;
        }

        if ($forumObj) {

            $forumDetailArr['id']               = $forumObj->getId();
            $forumDetailArr['title']            = $forumObj->getTitle();
            $forumDetailArr['title_cropped']    = \Helper_common::showCroppedText($forumObj->getTitle(), 20);
            $forumDetailArr['date_posted']      = date_format($forumObj->getCreatedAt(), 'M d, Y');
            $forumDetailArr['user_name']        = $forumObj->getUsers()->getFirstname() . " " . $forumObj->getUsers()
                                                    ->getLastname();
            $forumDetailArr['user_address']     = $forumObj->getUsers()->getAddress();
            $forumDetailArr['user_id']          = $forumObj->getUsers()->getId();
            $forumDetailArr['description']      = $forumObj->getDescription();
            $forumDetailArr['isMyForum']        = ($forumObj->getUsers()->getId() == $loggedinUserObj->getId())
                                                    ? true : false;
            $forumDetailArr['user_profile_photo'] = $this->userProfileService->getUserProfilePhoto($forumObj->getUsers()->getId());
        }

        //collecting all categories.
        $allCategories      = $this->forumCategoriesRepo->getAll();
        $forumCategories    = array();
        if($allCategories)
        {
            foreach($allCategories as $category)
            {
                $forumCategories[$category->getId()] = $category->getName();
            }
        }

        return view('frontend.forum-detail', compact('stars', 'forumDetailArr', 'forumCategories'))
            ->with('enable_profile_menu', false)
            ->with('forum_id',$forumId);
    }

    /**
     * Get forum details acc to id
     *
     * @param Request $request
     * @return json
     * @author rkaur3
     * @version 1.0
     * Dated 9-sep-2016
     */
    public function getForumDetails(Request $request)
    {
        $params = ['by_forum_id'=>$request->forum_id];

        $forumDetail =  $this->forumRepo->get($params);
        $forumDetailArr = array();

        if ($forumDetail) {
            $forumDetailArr['id']           = $forumDetail[0]->getId();
            $forumDetailArr['title']        = $forumDetail[0]->getTitle();
            $forumDetailArr['description']  = trim($forumDetail[0]->getDescription());
            $forumDetailArr['category_id']  = $forumDetail[0]->getForumCategories()->getId();
            $forumDetailArr['date_posted']  = date_format($forumDetail[0]->getCreatedAt(), 'M d, Y');
            $forumDetailArr['user_name']    = $forumDetail[0]->getUsers()->getFirstname() . " " .
                                                $forumDetail[0]->getUsers()->getLastname();
            $forumDetailArr['user_address'] = $forumDetail[0]->getUsers()->getAddress();
            $forumDetailArr['user_profile_photo'] = $this->userProfileService->getUserProfilePhoto(
                                                                            $forumDetail[0]->getUsers()->getId());
            return json_encode(array("forum_detail" => $forumDetailArr));
        } else {
            return false;
        }
    }

    /**
     * Add comment and update trending and activity of forum
     *
     * @param array ['forum_id','comment']
     * @return json comment details
     * @author rkaur3
     * @version 1.1
     * Dated 29-sep-2016
     */

    public function addComment(Request $request){

        $params = array();
        $params = $request->all();

        if(Auth::check())
        {
            $rules = [
                'comment' => 'required'
            ];

            $messages = [
                'comment.required' => 'Enter comment'
            ];
            //call to validator function
            $validator = $this->validator($request->all(), $rules, $messages);

            //If validation fails it throughs an exception
            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }

            $params['user_id'] = Auth::user()->getId();
            $cmmentAdd = $this->forumCommentsRepo->add($params);

            $params['activity_at'] = new \DateTime();

            //update activity date trending of forum
            $forum_obj = $this->forumRepo->getRowObject(['id', $params['forum_id']]);
            $views = $forum_obj->getNoOfViews();
            $no_of_comments = $forum_obj->getForumComments()->count();
            $trending = ($views + $no_of_comments);
            $params['id'] = $params['forum_id'];
            $params['views'] = $views;
            $params['trending'] = $trending;
            $result = $this->forumRepo->update($params);

            $this->elasticSearchForum->updateForumComments($params['forum_id']);

            //update forum average rating in elastic document
            $this->elasticSearchForum->addForum($request->forum_id,
                ['activity_date'=>$params['activity_at']->format(\DateTime::ATOM),'no_of_views'=>$views,'trending'=>$trending]);
            
            return json_encode($cmmentAdd);
        }
        else
        {
            return json_encode(array('status'=>301,"message"=>"Please login & try again"));
        }
    }

    public function getComments(Request $request)
    {
        $forum_id  = $request->forum_id;
        //offset set to 0 by default
       $start          = (null !== Input::get('offset')) ? Input::get('offset') : 0;
        // length/limit specified for listing records
        $length         = (null !== Input::get('length')) ? Input::get('length') : 10;
        $forumComments = $this->forumCommentsRepo->get( $forum_id, $length, $start );

        $forumCmtArr = array();
         if(!empty($forumComments['forum_comments'])) {
               foreach ($forumComments['forum_comments'] as $key => $comments) {

                   $forumCmtArr[$key]['id'] = $comments->getId();
                   $forumCmtArr[$key]['message'] = $comments->getMessage();
                   if(Auth::user()->getId() == $comments->getUsers()->getId())
                   {
                       $forumCmtArr[$key]['comment_by_user'] = "You";
                   }
                   else
                   {
                       $forumCmtArr[$key]['comment_by_user'] = $comments->getUsers()->getFirstname()." ".$comments->getUsers()->getLastname();
                   }
                       if(Auth::user()->getId() == $comments->getUsers()->getId()){
                       $forumCmtArr[$key]['isMyComment'] = 1;
                   }else{
                       $forumCmtArr[$key]['isMyComment'] = 0;
                   }

                   $forumCmtArr[$key]['posted_date'] = $comments->getCreatedAt()->format(\DateTime::ATOM);
                   $forumCmtArr[$key]['user_profile_photo'] = $this->userProfileService->getUserProfilePhoto($comments->getUsers()->getId());
                   $forumCmtArr[$key]['comment_by_user_id'] = $comments->getUsers()->getId();
               }
           }
        //get total count of  active forums
        $commentsCount = $this->forumCommentsRepo->getCount( $forum_id );

        //return json_encode($forumCmtArr);
        return json_encode(array('comments'=>$forumCmtArr,
            'total_count'=>$commentsCount,
            'is_more_records'=>$forumComments['is_more_records']));

    }

    /**
     * Add/override rating of forum
     *
     * @return json
     * @author rkaur3
     * @version 1.1
     * Dated 10-oct-2016
     */
    public function addForumRating(Request $request)
    {

        $params = array();
        if (Auth::check()) {
            $params['forum_id'] = $request->forum_id;
            $params['rating'] = $request->rating;
            $params['user_id'] = Auth::user()->getId();
            //check if rating already given
            $rating = $this->forumRatingsRepo->check($params);
            //override rating
            if (!empty($rating)) {
                $params['rating_id'] = $rating[0]['id'];
                $this->forumRatingsRepo->update($params);

                $avgRating =  $this->forumRepo->getAvgRating($params['forum_id']);

                //update forum average rating in elastic document
                $this->elasticSearchForum->addForum($request->forum_id, ['rating_stars'=>$avgRating]);

                return json_encode(array('status'=>200,'message'=>"Success",'avg_rating'=>$avgRating));

            } //add rating
            else {
                $forumRating = $this->forumRatingsRepo->add($params);
                $avgRating =  $this->forumRepo->getAvgRating($params['forum_id']);
                //update forum average rating in elastic document
                $this->elasticSearchForum->addForum($request->forum_id, ['rating_stars'=>$avgRating]);
                return json_encode(array('status'=>200,'message'=>"Success",'avg_rating'=>$avgRating));
            }

        }
        else
        {
            return json_encode(array('status'=>301,'message'=>'Please login and try again'));
        }

    }

    /**
     * Get average rating of forums
     *
     * @param integer type $forumId
     * @return round avg rating calculation
     * @author rkaur3
     * @version 1.0
     * Dated 14-sep-2016
     */

   /* public function getAvgRating($forumId)
    {
        $forumObj = $this->forumRepo->getRowObject(['id',$forumId]);
        if (count($forumObj->getForumRating()) > 0)
        {
            $forumDetailArr = array();
            $totalStars = 0;
            $numIterations = 0;
            foreach($forumObj->getForumRating() as $fr)
            {
                $rating = $fr->getRatingStars();
                $totalStars = $totalStars + $rating;
                $numIterations++;
            }
            $stars = ($totalStars/$numIterations);
        }
        else
        {
            $stars = 0;
        }
        return round($stars);
    }
    */
    public function updateViews($forum_id)
    {

        $params = array();
        $forum_obj = $this->forumRepo->getRowObject(['id',$forum_id]);
        $views = ($forum_obj->getNoOfViews()+1);
        $no_of_comments = $forum_obj->getForumComments()->count();
        $trending = ($views + $no_of_comments);
        $params['id'] = $forum_id;
        $params['views'] = $views;
        $params['trending'] = $trending;
        $result = $this->forumRepo->update($params);
        //update forums
        $this->elasticSearchForum->addForum($forum_id, ['no_of_views'=>$views, 'trending'=>$trending]);

        return $result;
    }

    /**
     * Removes forum, its comments from
     * MySQL DB and elastic search.
     *
     * @param Request $request (Request is just used for checking the request method.)
     * @param $id
     * @return string
     * @author jsingh7
     * @version 1.0
     */
    public function removeForum(Request $request, $id)
    {
        if($request->getMethod() == 'DELETE')
        {
            if($this->forumRepo->remove($id))
            {
                $this->elasticSearchForum->delete($id);
                //Success.
                return json_encode(1);
            }
            else
            {
                //Send response, that something went wrong.
                return json_encode(0);
            }
        }
        else
        {
            //Send response, that request method was wrong.
            return json_encode(2);
        }
    }

    /**
     * Removes forum-comment from
     * MySQL DB and elastic search.
     *
     * @param Request $request (Request is just used for checking the request method.)
     * @param $id
     * @return string
     * @author jsingh7
     * @version 1.0
     */
    public function removeComment(Request $request, $id)
    {
        if($request->getMethod() == 'DELETE')
        {
            $result = $this->forumCommentsRepo->remove($id);
            if($result)
            {
                $this->elasticSearchForum->updateForumComments($result['forumId'], false);
                //Success.
                return json_encode(1);
            }
            else
            {
                //Send response, that something went wrong.
                return json_encode(0);
            }
        }
        else
        {
            //Send response, that request method was wrong.
            return json_encode(2);
        }
    }

    /**
     * Updates the comment text in MySQL DB
     * and Elastic DB.
     *
     * @param Request $request (two parameters have been posted with names: comment_text, comment_id)
     * @return string
     * @author jsingh7
     * @version 1.0
     */
    public function updateComment(Request $request)
    {
        if($request->getMethod() == 'POST') {
            $params = $request->all();
            $forumCommentObj = $this->forumCommentsRepo->getRowObject(['id', $params['comment_id']]);
            $result = $this->forumCommentsRepo->update($params['comment_id'], $params['comment_text'] );

            //Update elastic comments.
            if($forumCommentObj && $forumCommentObj->getForum())
            {
                $this->elasticSearchForum->updateForumComments($forumCommentObj->getForum()->getId(), false);
            }
            
            if($result == 1) //Updated
            {
                return json_encode(1);
            }
            else if($result == 0) //No change required.
            {
                return json_encode(0);
            }
            else //Exception
            {
                return json_encode(2);
            }
        }
    }
}