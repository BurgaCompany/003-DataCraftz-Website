<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Deposits;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Root')) {
            // If the user has the 'Root' role, retrieve all deposits with type 'req'
            $deposits = Deposits::where('type', 'req')
                ->orWhere('user_id', $user->id)->get();
        } else {
            // For other users, retrieve deposits associated with their user_id
            $deposits = Deposits::where('user_id', $user->id)->get();
        }

        //dd($deposits);

        return view('deposits.index', compact('deposits'));
    }

    public function edit($id)
    {
        $deposit = Deposits::findOrFail($id);

        $user_id = $deposit->user_id;
        $bank = Bank::where('user_id', $user_id)->firstOrFail();

        //$deposit = $deposit->bank_id;
        // dd($deposit);

        return view('deposits.edit', compact('deposit', 'bank'));
    }


    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([]);

        // Find the deposit by ID and update its status
        $deposit = Deposits::findOrFail($id);
        $deposit->status = 'Done';
        $deposit->save();

        $bank_user = Bank::where('user_id', $deposit->user_id)->first();
        $bank_id = $bank_user->id;

        // Handle file upload if there's an image in the request
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('deposits') : null;

        // Create a new deposit object
        $newDeposit = new Deposits();
        $newDeposit->user_id = Auth::id();
        $newDeposit->bank_id = $bank_id;
        $newDeposit->amount = $request->amount;
        $newDeposit->type = 'send';
        $newDeposit->images = $imagePath;
        $newDeposit->save();

        // Get the authenticated user
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Subtract the deposit amount from the authenticated user's balance
        $user->balance -= $request->amount;
        $user->save();

        // Retrieve the deposit user and add the deposit amount to their balance
        $depositUser = User::find($deposit->user_id);
        $depositUser->balance += $request->amount;
        $depositUser->save();

        return redirect()->route('deposits.index')->with('success', 'Status deposit berhasil diperbarui');
    }
    public function detail($id)
    {

        $deposit = Deposits::findOrFail($id);


        $user_id = $deposit->user_id;

        $bank = Bank::where('user_id', $user_id)->first();
        //dd($deposit);

        //$deposit = $deposit->bank_id;


        return view('deposits.detail', compact('deposit', 'bank'));
    }
}
