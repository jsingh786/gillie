<?php

namespace App\Mailers\Frontend;

use Illuminate\Support\Facades\Mail;
use App\Repository\usersRepo as usersRepo;
use Illuminate\Support\Facades\Config;

class UserMailer{

     public function __construct()
     {
     }

    public static function sendActivationEmail($user)
    {

        $user->encoded_user_id = base64_encode($user->getId());
        $userData = [
            'email' => $user->getEmail(),
            'name' => $user->getFirstname() . ' ' . $user->getLastname(),
            'user_id' =>  base64_encode($user->getId()),
            'public_path'=> Config::get('constants.PUBLIC_PATH'),
        ];

        Mail::send('frontend.emails.verification-email', compact('userData'), function($message) use($userData)
        {

            $message
                ->to($userData['email'], $userData['name'])
                ->from(Config::get('constants.ADMIN_EMAIL'), Config::get('constants.APP_NAME'))
                ->subject('Verify your email')
            ;
        });
    }
}
