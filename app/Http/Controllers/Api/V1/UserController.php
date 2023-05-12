<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\{User, Card, Transaction, CardRequest};
use App\Classes\Coinbase;

class UserController extends Controller
{

    public function cardRequests(Request $request)
    {
        $user = Auth::user();
        $cardRequests = CardRequest::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json($cardRequests);
    }
    public function postRequestCard(Request $request) {

        // status
        $pending = 'pending';

        // card fees.
        $cardFees = [
            5 => 5,
            10 => 5,
            20 => 6,
            30 => 7,
            50 => 8,
            100 => 10,
            200 => 15,
            300 => 20,
            400 => 25,
            500 => 35,
        ];

        // get user.
        $user = Auth::user();
        $cardFee = 1;
        // get user balance from transactions table.
        $balance = Transaction::where('user_id', $user->id)->where('status', 'completed')->sum('amount');

        // get request amount.
        $amount = $request->amount ?? 0;

        // get package.
        $pkg = $request->pkg;

        // check if package is 1, then set amount to 2.
        if ($pkg == 1) {
            $cardFee = 2;
            $package = 'Without Loaded Virtual Card';
            $card_type = 'Non-Loaded';
        } else {
            $cardFee = $cardFees[$amount];
            $package = 'Loaded Virtual Card';
            $card_type = 'Loaded';
        }

        // quantity.
        $quantity = ($request->quantity > 0) ? $request->quantity : 1;

        // check if package is 1, then set amount to 2.
        if ($pkg == 1) {
            $amount = $request->quantity_pkg1 * 2;
            $quantity = $request->quantity_pkg1;
        } else {
            $amount = $amount * $quantity + ($cardFee * $quantity);
        }

        // check if user has enough balance to request a card.
        if ($balance < $amount) {
            return response()->json(['error' => 'You don\'t have enough funds to request a card.'])->setStatusCode(422);
        }

        if ($pkg == 1) {
            // If package is 1, then get cards and update them.
            $amount = $request->quantity_pkg1 * 2;
            $quantity = $request->quantity_pkg1;

            $cards = Card::where('card_request_id', null)->orderBy('id', 'asc')->get();
            // total cards.
            $totalCards = $cards->count();

            // check if cards are grater and equal to quantity, process it else return Only Available "Current card amount" Card as error.
            if ($totalCards >= $quantity) {
                $cards = $cards->take($quantity);
                $req = CardRequest::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'cost' => $amount,
                    'quantity' => $quantity,
                    "package" => $package,
                    'status' => 'completed',
                    'comment' => "",
                ]);

                // update cards.
                foreach ($cards as $card) {
                    $card->update([
                        'card_request_id' => $req->id,
                        'user_id' => $user->id,
                        'card_type' => $card_type,
                    ]);
                }

                // deduct amount from user balance.
                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => -$amount,
                    'type' => 'debit',
                    'status' => 'completed',
                ]);
                // return success.
                return response()->json(['success' => true]);
            } else {
                // return error.
                return response()->json(['error' => 'Only Available ' . $totalCards . ' Card.'])->setStatusCode(422);
            }
        }

        // deduct amount from user balance.
        Transaction::create([
            'user_id' => $user->id,
            'amount' => -$amount,
            'type' => 'debit',
            'status' => 'completed',
        ]);

        // Request Card.
        $req = CardRequest::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'cost' => $amount,
            'quantity' => $quantity,
            "package" => $package,
            'status' => 'pending',
            'comment' => "",
        ]);

        // return success.
        return response()->json(['success' => true]);
    }

    public function postAddFunds(Request $request)
    {
        $amount = $request->amount;
        $coinbase = new Coinbase();
        $charge = $coinbase->charge([
            'customer_id' => Auth::id(),
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'amount' => $amount,
        ]);
        $redirectedURL = $charge->hosted_url;
        Transaction::create([
            'user_id' => Auth::id(),
            'amount' => $amount,
            'type' => 'credit',
            'status' => 'pending',
            'charge_id' => $charge->id,
        ]);
        return response()->json(['redirectedURL' => $redirectedURL]);
    }

    // change password.
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }

        $user = User::find(Auth::id());
        if (!password_verify($request->old_password, $user->password)) {
            return response()->json(['old_password' => ['The old password is incorrect.']])->setStatusCode(422);
        }
        $user->update(['password' => bcrypt($request->password)]);

        return response()->json($user);
    }

    // Update user profile.
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'last_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }

        $user = User::find(Auth::id());
        $user->update($request->all());

        return response()->json($user);
    }

    // get total cards, funds.
    public function getTotalCardsFunds(Request $request)
    {
        $cards = Card::where('user_id', Auth::id())->count();
        $funds = Transaction::where('user_id', Auth::id())->where('status', 'completed')->sum('amount');
        return response()->json([
            'data' => [
                'funds' => [
                    'count'=> $funds,
                    'label'=> 'Funds',
                    'icon' => 'fas fa-money-bill-alt',
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

    // Get cards.
    public function getCards(Request $request)
    {
        $cards = Card::where('user_id', Auth::id());

        if ($request->has('search') && $request->search != 'null') {
            $cards = $cards->where('card_number', 'like', '%' . $request->search . '%');
        }

        $cards = $cards->orderBy('updated_at', 'desc')->paginate(10);
        return response()->json($cards);
    }

    // get transactions.
    public function getTransactions(Request $request)
    {
        $transactions = Transaction::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(10);
        return response()->json($transactions);
    }

    // postUpdate
    public function postUpdate(Request $request)
    {
        // Validate the request.
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'last_name' => 'nullable|regex:/^[a-z ,.\'-]+$/i|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        // Return validation errors if validation fails.
        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(422);
        }

        // Get the user and update it.
        $user = User::find(Auth::id());
        $user->update($request->all());

        // Return the updated user object.
        return response()->json($user);
    }
}
