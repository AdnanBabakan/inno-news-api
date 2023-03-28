<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class GeneralController extends Controller
{
    public function getSettings()
    {
        return GeneralSetting::getPublicSettings();
    }

    public function test()
    {
        $user = User::where('email', 'adnanbabakan.personal@gmail.com')->first();
        return Crypt::encryptString($user->id . '|' . $user->email_verification_code);
    }
}
