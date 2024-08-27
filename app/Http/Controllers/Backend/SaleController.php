<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Penjualan';
    }

    public function index()
    {
        $title      = $this->title;
        $invoice = Invoice::where('status', 'Aktif')->orderBy('created_at', 'desc')->get();

        return view('backend.sale.index', compact('title', 'invoice'));
    }
}
