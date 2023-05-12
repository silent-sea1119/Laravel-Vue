<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{User, UserMeta};

class ProfileController extends Controller
{
    public function getProfile(Request $request, $id)
    {
        $user = User::where('id', $id)->with('user_meta')->first();
        return response()->json($user);
    }
}