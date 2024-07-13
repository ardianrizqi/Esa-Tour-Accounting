<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Customer;
use App\Models\District;
use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\PhysicalInvoice;
use App\Models\Bank;
use App\Models\BankHistory;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Invoice';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.invoice.index', compact('title'));
    }

    public function form($id = null)
    {
        $data               = null;
        $data_d             = null;
        $title              = $this->title;
        $provinces          = Province::all();

        if ($id) {
            $action = 'Ubah';
        }else{
            $action = 'Tambah';
        }

        $customers          = Customer::all();
        $products           = Product::all();
        $physical_invoie    = PhysicalInvoice::all();
        $bank               = Bank::all();

        if ($id) {
            $data   = Invoice::find($id);
            $data_d = InvoiceDetail::where('invoice_id', $data->id)->get();
        }
                
        return view('backend.invoice.form', compact('title', 'action', 'provinces', 'customers', 'products', 'physical_invoie', 'bank', 'data', 'data_d'));
    }

    public function get_city($province_id)
    {
        $data = City::where('province_id', $province_id)->get();
       
        return response()->json($data);
    }

    public function get_district($city_id)
    {
        $data = District::where('city_id', $city_id)->get();
       
        return response()->json($data);
    }
    
    public function customer_store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = Customer::create($request->all());

            DB::commit();

            $data = Customer::all();

            return response()->json([
                'status'    => 200,
                'message'   => 'Berhasil Menyimpan Data',
                'data'      => $data,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal Menyimpan Data, Coba Lagi Kembali',
            ]);
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        DB::beginTransaction();

        try {
            $param_h = [
                'customer_id'           => $request->customer_id,
                'date_publisher'        => $request->date_publisher,
                'physical_invoice_id'   => $request->physical_invoice_id,
                'invoice_number'        => $request->invoice_number,
                'price_total_selling'   => $request->price_total_selling,
                'price_total_purchase'  => $request->price_total_purchase,
                'total_profit'          => $request->total_profit,
                'created_user'          => Auth::user()->id,
                'updated_user'          => Auth::user()->id,
                'receivables'           => $request->price_total_selling
            ];

            if ($request->invoice_id) {
                $find = Invoice::find($request->invoice_id);
                $find->update($param_h);

                // dd('masok');
                $insert_h = $find;

                $find_d = InvoiceDetail::where('invoice_id', $find->id)->delete();
            }else{
                $insert_h = Invoice::create($param_h);
            }

            // dd($request->category_id);
            foreach ($request->category_id as $key => $value) {
                // dd($request);

                $param_d = [
                    'invoice_id'        => $insert_h->id,
                    'category_id'       => $value,
                    'product_name'      => $request->product_name[$key],
                    'qty'               => $request->qty[$key],
                    'selling_price'     => $request->selling_price[$key],
                    'from_bank'         => $request->from_bank[$key],
                    'purchase_price'    => $request->purchase_price[$key],
                    'note'              => $request->note[$key],
                    'debt_to_vendors'   => $request->debt_to_vendors[$key],
                    'total_price_sell'  => $request->total_price_sell[$key],
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id
                ];

                // dd($param_d);

                $insert_d = InvoiceDetail::create($param_d);
            }

            // dd('masok');
            DB::commit();

            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.invoice.index');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
    }

    public function data()
    {
        $data = Invoice::with('physical_invoice')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function show($id)
    {
        $title              = $this->title;
        $provinces          = Province::all();
        $action             = 'Detail';
        $customers          = Customer::all();
        $products           = Product::all();
        $physical_invoie    = PhysicalInvoice::all();
        $bank               = Bank::all();

        $data   = Invoice::find($id);
        $data_d = InvoiceDetail::where('invoice_id', $data->id)->get();
        $customer_payment = BankHistory::where('invoice_id', $data->id)
                            ->where('type', 'customer_payment')
                            ->get();
    
        return view('backend.invoice.show', compact('title', 'action', 'provinces', 'customers', 'products', 'physical_invoie', 'bank', 'data', 'data_d', 'customer_payment'));
    }

    public function update_details($id, Request $request)
    {
        // dd($request);
        DB::beginTransaction();

        try {
            $invoice = Invoice::find($id);
            $check = BankHistory::where('invoice_id', $id)
                    ->where('type', 'customer_payment')
                    ->get();

            foreach ($check as $key => $value) {
                $bank = Bank::find($value->bank_id);
                calculate_income($bank, $value->nominal, true);
                calculate_receivables($invoice, $value->nominal, true);
                
                $value->delete();
            }

            if ($request->nominal) {
                foreach ($request->nominal as $key => $value) {
                    $bank = Bank::find($request->bank_id[$key]);
                    calculate_income($bank, $value);

                    $note = '-';
    
                    if ($request->note[$key] !== null) {
                        $note = $request->note[$key];
                    }
    
                    $transaction_name = 'Pembayaran Customer dari '.$invoice->invoice_number. ' Ket: '.$note;
    
                    $create = BankHistory::create([
                        'bank_id'           => $request->bank_id[$key],
                        'transaction_name'  => $transaction_name,
                        'invoice_id'        => $id,
                        'date'              => $request->date[$key],
                        'type'              => 'customer_payment',
                        'nominal'           => $value,
                        'note'              => $note,
                        'created_user'      => Auth::user()->id,
                        'updated_user'      => Auth::user()->id
                    ]);

                    calculate_receivables($invoice, $value);
                }
            }

        
           
            DB::commit();

            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
     
    }
}
