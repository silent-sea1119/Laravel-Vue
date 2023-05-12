<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\{User, Card, Transaction};
use App\Classes\Coinbase;

class AdminController extends Controller
{
    public function transactions(Request $request)
    {
        $transactions = new Transaction();

        // check if request has filter and search and is not empty.
        if ($request->has('filter') && $request->has('search') && !empty($request->search)) {
            $transactions = $transactions->where($request->filter, 'like', '%' . $request->search . '%');
        }

        // check if request has user_id and is not empty.
        if ($request->has('user_id') && !empty($request->user_id)) {
            $transactions = $transactions->where('user_id', $request->user_id);
        }

        $transactions = $transactions->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($transactions);
    }

    public function dashboard(Request $request)
    {
        $cards = Card::count();
        $users = User::count();
        return response()->json([
            'data' => [
                'funds' => [
                    'count'=> $users,
                    'label'=> 'Users',
                    'icon' => 'fas fa-users',
                    'color' => 'text-blue-500 bg-blue-100'
                ],
                'cards' => [
                    'count'=> $cards,
                    'label'=> 'Cards',
                    'icon' => 'fas fa-credit-card',
                    'color' => 'text-green-500 bg-green-100'
                ],
            ],
        ]);
    }
}
