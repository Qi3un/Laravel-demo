<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function regValidator(array $data)
    {
        return Validator::make($data, [
            'username' => 'bail|required|string|unique:users',

            'password_confirmation' => 'bail|required|string',

            'password' => 'bail|required|string|confirmed',
        ]);
    }

    /**
     * Generate api_token for new user
     *
     * @return string
     */
    static public function genToken()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 32; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Illuminate\Http\Request  $req
     * @return json
     */
    protected function create(Request $req)
    {
        $data = DB::table('users')->get();
        error_log(print_r($data, TRUE));

        $validator = $this->regValidator($req->all());
        if($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        $user = new User;
        $user->username = $req->username;
        $user->password = $req->password;
        $user->api_token = $this->genToken();
        $user->save();

        return response(["token" => $user->api_token], 201);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function loginValidator(array $data)
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
        $validator = $this->loginValidator($req->all());

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
                $token = $this->genToken();
                DB::table('users')->where("username", $req->username)
                    ->update([ "api_token" => $token ]);
            }
            return response()->json([ "token" => $token ], 201);
        }
    }

    /**
     * authenticates a user who is trying to log in.
     *
     * @param  Illuminate\Http\Request req
     * @return json
     */
    protected function logout(Request $req)
    {
        $user = DB::table('users')->where('id', $req->user_id)->update(['api_token' => ""]);
        return response("", 200);
    }
}
