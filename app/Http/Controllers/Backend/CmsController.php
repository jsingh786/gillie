<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;

use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use LaravelDoctrine\ORM\Exceptions\ExtensionNotFound;
use Mockery\CountValidator\Exception;
use Validator;
use App\Repository\cmsRepo as cmsRepo;

class CmsController extends Controller{

    /**
     * @var cmsRepo
     */
    private $cmsRepo;

    public function __construct(cmsRepo $cmsRepo)
    {
        $this->cmsRepo = $cmsRepo;
        $this->middleware('admin');
    }

    protected function validator(array $data , array $rules, array $messages)
    {
        return Validator::make($data,$rules,$messages);
    }

    /**
     * Get all cms pages
     *
     * @param array Request $request
     * @return json
     * @author rkaur3
     * @version 1.0
     * Dated 22 june,2016
     */
    public function index(Request $request)
    {

        if($request->ajax())
        {
            $searchParam = (null !== Input::get('searchParam')) ? Input::get('searchParam') : '';
            $start = (null !== Input::get('start')) ? Input::get('start') : 0;
            $length = (null !== Input::get('length')) ? Input::get('length') : 1;
            $cmsData = $this->cmsRepo->getAllCmsPages($request->all(),$searchParam,$start,$length);
            $cms_arr = array();
            foreach($cmsData as $key=>$cms)
            {
                $cms_arr[$key]['id'] = $cms->getId();
                $cms_arr[$key]['slug'] = $cms->getSlug();
                $cms_arr[$key]['title'] = $cms->getTitle();
                $cms_arr[$key]['description'] = $cms->getDescription();
                $cms_arr[$key]['status'] = $cms->getStatus();
                $cms_arr[$key]['updated_at'] = $cms->getUpdatedAt();
            }
            $list['recordsTotal'] = $this->cmsRepo->getTotalCount();
            if($searchParam !="")
            {
                $list['recordsFiltered'] = count($cmsData);
            }
            else
            {
                $list['recordsFiltered'] =  $this->cmsRepo->getTotalCount();
            }
            $list['recordsTotal'] = $this->cmsRepo->getTotalCount();
            $list['draw'] = (Input::has('draw') ? (int)Input::get('draw') : 0);
            $list['data'] =  $cms_arr;
            $cmsList = json_encode($list);
            return $cmsList;
        }
        return view('backend/manage-cms');
    }

    /**
     * Manages view for add/edit page
     *
     * @param integer $pageId(optional)
     * @return view
     * @author rkaur3
     * @version 1.0
     * Dated 22june,2016
     */

    public function addPage($pageId = null)
    {
        return view('backend/add-page',compact('pageId'));
    }

    /**
     * @param  array Request $request
     * @return json
     * @author rkaur3
     * @version 1.0
     * Dated 22nd June,2016
     */
    public function savePage(Request $request)
    {
        $cmsData = $request->all();

        $rules = [
            'title' => 'required|max:50|regex:/^[a-zA-Z0-9\-\s]+$/',
            'description' => 'required',
            'status' => 'required'
        ];

        $messages = [
            'title.required' => 'Title is required',
            'title.regex' => 'Only alphanumeric and spaces are allowed',
            'title.max' => 'Max 50 characters are allowed',
            'description.required' => 'Description is required',
            'status.required' => 'Status is required'
        ];

        //call to validator function
        $validator = $this->validator($request->all(), $rules, $messages);

        //If validation fails it throughs an exception
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        try {
            $pageId = $this->cmsRepo->savePage($cmsData);

        }catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }
        if($pageId)
        {
            if(isset($cmsData['page_id']) && $cmsData['page_id'] != "")
            {

                return Response::json(['status' => 'update', 'msg' => 'Page has been updated successfully']);
            }
            else
            {
                return Response::json(['status' => 'save', 'msg' => 'Page has been saved successfully']);
            }
        }else{
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }
    }

    /**
     * Get particular page details
     *
     * @return json
     * @author rkaur3
     * @version 1.0
     * Dated 22June,2016
     */
    public function getPageDetails( $pageId )
    {
        try {
            if (isset($pageId) && $pageId != "") {

                $cmsObj = $this->cmsRepo->getRowObject($pageId);

                $cmsArr = array();
                $cmsArr['title'] = $cmsObj->getTitle();
                $cmsArr['description'] = $cmsObj->getDescription();
                $cmsArr['status'] = $cmsObj->getStatus();

                return json_encode($cmsArr);
            }
        }
        catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }


    }

    /**
     * Soft deletes cms page
     *
     * @param array Request $request
     * @return json
     * @author rkaur3
     * @version 1.0
     * Dated 22June,2016
     */
    public function deletePage(Request $request)
    {
        try {
            $data = $request->all();
            $pageId = $data['page_id'];
            if ($pageId != "") {
                $deletePage = $this->cmsRepo->deletePage($pageId);
                return Response::json(['status' => 'delete', 'msg' => 'Page has been deleted successfully']);
            }
        }
        catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }

    }

}
?>