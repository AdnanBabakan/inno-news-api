<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Sources\Guardian;
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
        $guardian = new Guardian;
        $guardian->savePosts();
        return 'Done';
    }
}
