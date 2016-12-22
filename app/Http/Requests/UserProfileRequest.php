<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/8/2016
 * Time: 4:36 PM
 */
namespace App\Http\Requests;

class UserprofileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //$this->sanitize();
        $input = $this->all();

        $rules = [
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u|max:20',
            'lastname' => 'required|alpha|max:20'
        ];

        if (isset($input['school'])) {
            $rules['school'] = 'max:255';
        }
        if (isset($input['work'])) {
            $rules['work'] = 'max:255';
        }

        if (isset($input['college'])) {
            $rules['college'] = 'max:255';
        }
        if (isset($input['phone'])) {
            $rules['phone'] = 'digits:10';
        }
        if (isset($input['occupation'])) {
            $rules['occupation'] = 'max:255';
        }

        return $rules;
    }

    public function messages()
    {//Todo: Please shift messages to gillie\config\constants.php
        $messages = [

            'firstname.required' => 'First name is required',
            'firstname.max' => 'Max 50 characters are allowed',
            'firstname.regex' => 'Only alphabets and spaces are allowed',
            'lastname.required' => 'Last name is required',
            'lastname.max' => 'Max 50 characters are allowed',
            'lastname.alpha' => 'Alphabets only,please',
            'work.max' => 'Max 255 characters are allowed',
            'school.max' => 'Max 255 characters are allowed',
            'college.max' => 'Max 255 characters are allowed',
            'occupation.max' => 'Max 255 characters are allowed',
            'phone.max' => 'Max 10 digits are allowed',
            'phone.numeric' => 'Phone number should be numeric',



        ];
        return $messages;
    }
}

