<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Validator;
use App\Repository\userNotesRepo as userNotesRepo;
use App\Repository\usersRepo as usersRepo;


class NotesController extends Controller
{
    private $userNotesRepo;

    public function __construct(userNotesRepo $userNotesRepo,
                                usersRepo $usersRepo)
    {

        parent::__construct();
        $this->middleware('auth');
        $this->userNotesRepo = $userNotesRepo;
        $this->usersRepo = $usersRepo;
    }

    public function notes()
    {
        $user_obj = $this->usersRepo->getRowObject(['id',Auth::Id()]);
        return view('frontend.user-notes')->with('enable_profile_menu', true)->with('profileHolderObj',$user_obj);

    }

    protected function validator(array $data , array $rules, array $messages)
    {
        return Validator::make($data,$rules,$messages);
    }

    /**
     * Add note
     *
     * @param array ['note']
     * @return json
     * @throws \Illuminate\Foundation\Validation\ValidationException
     * @author rkaur3
     * @version 1.0
     *
     * Dated 20-sep-2016 //Todo: No need to mention date. just update version everytime you change the function.
     */
    public function addNote(Request $request)
    {

        if(Auth::check()){

            $rules = [
                'note' => 'required|max:1000'
            ];
            $messages = [
                'note.required' => 'Enter note',
                'note.max' => 'Maximum 1000 characters allowed'
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
            $params['note'] = $request->note;
            $noteAdded = $this->userNotesRepo->add($params);
            if($noteAdded){
                $result = array('status'=>200,'message'=>'success','note'=>$noteAdded);
            }
        }
        else
        {
            $result = array('status'=>301,'message'=>"Please login & try again");
        }
        return json_encode($result);
    }

    /**
     * Get new note popup view
     *
     * @return view
     * @author rkaur3
     * @version 1.0
     * Dated 16-sep-2016
     */
    public function getNewNote()
    {
        return view('frontend.new-note');
    }


    /**
     * Get notes according to limit and offset passed
     *
     * @param ['offset','limit']
     * @return json
     * @author rkaur3
     * @version 1.1
     * Dated 17-oct-2016
     */

    public function getNotes(Request $request)
    {
        if(Auth::check()) {

            $start          = (null !== Input::get('offset')) ? Input::get('offset') : 0;
            // length/limit specified for listing records
            $length         = (null !== Input::get('limit')) ? Input::get('limit') : 3;

            $notes = $this->userNotesRepo->get(Auth::user()->getId(), $length, $start);

           /* if($request->right_profile_menu == 'yes') {
            foreach ($notes['user_notes'] as $key => $nt) {
                $notes['user_notes'][$key]['notes'] = self::substr_text_only($nt['notes'], 50);
                }
            }*/

            $notesCount = $this->userNotesRepo->getCount( Auth::user()->getId() );

            $result = array('status'=>200,'message'=>'success','notes'=>$notes['user_notes'],'total_count'=>$notesCount,'is_more_records'=>$notes['is_more_records']);
        }else {
            $result = array('status'=>301,'message'=>"Please login & try again");
        }
        return json_encode($result);
    }
    public function substr_text_only($string, $limit, $end='...')
    {
        $with_html_count = strlen($string);

        $without_html_count = strlen(strip_tags($string));
        $html_tags_length = $with_html_count-$without_html_count;
       /* echo str_limit($string, $limit+$html_tags_length, $end);
        die();*/
        return str_limit($string, $limit+$html_tags_length, $end);
    }
}