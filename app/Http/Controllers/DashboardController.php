<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalIncome = Transaction::where('type', 'income')
                                    ->where('user_id', $user->id)
                                    ->sum('amount');

        $totalExpense = Transaction::where('type', 'expense')
                                    ->where('user_id', $user->id)
                                    ->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('dashboard', compact('totalIncome', 'totalExpense', 'balance'));
    }
}
