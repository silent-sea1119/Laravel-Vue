<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\{AccountCreationMail, MarkMail};
use Illuminate\Validation\Rule;

use App\Models\{User, Card, CardRequest};

class CardController extends Controller
{

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
            'card_request_id' => 'required|exists:card_requests,id',
        ]);

        $cardRequest = CardRequest::find($request->card_request_id);
        $package = $cardRequest->package;
        if ($package == 'Without Loaded Virtual Card') {
            $card_type = 'Non-Loaded';
        } else {
            $card_type = 'Loaded';
        }

        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }
        $file = $request->file('file');
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $file_name);
        $file_path = public_path('uploads') . '/' . $file_name;
        $csv = array_map('str_getcsv', file($file_path));
        $csv = array_slice($csv, 1);
        $csv = array_filter($csv);
        $csv = array_values($csv);
        $csv = array_map(function ($item) use ($request, $cardRequest) {
            return [
                'card_request_id' => $request->card_request_id,
                'user_id' => $cardRequest->user_id,
                'first_name' => $item[0],
                'last_name' => $item[1],
                'card_number' => $item[2],
                'cvc' => $item[3],
                'expire_month' => $item[4],
                'expire_year' => $item[5],
                'address' => $item[6] ?? null,
                'city' => $item[7] ?? null,
                'state' => $item[8] ?? null,
                'zip' => $item[9] ?? null,
                'transaction_id' => 1,
                'card_type' => $card_type,
            ];
        }, $csv);
        $cards = Card::insert($csv);
        return response()->json($cards);
    }
    public function index(Request $request)
    {
        $cards = new Card();

        // check if request has filter and search and is not empty.
        if ($request->has('filter') && $request->has('search') && !empty($request->search)) {
            $cards = $cards->where($request->filter, 'like', '%' . $request->search . '%');
        }

        $cards = $cards->orderBy('created_at', 'desc')->with('user')->paginate(10);
        return response()->json($cards);
    }

    public function importWithoutCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }
        $file = $request->file('file');
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $file_name);
        $file_path = public_path('uploads') . '/' . $file_name;
        $csv = array_map('str_getcsv', file($file_path));
        $csv = array_slice($csv, 1);
        $csv = array_filter($csv);
        $csv = array_values($csv);
        $csv = array_map(function ($item) use ($request) {
            return [
                'card_request_id' => null,
                'user_id' => Auth::user()->id,
                'first_name' => $item[0],
                'last_name' => $item[1],
                'card_number' => $item[2],
                'cvc' => $item[3],
                'expire_month' => $item[4],
                'expire_year' => $item[5],
                'address' => $item[6] ?? null,
                'city' => $item[7] ?? null,
                'state' => $item[8] ?? null,
                'zip' => $item[9] ?? null,
                'transaction_id' => 1,
            ];
        }, $csv);
        $cards = Card::insert($csv);
        return response()->json($cards);
    }
    public function indexWithOutRequest(Request $request)
    {
        $cards = new Card();

        // check if request has filter and search and is not empty.
        if ($request->has('filter') && $request->has('search') && !empty($request->search)) {
            $cards = $cards->where($request->filter, 'like', '%' . $request->search . '%');
        }

        $cards = $cards->where('card_request_id', null)->orderBy('id', 'asc')->with('user')->paginate(10);
        return response()->json($cards);
    }

    public function storeWithOutRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'last_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'card_number' => 'required|string|max:255|unique:cards',
            'cvc' => 'required|string|max:255',
            'expire_month' => 'required|string|max:255',
            'expire_year' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }

        $request->request->add(['user_id' => Auth::user()->id]);

        $request->request->add(['transaction_id' => 1]);

        $card = Card::create($request->all());

        return response()->json($card);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'last_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'card_number' => 'required|string|max:255|unique:cards',
            'cvc' => 'required|string|max:255',
            'expire_month' => 'required|string|max:255',
            'expire_year' => 'required|string|max:255',
            'card_request_id' => 'required|exists:card_requests,id',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }

        $cardRequest = CardRequest::find($request->card_request_id);
        $number_of_cards = $cardRequest->number_of_cards;
        $quantity = $cardRequest->quantity;
        $package = $cardRequest->package;
        if ($package == 'Without Loaded Virtual Card') {
            $card_type = 'Non-Loaded';
        } else {
            $card_type = 'Loaded';
        }

        // If its reached set status to completed.
        if ($number_of_cards >= ($quantity - 1)) {
            //$request->request->add(['status' => 'completed']);
            $cardRequest->status = 'completed';
            $cardRequest->save();
        }

        $request->request->add(['card_type' => $card_type]);
        $request->request->add(['user_id' => $cardRequest->user_id]);

        $request->request->add(['transaction_id' => 1]);

        $card = Card::create($request->all());

        return response()->json($card);
    }

    public function show($id)
    {
        $card = Card::where('id', $id)->with('user')->first();
        return response()->json($card);
    }
    public function put(Request $request, $id)
    {
        $card = Card::find($id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'last_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'card_number' => 'required|string|max:255|unique:cards,card_number,' . $id,
            'cvc' => 'required|string|max:255',
            'expire_month' => 'required|string|max:255',
            'expire_year' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }
        try {
            $card->first_name = $request->first_name;
            $card->last_name = $request->last_name;
            $card->card_number = $request->card_number;
            $card->cvc = $request->cvc;
            $card->expire_month = $request->expire_month;
            $card->expire_year = $request->expire_year;
            $card->address = ($request->address != 'null' && $request->address != null) ? $request->address : null;
            $card->city = ($request->city != 'null' && $request->city != null) ? $request->city : null;
            $card->state = ($request->state != 'null' && $request->state != null) ? $request->state : null;
            $card->zip = ($request->zip != 'null' && $request->zip != null) ? $request->zip : null;
            $card->save();

            return response()->json($card);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()])->setStatusCode(422);
        }
    }
    public function destroy($id)
    {
        $card = Card::find($id);
        $card->delete();
        return response()->json(['message' => 'Card deleted successfully']);
    }
}
