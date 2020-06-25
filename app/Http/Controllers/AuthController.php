<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Auth Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the user authentications and exchange data through API.
    |
    */

    /**
     * Implement register process for the user through api
     * in addition to validations.
     * @param  \Illuminate\Http\Request  $request
     * @return Json Response
     */

    public function register(Request $request) {

        // Validation section for registration
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);


        // Create user instace section
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        // Json Response with status
        if ($user->save()) {
            return response()->json([
                'message' => 'User created successfully!',
                'status_code' => 201
            ], 201);
        } else {
            return response()->json([
                'message' => 'Some errorr occurred, Please try again',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * Implement login process for the user through api.
     * in addition to validations
     * @param  \Illuminate\Http\Request  $request
     * @return Json Response
     */
    public function login(Request $request) {

        // Validation section for login
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        // Credentials for check user data when attempt login
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Invalid username/password',
                'status_code' => 401
            ], 401);
        }

        $user = $request->user();

        $tokenData = $user->createToken('Personal Access Token', ['user']);

        $token = $tokenData->token;

        // Remeber me Section
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        if ($token->save()) {
            return response()->json([
                'user' => $user,
                'access_token' => $tokenData->accessToken,
                'token_type' => 'Bearer',
                'token_scope' => $tokenData->token->scopes[0],
                'expires_at' => Carbon::parse($tokenData->token->expires_at)->toDateTimeString(),
                'status_code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Some error occurred, Please try again',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * Implement logout process for the user through api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json Response
     */

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logout successfully!',
            'status_code' => 200
        ], 200);
    }

    /**
     * Fetch info for the authenticated user through api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json Response
     */
    public function profile(Request $request) {

        // check if there is user logged in already then fetch his info if found
        if ($request->user()) {
            return response()->json($request->user(), 200);
        }

        // in fail status
        return response()->json([
            'message' => 'Not loggedin',
            'status_code' => 500
        ], 500);
    }
}
