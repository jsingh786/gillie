<?php
/**
 * Created by PhpStorm.
 * User: rkaur3
 * Date: 6/15/2016
 * Time: 11:07 AM
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Repository\profileRatingRepo;

class RatingController extends  Controller{


    /**
     * @var profileRatingRepo
     */
    private $profileRatingRepo;

    public function __construct(profileRatingRepo $profileRatingRepo)
    {
        //Contructor Function
        $this->profileRatingRepo = $profileRatingRepo;
        $this->middleware('admin');
    }

    protected function validator(array $data , array $rules, array $messages)
    {
        return Validator::make($data,$rules,$messages);
    }
    /**
     * Manages user listing for ajax/view.
     *
     * @param  array Request $request
     * @return json/view
     * @author rkaur3
     * @version 1.0
     * @date 23 June, 2016
     */
    public function index(Request $request)
    {
        return view('backend/manage-rating-algo');
    }



    /**
     * Updates points for rating algo
     *
     * @param array Request $request
     * @return json
     * @author rkaur3
     * @version 1.0
     * @date 23 June, 2016
     */
    public function updatePoints(Request $request)
    {
        try {
            $ratingData = $request->all();
            $updatePoints = $this->profileRatingRepo->updatePoints($ratingData);
            if ($updatePoints) {
                return Response::json(['status' => 'update', 'msg' => 'Points have been updated successfully']);
            }
        }
        catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }
    }

    /**
     * Get all rating points
     *
     * @return array
     * @author rkaur3
     * @version 1.0
     * Dated 4 July,2016
     */
    public function getAllRatings()
    {
        $profileRating = $this->profileRatingRepo->getRatings();
        $ratingArr = array();
        foreach($profileRating as $key => $pr)
        {
            $ratingArr[$key]['id'] = $pr->getId();
            $ratingArr[$key]['stars'] = $pr->getStar();
            $ratingArr[$key]['points'] = $pr->getPoints();
        }
        return $ratingArr;
    }
}


