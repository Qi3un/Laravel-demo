<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

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
        $this->middleware('guest');
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
        echo "entered create";
        $data = DB::table('users')->get();
        error_log(print_r($data, TRUE));

        $validator = $this->validator($req->all());
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
}
