<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Sources\Guardian;
use App\Sources\NewsAPI;
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
        $news_api = new NewsAPI;
        $news_api->savePosts();
        return 'Done';
    }
}
