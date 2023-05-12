<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\{User, Card, CardRequest, Transaction};

class CardRequestController extends Controller
{

    public function deny($id, Request $request)
    {
        $card = CardRequest::find($id);
        $card->status = 'denied';
        $card->comment = $request->comment;
        $card->save();

        // Refund the amount.
        Transaction::create([
            'user_id' => $card->user_id,
            'amount' => $card->amount,
            'type' => 'refunded',
            'status' => 'completed',
        ]);
        return response()->json(['message' => 'Card denied successfully']);
    }
    public function index(Request $request)
    {
        $cards = new CardRequest();

        // check if request has filter and search and is not empty.
        if ($request->has('filter') && $request->has('search') && !empty($request->search)) {
            $cards = $cards->where($request->filter, 'like', '%' . $request->search . '%');
        }

        $cards = $cards->orderBy('created_at', 'desc')->with('user', 'cards')->paginate(10);
        return response()->json($cards);
    }

    public function show($id)
    {
        $card = CardRequest::where('id', $id)->with('user', 'cards')->first();
        return response()->json($card);
    }

    public function updateStatus($id, Request $request)
    {
        try {
            $card = CardRequest::find($id);
            $card->status = $request->status;
            $card->save();
            return response()->json(['message' => 'Card status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong']);
        }
    }
    public function destroy($id)
    {
        $card = CardRequest::find($id);
        $card->delete();
        return response()->json(['message' => 'Card deleted successfully']);
    }
}
