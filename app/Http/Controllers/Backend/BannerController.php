<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use App\Repository\bannerRepo as bannerRepo;
use Validator;

class BannerController extends Controller
{

    /*
     * Guard for admin
     */
    protected $guard = 'admin';

    public function __construct(bannerRepo $bannerRepo)
    {
        $this->bannerRepo = $bannerRepo;
        $this->middleware('admin');
    }


    protected function validator(array $data, array $rules)
    {
        return Validator::make($data, $rules);
    }

    /*
  * [Manage Banner Page View]
  *
  * @uthor kaurGuneet
  * @version 1.0
  * @date 23/06/2016
  *
  *
  */

    public function getIndex(){
        return view('backend/manage-banner');
    }


    /*
    * [Get all  added banners api]
    *
    * @uthor kaurGuneet
    * @version 1.0
    * @date 24/06/2016
    *
    *
    */

    public function getBanners(Request $request){
        try {
           
            $searchParam = (null !== Input::get('searchParam')) ? Input::get('searchParam') : '';
            $start = (null !== Input::get('start')) ? Input::get('start') : 0;
            $length = (null !== Input::get('length')) ? Input::get('length') : 1;

            $banner = $this->bannerRepo->getAllBanner($request->all(), $searchParam, $start, $length);

            $list['recordsTotal'] = $this->bannerRepo->getBannerCount();
            if ($searchParam != "") {
                $list['recordsFiltered'] = count($banner);
            } else {
                $list['recordsFiltered'] = $this->bannerRepo->getBannerCount();
            }

            $list['draw'] = (Input::has('draw') ? (int)Input::get('draw') : 0);
            $list['data'] = $banner;
            $bannerList = json_encode($list);
            return $bannerList;
        }catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }
    }

    /*
   * [Get add banner form page]
   *
   * @uthor kaurGuneet
   * @version 1.0
   * @date 23/06/2016
   *
   *
   */


    public function getAddBanner()
    {
        return view('backend/add-banner');
    }

    /*
    * [Add Banner API]
    *
    * @author kaurGuneet
    * @version 1.0
    * @date 23/06/2016
    *
    */

    public function postAddBanner(Request $request)
    {
        //Validation Rules for change password form
        if($request->bannerId){
            $rules = [
                'title' => 'required|max:50|regex:/^[a-zA-Z0-9\-\s]+$/',
                'description' => 'required|max:255'
            ];

        }else {
            $rules = [
                'title' => 'required|max:50|regex:/^[a-zA-Z0-9\-\s]+$/',
                'description' => 'required|max:255',
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
            $banner = $this->bannerRepo->saveBanner($request->all());

            if($banner == 'Updated'){
                return Response::json(['status' => 'success', 'msg' => 'Updated Successfuly !!']);
            }else if($banner == 'Invalide-Fomate'){
                return Response::json(['status' => 'error', 'error' => 'Image must be of format jpg, png or gif.']);
            }
            else if ($banner) {
                return Response::json(['status' => 'success', 'msg' => 'Added Successfuly !!']);
            } else {
                return Response::json(['status' => 'error', 'error' => 'Oops! Something went wrong.']);
            }

        }catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }

    }

    /*
     * [edit banner view]
     * @author kaurGuneet
     * @version 1.0
     * @date 24/06/2016
     *
     */

    public function getEditBanner($banner_id){
        return view('backend/add-banner',compact('banner_id'));
    }

    /*
     *[Get banner Detail]
     *@author: kaurGuneet
     *@version:1.0
     * @date: 24/06/2016
     */

    public function getBannerDetail(Request $request)
    {

        $banner = $this->bannerRepo->getBanner($request->banner_id);

        $bannerArr =  array();
        $bannerArr['title'] = $banner->getTitle();
        $bannerArr['description'] = $banner->getDescription();
        $bannerArr['image'] = $banner->getImage();
        $bannerArr['status'] = $banner->getStatus();

        return json_encode($bannerArr);
    }

    /*
     *[Get banner Detail View]
     *@author: kaurGuneet
     *@version:1.0
     * @date: 24/06/2016
     */

    public function getBannerDetailView($banner_id)
    {
        return view('backend/view-banner',compact('banner_id'));
    }

    /*
   * [ Delete banner api]
   *
   * @author kaurGuneet
   * @version 1.0
   * @date 24/06/2016
   *
   */

    public function postDeleteBanner(Request $request)
    {
        try {
            $data = $request->all();
            $bannerId = $data['banner_id'];
            if($bannerId != ""){
                $bannerDeleted = $this->bannerRepo->deleteBanner($bannerId);
            }
            if ($bannerDeleted) {
                return Response::json(['status' => 'success', 'msg' => 'Deleted Successfuly !!']);
            } else {
                return Response::json(['status' => 'error', 'error' => 'Oops! Something went wrong']);
            }
        }catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }
    }



}
?>