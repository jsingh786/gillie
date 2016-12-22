<?php

namespace App\Http\Requests;


class AdminRequest extends Request
{
    /**
     * Determine if the admin user is authorized to make this request.
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
        $input = $this->all();
        $rules = [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255'
        ];

        return $rules;
    }

    public function messages()
    {//Todo: Please shift messages to gillie\config\constants.php
        $messages = [
            'firstname.required' => 'First name is requied.',
            'firstname.max' =>'Maximum length of 255 characters are allowed.',
            'lastname.required' => 'Last name is required'
        ];

        return $messages;
    }

}