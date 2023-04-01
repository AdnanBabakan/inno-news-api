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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'status' => 'SUCCESSFUL',
            'message' => 'LOGGED_OUT_SUCCESSFULLY'
        ];
    }
}
