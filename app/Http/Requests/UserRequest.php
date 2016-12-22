<?php

namespace App\Http\Requests;


class UserRequest extends Request
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
            //'firstname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
            'firstname' => 'required|alpha|max:20',
            'lastname' => 'required|alpha|max:20',
            'email' => 'required|email|unique:App\Entity\users',
            'password' => 'required|min:6|max:20|regex:/^\S*$/',
            'cpassword' => 'required|max:255|same:password',
            //'gender' => 'required|in:0,1',
            //'zipcode' => 'required|max:50|alpha_num',
            'address' => 'required',
            'is_active' => 'required'

        ];

        if(isset($input['gender']))
        {
            $rules['gender'] = 'required|in:0,1';
        }
        if(isset($input['address']))
        {
            $rules['address'] = 'required';
        }
        /*if(isset($input['cpassword']))
        {
            $rules['cpassword'] = 'required|max:255|same:password';
        }*/
        //different on edit screen
        if(isset($input["user_id"]) && $input["user_id"] != 0){

            $rules["email"] = '';
            $rules["password"] = '';
        }

        return $rules;
    }
    public function messages()
    {//Todo: Please shift messages to gillie\config\constants.php
        $messages = [

            'firstname.required' => 'First name is required',
            'firstname.max' => 'Maximum 50 characters are allowed',
            'firstname.alpha' => 'Alphabets only,please',
            'lastname.required' => 'Last Name is required',
            'lastname.max' => 'Max 50 characters are allowed',
            'lastname.alpha' => 'Alphabets only,please',
            'gender.required' => 'Gender is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password should be of minimum 6 characters',
            'password.max' => 'Maximum 20 characters are allowed',
            'password.regex'=>'Spaces are not allowed',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter valid email address',
            'email.unique' => 'Email address already exist',
            'zipcode.required' => 'Zipcode is required',
            'zipcode.max' => 'Max 50 characters are allowed',
            'zipcode.alpha_num' => 'Only alphanumeric characters are allowed',
            'address.required' => 'Location is required',
           // 'address.max' => 'Max 255 characters are allowed',
            'is_active.required' => 'Status is required',
            'cpassword.required' => 'Please confirm your password',
            'cpassword.same' => 'Password and confirm password does not match',

        ];
        return $messages;
    }


}
