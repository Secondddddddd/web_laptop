<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryComponent extends Component
{
    public $orders;

    public function __construct()
    {
        $this->orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('components.transaction-history-component');
    }
}
