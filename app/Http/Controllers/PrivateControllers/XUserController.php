<?php

namespace App\Http\Controllers\PrivateControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class XUserController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
}
