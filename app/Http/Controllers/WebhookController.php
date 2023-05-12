<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Card, Transaction};

class WebhookController extends Controller
{
    // Coinbase charge:completed.
    public function chargeCompleted(Request $request)
    {
        $charge_id = $request->data['code'];
        $charge = Transaction::where('charge_id', $charge_id)->first();
        if ($charge) {
            $charge->update([
                'status' => 'completed',
            ]);
        }
        return response()->json(['success' => true]);
    }

    // Coinbase charge:failed.
    public function chargeFailed(Request $request)
    {
        $charge_id = $request->data['code'];
        $charge = Transaction::where('charge_id', $charge_id)->first();
        if ($charge) {
            $charge->update([
                'status' => 'failed',
            ]);
        }
        return response()->json(['success' => true]);
    }

    // Coinbase charge:delayed.
    public function chargeDelayed(Request $request)
    {
        $charge_id = $request->data['code'];
        $charge = Transaction::where('charge_id', $charge_id)->first();
        if ($charge) {
            $charge->update([
                'status' => 'delayed',
            ]);
        }
        return response()->json(['success' => true]);
    }
}
