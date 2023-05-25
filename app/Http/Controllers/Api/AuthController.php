<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{





    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register', 'sendVerificationCode', 'verifyCode']]);
    }*/

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'person_type' => 'required|in:user,singer',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name/*input('name')*/,
            'email' => $request->email/*input('email')*/,
            'person_type' => $request->input('person_type'),
            'password' => Hash::make($request->password/*input('password')*/),
            'verification_token' => mt_rand(100000, 999999),
        ]);
        //$user->save();

        // Send verification email
       // $user->sendEmailVerificationNotification();
        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json(['message' => 'Successfully registered, please verify your email '/*.$request->input('person_type') .' '.  $user ->person_type*/     ]);
    }








    /**
     * Login user and create token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only(['email', 'password']);

      /*  if (!/*Auth::attempt($credentials)*//*Auth::guard('sanctum')->getProvider()->validateCredentials(Auth::guard('sanctum')->user(), $credentials)) {
        /*    return response()->json(['message' => 'Unauthorized'], 401);
        }*/
        if (!Auth::guard('web')->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $user = User::where('email', $request->email)->firstOrFail();

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }










    /**
     * Logout user and invalidate token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }








    // Send email verification code to user's email
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $verificationCode = rand(100000, 999999);

        $user->verification_token = $verificationCode;
        $user->save();

        //$user->sendEmailVerificationNotification();
        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json(['message' => 'Verification code sent to email']);
    }









    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $user = User::where('verification_token', $request->verification_code)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        $user->email_verified_at = Carbon::now();
        $user->verification_token = null;
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
    }
}
