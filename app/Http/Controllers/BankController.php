<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::where('user_id', auth()->id())->paginate(15);
        return view('banks.index', compact('banks'));
    }

    public function search(Request $request)
    {
        $query = Bank::where('user_id', auth()->id());

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', '%' . $search . '%');
            });
        }

        $banks = $query->paginate(15);

        return view('banks.index', compact('banks'));
    }

    public function create()
    {
        return view('banks.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'account_name' => 'required|string|max:255',
            'type_bank' => 'required|string|max:255',
            'account_number' => 'required|string|max:20|unique:banks,account_number',
        ]);

        $bank = new Bank([
            'user_id' => auth()->id(),
            'account_name' => $request->get('account_name'),
            'type_bank' => $request->get('type_bank'),
            'account_number' => $request->get('account_number'),
        ]);

        $bank->save();
        return redirect()->route('banks.index')->with('success', 'Bank created successfully');
    }
    public function detail($id)
    {
        $bank = Bank::find($id);

        if (!$bank) {
            return redirect()->route('banks.index')->with('error', 'Bank not found');
        }

        return view('banks.detail', compact('bank'));
    }
    public function edit($id)
    {
        $bank = Bank::find($id);

        if (!$bank || $bank->user_id !== auth()->id()) {
            return redirect()->route('banks.index')->with('error', 'You do not have permission to edit this bank.');
        }

        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'account_name' => 'required',
            'type_bank' => 'required',
            'account_number' => 'required|unique:banks,account_number,' . $id,
        ]);

        $bank = Bank::find($id);

        if (!$bank) {
            return redirect()->route('banks.index')->with('error', 'Bank not found');
        }

        $bank->user_id = auth()->id();
        $bank->account_name = $request->get('account_name');
        $bank->type_bank = $request->get('type_bank');
        $bank->account_number = $request->get('account_number');

        $bank->save();

        return redirect()->route('banks.index')->with('success', 'Bank updated successfully');
    }

    public function destroyMulti(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:banks,id',
        ]);
        Bank::whereIn('id', $request->ids)->delete();
        return redirect()->route('admins.index')->with('message', 'Berhasil menghapus data');
    }
}
