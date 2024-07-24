<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Bank;
use App\Models\Deposit;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScaleController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Neraca';
    }

    public function index()
    {
        $title      = $this->title;
        $bank   = Bank::all();
        $piutang = Invoice::all()->sum('receivables');
        $deposit = Deposit::all();
        $hutang = Invoice::select(DB::raw('SUM(invoice_d.debt_to_vendors) as debt_to_vendors'), 'products.product_category')
                    ->join('invoice_d', 'invoice.id', 'invoice_d.invoice_id')
                    ->join('products', 'products.id', 'invoice_d.category_id')
                    ->where('invoice_d.debt_to_vendors', '!=', null)
                    ->groupBy('products.product_category')
                    ->get();

        $asset = Asset::all();
        $profit = Invoice::all()->sum('total_profit');

        return view('backend.scale.index', compact('title', 'bank', 'piutang', 'deposit', 'hutang', 'asset', 'profit'));
    }

}
