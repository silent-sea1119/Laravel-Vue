<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\{AccountCreationMail, VerifiedMail};

use App\Models\User;

class AuthController extends Controller
{
    public function getNewToken(Request $request) {
        // laravel passport auth/refresh
        $user = Auth::user();
        $token = $user->createToken('MyApp')->accessToken;
        return response()->json(['token' => $token]);
    }
    public function verifyUser(Request $request, $token)
    {
        $user = User::where('verification_token', $token);
        if ($user->exists()) {
            $user->update([
                'verification_token' => null,
                'email_verified_at' => now(),
            ]);
            $user = $user->first();
            Mail::to($user->email)->send(new VerifiedMail($user));
        }
       return redirect('/');
    }
    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'last_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }
        $verification_token = str_random(32);
        $request->merge(['verification_token' => $verification_token]);
        $request->merge(['user_role' => 'user']);
        $password = $request->password;
        $request->merge(['password' => bcrypt($password)]);

        $password_hash = bcrypt($password);
        $user = User::create($request->all());

        // sent email to user with password and link to verify account.
        $link = url('/user/verify/' . $verification_token);
        Mail::to($user->email)->send(new AccountCreationMail($password, $link, $user));
        return response()->json(['sucess' => true]);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if($request->user_role == 'admin' && $user->user_role != 'admin'){
                // logout the user.
                Auth::logout();
                return response()->json([
                    'msg' => 'Can not login as admin',
                    'status' => 221
                ])->setStatusCode(401);
            }

            if($request->user_role == 'others' && $user->user_role == 'admin'){
                Auth::logout();
                return response()->json([
                    'msg' => 'Can not login as client',
                    'status' => 222
                ])->setStatusCode(401);
            }
            if ($user->email_verified_at == null) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Please verify your email first',
                    'status' => 223
                ])->setStatusCode(401);
            }

            $accessToken = $user->createToken('ApiAccess')->accessToken;
            // get array of permissions premission field.
            return response()->json([
                'accessToken' => $accessToken,
                'refreshToken' => $accessToken,
                'msg' => 'Login successful',
                'user' => $user,
            ]);
        }

        return response()->json([
            'msg' => 'Email or password is incorrect',
            'status' => 220
        ])->setStatusCode(401);
    }
    public function postLogout(Request $request)
    {
        $user = Auth::user();
        $user->token()->revoke();
        return response()->json([
            'msg' => 'Logout successful',
            'status' => 200
        ]);
    }
    public function getMe(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user,
        ]);
        return response()->json($userData);
    }

    public function postResetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $new_password = $this->randomPassword();

        $user->password = bcrypt($new_password);
        $user->save();

        Mail::to($user->email)->send(new PasswordResetMail($new_password, $user->first()));
    
        return response()->json(['msg' => 'Your new password has been sent to your email.']);
    }

    public function putMe(Request $request)
    {
        $user = Auth::user();

        $validation_rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'reg_num' => 'required|string|max:255|unique:users',
            'gender' => 'required|in:male,female,other',
            'profile_picture' => 'required|string|max:255',
            'session' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'previous_job' => 'required|string|max:255',
            'current_job' => 'required|string|max:255',
        ];

        $this->validate($request, $validation_rules);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->reg_num = $request->reg_num;
        $user->gender = $request->gender;
        // save profile pic into disk and save the path into database
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_pictures'), $filename);
            $user->profile_picture = $filename;
        } else {
            $user->profile_picture = null; // default avatar path. TODO: change this to default avatar
        }
        $user->reg_num = $request->reg_num;
        $user->session = $request->session;
        $user->year = $request->year;
        $user->degree = $request->degree;
        $user->previous_job = $request->previous_job;
        $user->current_job = $request->current_job;
        $user->save();

        $userData = User::find($user['id']);

        return response()->json(['msg' => 'success', 'user' => $userData]);
    }

    function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
}
