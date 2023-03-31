<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Sources\SourceHandler;

class GeneralController extends Controller
{
    public function getSettings()
    {
        return GeneralSetting::getPublicSettings();
    }

    public function test()
    {
        return SourceHandler::runNewsSources();
    }
}
