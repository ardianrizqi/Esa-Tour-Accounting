<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Laba Rugi';
    }

    public function index()
    {
        $title      = $this->title;
        $invoice   = Invoice::select(DB::raw('SUM(invoice_d.selling_price * invoice_d.qty) as selling_price'), 'products.product_category', DB::raw('SUM(invoice_d.purchase_price * invoice_d.qty) as purchase_price'))
                ->join('invoice_d', 'invoice.id', 'invoice_d.invoice_id')
                ->join('products', 'invoice_d.category_id', 'products.id')
                ->where('status', 'Aktif')
                ->groupBy('products.product_category')
                ->get();

        $retur_sale = Invoice::select(DB::raw('SUM(bank_history.nominal) as price'))
                    ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
                    // ->join('products', 'bank_history.product_id', 'products.id')
                    ->where('invoice.status', 'Aktif')
                    ->where('bank_history.refund_category', 'Refund Customer')
                    // ->groupBy('products.product_category')
                    ->first();

        $retur_purchase = Invoice::select(DB::raw('SUM(bank_history.nominal) as price'))
            ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
            // ->join('products', 'bank_history.product_id', 'products.id')
            ->where('invoice.status', 'Aktif')
            ->where('bank_history.refund_category', 'Refund Supplier')
            // ->groupBy('products.product_category')
            ->first();

        // dd($retur_sale->price);

        $ppn = Invoice::select(DB::raw('SUM(bank_history.nominal) as ppn'))
            ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
            ->where('bank_history.type', 'tax')
            ->first();
        // dd($ppn);

        $expense = Expense::all();

        return view('backend.profit_loss.index', compact('title', 'invoice', 'ppn', 'retur_sale', 'retur_purchase', 'expense'));
    }
}
