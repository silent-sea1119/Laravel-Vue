<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\{AccountCreationMail, MarkMail};

use App\Models\{User, Transaction};

class UserController extends Controller
{
    public function addFunds(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }
        $user = User::find($id);
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'credit',
            'status' => 'completed',
            'description' => 'Added funds to account',
        ]);
        //$user->update(['balance' => $user->balance + $request->amount]);
        return response()->json($user);
    }
    public function index(Request $request)
    {
        $users = new User();

        // check if request has filter and search and is not empty.
        if ($request->has('filter') && $request->has('search') && !empty($request->search)) {
            $users = $users->where($request->filter, 'like', '%' . $request->search . '%');
        }
        // check if request has type and is not empty.
        if ($request->has('type') && !empty($request->typex)) {
            $users = $users->where('user_role', $request->type);
        }
        $users = $users->orderBy('created_at', 'desc')->paginate(10);
        return response()->json($users);
    }
    public function markVerified(Request $request, $id)
    {
        $user = User::where('id', $id);
        if ($request->mark === 'suspend') {
            $user->update(['suspend_at' => now()]);
        } else {
            $user->update(['suspend_at' => null]);
        }
        return response()->json(['success' => $user]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'last_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'user_role' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }

        // $verification_token = str_random(32);
        // $request->merge(['verification_token' => $verification_token]);

        // validate the user as creating by admin.
        $request->merge([
            'email_verified_at' => now(),
        ]);

        // generate password
        $password = str_random(8);
        $password_hash = bcrypt($password);
        // Append to request
        $request->merge(['password' => $password_hash]);
        $user = User::create($request->all());

        //$user = $user->first();

        // sent email to user with password and link to verify account.
        // $link = url('/user/verify/' . $user->id . '/' . $verification_token);
        // Mail::to($user->email)->send(new AccountCreationMail($password, $link, $user));
        return response()->json($user);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json($user);
    }
    public function put(Request $request, $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'last_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'email' => 'required|string|email|max:255',
            'user_role' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }
        try {
            $user->first_name = ($request->first_name != 'null' && $request->first_name != null) ? $request->first_name : null;
            $user->last_name = ($request->last_name != 'null' && $request->last_name != null) ? $request->last_name : null;
            $user->email = $request->email;
            $user->user_role = $request->user_role ?? 'user';
            $user->save();

            return response()->json($user);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()])->setStatusCode(422);
        }
    }
    public function destroy($id)
    {
        // Check if id is not equal to login user id or not equal to 1.
        if (Auth::user()->id === $id || $id == 1) {
            return response()->json(['message' => 'You can not delete this admin.'])->setStatusCode(422);
        }
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
