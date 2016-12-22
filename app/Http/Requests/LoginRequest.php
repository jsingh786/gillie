<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Auth;


class LoginRequest extends Request {
        
	public function authorize()
	{
	  return Auth::guest() == true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

		return [
			'email' => 'required', 'password' => 'required',
		];
	}

}
