<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'bail|required|string',

            'password' => 'bail|required|string',
        ]);
    }

    /**
     * authenticates a user who is trying to log in.
     *
     * @param  Illuminate\Http\Request req
     * @return json
     */
    protected function login(Request $req)
    {
        // echo "entered login";
        $validator = $this->validator($req->all());

        if($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        $user = DB::table('users')->where("username", $req->username);
        if($user->count() == 0) {
            return response()->json([ "error" => "user " . $req->username . " not exist" ], 400);
        }
        else if($user->value('password') != $req->password) {
            return response()->json([ "error" => "password incorrect" ], 400);
        }
        else {
            $token = $user->value('api_token');
            error_log(print_r($token, true));

            if(empty($token)) {
                $token = RegisterController::genToken();
                DB::table('users')->where("username", $req->username)
                    ->update([ "api_token" => $token ]);
            }
            return response()->json([ "token" => $token ], 201);
        }
    }
}
