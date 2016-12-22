<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use App\Repository\newsRepo as newsRepo;
use Validator;

class NewsController extends Controller{

    /*
     * Guard for admin
     */
    protected $guard = 'admin';

    public function __construct(EntityManager $em,newsRepo $newsRepo)//todo Add space after punctuation mark.
    {
        $this->newsRepo = $newsRepo;
        $this->middleware('admin');
    }


    protected function validator(array $data , array $rules)//todo Remove space before punctuation mark.
    {
        return Validator::make($data,$rules);//todo Add space after punctuation mark.
    }

  /*
   * [Manage News Page View]
   *
   * @uthor kaurGuneet
   * @version 1.0
   * @date 21/06/2016
   *
   *
   */
    public function getIndex(){
        return view('backend/manage-news');
    }

    /*
    * [Get all  added news api]
    *
    * @uthor kaurGuneet
    * @version 1.0
    * @date 21/06/2016
    */
    public function getNews(Request $request){
        try {
            $searchParam = (null !== Input::get('searchParam'))? Input::get('searchParam'): '';
            $start = (null !== Input::get('start'))? Input::get('start'): 0;
            $length = (null !== Input::get('length'))? Input::get('length'): 1;

            
            $news = $this->newsRepo->getAllNews($request->all(), $searchParam, $start, $length);

            $list['recordsTotal'] = $this->newsRepo->getNewsCount();
            if ($searchParam != "") {
                $list['recordsFiltered'] = count($news);
            } else {
                $list['recordsFiltered'] = $this->newsRepo->getNewsCount();
            }

            $list['draw'] = (Input::has('draw')? (int)Input::get('draw'): 0);
            $list['data'] = $news;

            $newsList = json_encode($list);

            return $newsList;
        }catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Something went wrong!!']);
        }
    }



    /*
    * [Get add news form page]
    *
    * @uthor kaurGuneet
    * @version 1.0
    * @date 20/06/2016
    *
    *
    */
    public function getAddNews(){
        return view('backend/add-news');
    }

    /*
    * [Add new news api]
    *
    * @author kaurGuneet
    * @version 1.0
    * @date 20/06/2016
    *
    */
    public function postAddNews(Request $request){
            //Validation Rules for change password form
            if($request->newsId){
                $rules = [
                    'title' => 'required|max:50|regex:/^[a-zA-Z0-9\-\s]+$/',
                    'description' => 'required'
                ];

            }else {
                $rules = [
                    'title' => 'required|max:50|regex:/^[a-zA-Z0-9\-\s]+$/',
                    'description' => 'required',
                    'file' => 'required'
                ];
            }
            //call to validator function
            $validator = $this->validator($request->all(), $rules);

            //If validation fails it throughs an exception
            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }
            try {
                $news = $this->newsRepo->saveNews($request->all());
                if($news == 'Updated'){
                    return Response::json(['status' => 'success', 'msg' => 'Updated Successfuly !!']);
                }else if($news == 'Invalide-Fomate'){
                    return Response::json(['status' => 'error', 'error' => 'Image must be of format jpg, png or gif.']);
                }else if ($news) {
                    return Response::json(['status' => 'success', 'msg' => 'Added Successfuly !!']);
                } else {
                    return Response::json(['status' => 'error', 'error' => 'Opps!! Something went wrong']);
                }
            }catch (\Exception $e) {
                return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
            }


    }

    /*
    * [ Delete news api]
    *
    * @author kaurGuneet
    * @version 1.0
    * @date 22/06/2016
    *
    */

    public function postDeleteNews(Request $request){
        try {
            $data = $request->all();
            $newsId = $data['news_id'];
            if($newsId != ""){
                $newsDeleted = $this->newsRepo->deleteNews($newsId);
            }
            if ($newsDeleted) {
                return Response::json(['status' => 'success', 'msg' => 'Deleted Successfuly !!']);//todo Make a separate helper class for string values.
            } else {
                return Response::json(['status' => 'error', 'error' => 'Opps!! Something went wrong']);//todo Make a separate helper class for string values.
            }
        }catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }
    }

    /*
     * [edit news view]
     * @author kaurGuneet
     * @version 1.0
     * @date 23/06/2016
     *
     */

    public function getEditNews($news_id){
        return view('backend/add-news',compact('news_id'));
    }

    /*
     *[Get news Detail]
     *@author: kaurGuneet
     *@version:1.0
     * @date: 23/06/2016
     */

    public function getNewsDetail(Request $request)
    {
        $news = $this->newsRepo->getNews($request->news_id);

        $newsArr =  array();
        $newsArr['title'] = $news->getTitle();
        $newsArr['description'] = $news->getDescription();
        $newsArr['image'] = $news->getImage();

        return json_encode($newsArr);
    }

    /*
     *[Get news Detail View]
     *@author: kaurGuneet
     *@version:1.0
     * @date: 23/06/2016
     */

    public function getNewsDetailView($news_id)
    {
        return view('backend/view-news',compact('news_id'));
    }

}
