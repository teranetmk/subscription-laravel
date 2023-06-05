<?php

namespace App\Http\Controllers;

use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchase.index');
    }

    public function show(Purchase $purchase)
    {
        return view('purchase.show', compact('purchase'));
    }
}
