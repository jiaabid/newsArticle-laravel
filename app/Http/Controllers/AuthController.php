<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    /**
     * Login the user and return the token.
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token =  $user->createToken('MyApp')->accessToken;
            return self::success("User loggedin successfully", ["token" => $token, "user" => $user]);
        } else {
            return self::failure("Unauthorized!", [], 203);
        }
    }

    /**
     * Register the user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return self::failure('Validation Error.', ["errors" => $validator->errors()], 203);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token =  $user->createToken('MyApp')->accessToken;
        return self::success("User loggedin successfully", ["token" => $token->token, "user" => $user]);
    }
}
