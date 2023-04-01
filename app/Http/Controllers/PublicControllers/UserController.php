<?php

namespace App\Http\Controllers\PublicControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $new_user = new User;
        $new_user->first_name = $request->first_name;
        $new_user->last_name = $request->last_name;
        $new_user->email = $request->email;
        $new_user->email_verified_at = Carbon::now();
        $new_user->password = Hash::make($request->password);
        $new_user->save();

        return [
            'status' => 'SUCCESSFUL',
            'message' => 'USER_CREATED_SUCCESSFULLY'
        ];
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'status' => 'FAILED',
                'message' => 'INVALID_CREDENTIALS'
            ])->setStatusCode(403);
        }

        if ($user->email_verified_at == null) {
            return response([
                'status' => 'FAILED',
                'message' => 'EMAIL_NOT_VERIFIED'
            ])->setStatusCode(403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'status' => 'SUCCESSFUL',
            'message' => 'USER_AUTHENTICATED_SUCCESSFULLY',
            'token' => $token
        ];
    }

    public function requestNewVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'status' => 'FAILED',
                'message' => 'INVALID_CREDENTIALS'
            ])->setStatusCode(403);
        }

        if ($user->email_verified_at != null) {
            return response([
                'status' => 'FAILED',
                'message' => 'EMAIL_ALREADY_VERIFIED'
            ])->setStatusCode(403);
        }

        $user->email_verification_code = rand(100000, 999999);
        $user->save();

        return [
            'status' => 'SUCCESSFUL',
            'message' => 'NEW_VERIFICATION_EMAIL_SENT'
        ];
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'verification_signature' => 'required|string',
        ]);

        $verification_signature_decrypt = Crypt::decryptString($request->verification_signature);

        $verification_signature_parts = explode('|', $verification_signature_decrypt);

        $user = User::where('id', $verification_signature_parts[0])->first();

        if (!$user) {
            return response([
                'status' => 'FAILED',
                'message' => 'INVALID_VERIFICATION_SIGNATURE'
            ])->setStatusCode(403);
        }

        if ($user->email_verified_at != null) {
            return response([
                'status' => 'FAILED',
                'message' => 'EMAIL_ALREADY_VERIFIED'
            ])->setStatusCode(403);
        }

        if ($user->email_verification_code != $verification_signature_parts[1]) {
            return response([
                'status' => 'FAILED',
                'message' => 'INVALID_VERIFICATION_SIGNATURE'
            ])->setStatusCode(403);
        }

        $user->email_verified_at = now();
        $user->email_verification_code = null;
        $user->save();

        return [
            'status' => 'SUCCESSFUL',
            'message' => 'EMAIL_VERIFIED_SUCCESSFULLY'
        ];
    }
}
