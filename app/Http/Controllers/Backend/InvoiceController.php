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
use App\Models\Tax;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public $title, $messege, $error;

    public function __construct()
    {
        $this->title = 'Invoice';
        $this->messege = '';
        $this->error = false;
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
        $tax = [];
        $cashback = [];
        $refund = [];

        if ($id) {
            $data   = Invoice::find($id);
            $data_d = InvoiceDetail::where('invoice_id', $data->id)->get();
        }
                
        return view('backend.invoice.form', compact('title', 'action', 'provinces', 'customers', 'products', 'physical_invoie', 'bank', 'data', 'data_d', 'tax', 'cashback', 'refund'));
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
            $requestData = array_merge($request->all(), [
                'created_user'  => Auth::user()->id,
                'updated_user'  => Auth::user()->id,
            ]);

            $data = Customer::create($requestData);

            DB::commit();

            $data = Customer::all();

            return response()->json([
                'status'    => 200,
                'message'   => 'Berhasil Menyimpan Data',
                'data'      => $data,
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
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
                // dd('masok');
                $insert_h = Invoice::create($param_h);
            }

            // dd('keluar');
            $id = $insert_h->id;

            $invoice = Invoice::find($id);
            $check = BankHistory::where('invoice_id', $id)
                    ->where('type', 'customer_payment')
                    ->get();

            // dd($check);
            foreach ($check as $key => $value) {
                $bank = Bank::find($value->bank_id);
                calculate_bank_income($bank, $value->nominal, true);
                calculate_receivables($invoice, $value->nominal, true);
                
                $value->delete();
            }

            // dd('masok');
            // dd($request);
            # pembayaran customer
            if ($request->nominal) {
                foreach ($request->nominal as $key => $value) {
                    if ($value !== null) {
                        $bank = Bank::find($request->bank_id[$key]);
                        calculate_bank_income($bank, $value);
    
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
            }

            # hutang vendor
            if ($request->inv_debt_id) {
                foreach ($request->inv_debt_id as $key => $value) {
                    $invoice_d = InvoiceDetail::find($value);
                    $status_payment = true;
    
                    if ($invoice_d->status_debt !== 'Sudah Lunas') {
                        if ($request->payment_date[$key] !== null) {
                            $bank = Bank::find($invoice_d->from_bank);
        
                            if ($bank->balance < $invoice_d->debt_to_vendors) {
                                // dd('masok');
                                DB::rollBack();
        
                                Alert::error('Error', 'Tidak Bisa Melakukan Pelunasan Hutang Vendor, Saldo Bank Tidak Mencukupi !!');
                                return redirect()->back();
                            }
        
        
                            calculate_bank_expense($bank, $invoice_d->debt_to_vendors, true);
        
                            $invoice_d->update([
                                'status_debt'       => 'Sudah Lunas',
                                'date_payment_debt' => $request->payment_date[$key]
                            ]);
        
                            $transaction_name = 'Pelunasan Hutang Ke Vendor dari '.$invoice->invoice_number;
        
                            $create = BankHistory::create([
                                'bank_id'           => $invoice_d->from_bank,
                                'transaction_name'  => $transaction_name,
                                'invoice_id'        => $invoice_d->invoice_id,
                                'date'              => $request->payment_date[$key],
                                'type'              => 'vendor_payment',
                                'nominal'           => $invoice_d->debt_to_vendors,
                                'note'              => '-',
                                'created_user'      => Auth::user()->id,
                                'updated_user'      => Auth::user()->id
                            ]);
                        }
                    }
                
    
                    // if ($invoice_d->status_debt == 'Belum Lunas') {
                    //     $status_payment = false;
                    // }
                }
            }
            // dd($request);
         
           
            // if ($status_payment) {
            //     $invoice->update([
            //         'status'    => 'Sudah Lunas'
            //     ]);
            // }

            # refund
            $this->store_refund($id, $request);

            // dd('mask');
            # cashback
            $this->store_cashback($id, $request);
            # tax
            $this->store_tax($id, $request);
            // dd('maosk');

            if ($this->error) {
                Alert::error('Error', $this->messege);

                DB::rollBack();
                return redirect()->back();
            }

            // dd($request->category_id);
            foreach ($request->category_id as $key => $value) {
                // dd($insert_h);

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

                if ($request->debt_to_vendors[$key] !== null) {
                    $param_d['status_debt'] = 'Belum Lunas';
                }
                // dd($param_d);

                // dd('masok');
                $insert_d = InvoiceDetail::create($param_d);

                $product = Product::find($value);
                calculate_sell_product($product, $request->selling_price[$key]);
                calculate_purchase_product($product, $request->selling_price[$key]);
                calculate_profit_product($product);
            }

            // dd('masok');
            DB::commit();

            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.invoice.index');
        } catch (\Throwable $th) {
            dd($th->getMessage());
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

        $refund = BankHistory::where('invoice_id', $data->id)
                        ->where('type', 'refund')
                        ->get();

        $cashback = BankHistory::where('invoice_id', $data->id)
            ->where('type', 'cashback')
            ->get();

        $tax = BankHistory::where('invoice_id', $data->id)
                ->where('type', 'tax')
                ->get();
        
        return view('backend.invoice.show', compact('title', 'action', 'provinces', 'customers', 
        'products', 'physical_invoie', 'bank', 'data', 
        'data_d', 'customer_payment', 'refund', 'cashback', 'tax'));
    }
    
    public function store_refund($id, $request)
    {
        $invoice = Invoice::find($id);

        $check = BankHistory::where('invoice_id', $id)
                ->where('type', 'refund')
                ->get();

        foreach ($check as $key => $value) {
            $bank = Bank::find($value->bank_id);
            calculate_bank_expense($bank, $value->nominal);
            // calculate_receivables($invoice, $value->nominal, true);
            
            $value->delete();
        }

        if ($request->nominal_refund) {
            // dd('masok');
            foreach ($request->nominal_refund as $key => $value) {
                if ($value !== null) {
                    $bank = Bank::find($request->bank_id_refund[$key]);
                    // dd($request);
    
                    if ($bank) {
                        if ($bank->balance < $value) {
                            // dd('masok');
                            // DB::rollBack();
                            $this->error = true;
                            $this->messege = 'Tidak Bisa Melakukan Refund Customer, Saldo Bank Tidak Mencukupi !!';

                            // Alert::error('Error', 'Tidak Bisa Melakukan Refund Customer, Saldo Bank Tidak Mencukupi !!');
                            // return redirect()->back();
                        }

                    }
                   
                    // dd('masok');
                    calculate_bank_expense($bank, $value, true);

                    $note = '-';
    
                    if ($request->note_refund[$key] !== null) {
                        $note = $request->note_refund[$key];
                    }
    
                    $transaction_name = 'Refund Customer dari '.$invoice->invoice_number. ' Ket: '.$note;
    
          
                    $create = BankHistory::create([
                        'bank_id'           => $request->bank_id_refund[$key],
                        'transaction_name'  => $transaction_name,
                        'invoice_id'        => $id,
                        'date'              => $request->date_refund[$key],
                        'product_id'        => $request->category_id_refund[$key],
                        'type'              => 'refund',
                        'nominal'           => $value,
                        'note'              => $note,
                        'created_user'      => Auth::user()->id,
                        'updated_user'      => Auth::user()->id
                    ]);

                    // calculate_receivables($invoice, $value);
                }
            }
        }
        
    }

    public function store_cashback($id, $request)
    {
        $invoice = Invoice::find($id);

        $check = BankHistory::where('invoice_id', $id)
                ->where('type', 'cashback')
                ->get();

        foreach ($check as $key => $value) {
            $bank = Bank::find($value->bank_id);
            calculate_bank_income($bank, $value->nominal, true);
            
            $value->delete();
        }
        // dd($check);

        if ($request->nominal_cashback) {
            foreach ($request->nominal_cashback as $key => $value) {
                // dd($value);
                if ($value !== null) {
                    // dd('masok');
                    $bank = Bank::find($request->bank_id_cashback[$key]);
                    // dd($bank);
                    
                    calculate_bank_income($bank, $value);

                    $note = '-';
    
                    if ($request->note_cashback[$key] !== null) {
                        $note = $request->note_cashback[$key];
                    }
    
                    $transaction_name = 'Cashback Customer dari '.$invoice->invoice_number. ' Ket: '.$note;
    
                    $create = BankHistory::create([
                        'bank_id'           => $request->bank_id_cashback[$key],
                        'transaction_name'  => $transaction_name,
                        'invoice_id'        => $id,
                        'date'              => $request->date_cashback[$key],
                        'product_id'        => $request->category_id_cashback[$key],
                        'type'              => 'cashback',
                        'nominal'           => $value,
                        'note'              => $note,
                        'created_user'      => Auth::user()->id,
                        'updated_user'      => Auth::user()->id
                    ]);

                    // calculate_receivables($invoice, $value);
                }
            }
        }
    }

    public function store_tax($id, $request)
    {
        $invoice = Invoice::find($id);

        $check = BankHistory::where('invoice_id', $id)
                ->where('type', 'tax')
                ->get();

        foreach ($check as $key => $value) {
            $bank = Bank::find($value->bank_id);
            calculate_bank_expense($bank, $value->nominal);
            
            $value->delete();
        }
        // dd($bank);

        if ($request->nominal_tax) {
            foreach ($request->nominal_tax as $key => $value) {
                if ($value !== null) {
                    $bank = Bank::find($request->bank_id_tax[$key]);
                
                    if ($bank->balance < $value) {
                        DB::rollBack();

                        Alert::error('Error', 'Tidak Bisa Melakukan Pajak & Biaya, Saldo Bank Tidak Mencukupi !!');
                        return redirect()->back();
                    }
                    
                    calculate_bank_expense($bank, $value, true);

                    $note = '-';
    
                    if ($request->note_tax[$key] !== null) {
                        $note = $request->note_tax[$key];
                    }
    
                    // $transaction_name = 'Pajak & Biaya dari '.$invoice->invoice_number. ' Ket: '.$note;
    
                    $create = BankHistory::create([
                        'bank_id'           => $request->bank_id_tax[$key],
                        'transaction_name'  => $request->name_tax[$key],
                        'invoice_id'        => $id,
                        'date'              => $request->date_tax[$key],
                        'type'              => 'tax',
                        'nominal'           => $value,
                        'note'              => $note,
                        'created_user'      => Auth::user()->id,
                        'updated_user'      => Auth::user()->id
                    ]);


                    # create tax
                    $insert = Tax::create([
                        'date'          => $request->date_tax[$key],
                        'invoice_id'    => $id,
                        'name'          => $request->name_tax[$key],
                        'nominal'       => $value,
                        'created_user'  => Auth::user()->id,
                        'updated_user'  => Auth::user()->id
                    ]);
                    // calculate_receivables($invoice, $value);
                }
            }
        }
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
                calculate_bank_income($bank, $value->nominal, true);
                calculate_receivables($invoice, $value->nominal, true);
                
                $value->delete();
            }

            // dd('masok');
            # pembayaran customer
            if ($request->nominal) {
                foreach ($request->nominal as $key => $value) {
                    if ($value !== null) {
                        $bank = Bank::find($request->bank_id[$key]);
                        calculate_bank_income($bank, $value);
    
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
            }

            # hutang vendor
            // dd($request);
            foreach ($request->inv_debt_id as $key => $value) {
                $invoice_d = InvoiceDetail::find($value);
                $status_payment = true;

                if ($invoice_d->status_debt !== 'Sudah Lunas') {
                    if ($request->payment_date[$key] !== null) {
                        $bank = Bank::find($invoice_d->from_bank);
    
                        if ($bank->balance < $invoice_d->debt_to_vendors) {
                            // dd('masok');
                            $this->error = true;
                            $this->messege = 'Tidak Bisa Melakukan Pelunasan Hutang Vendor, Saldo Bank Tidak Mencukupi !!';
                            // DB::rollBack();
    
                            // Alert::error('Error', 'Tidak Bisa Melakukan Pelunasan Hutang Vendor, Saldo Bank Tidak Mencukupi !!');
                            // return redirect()->back();
                        }
    
    
                        calculate_bank_expense($bank, $invoice_d->debt_to_vendors, true);
    
                        $invoice_d->update([
                            'status_debt'       => 'Sudah Lunas',
                            'date_payment_debt' => $request->payment_date[$key]
                        ]);
    
                        $transaction_name = 'Pelunasan Hutang Ke Vendor dari '.$invoice->invoice_number;
    
                        $create = BankHistory::create([
                            'bank_id'           => $invoice_d->from_bank,
                            'transaction_name'  => $transaction_name,
                            'invoice_id'        => $invoice_d->invoice_id,
                            'date'              => $request->payment_date[$key],
                            'type'              => 'vendor_payment',
                            'nominal'           => $invoice_d->debt_to_vendors,
                            'note'              => '-',
                            'created_user'      => Auth::user()->id,
                            'updated_user'      => Auth::user()->id
                        ]);
                    }
                }
            

                // if ($invoice_d->status_debt == 'Belum Lunas') {
                //     $status_payment = false;
                // }
            }
           
            // if ($status_payment) {
            //     $invoice->update([
            //         'status'    => 'Sudah Lunas'
            //     ]);
            // }

            # refund
            $this->store_refund($id, $request);

            # cashback
            $this->store_cashback($id, $request);

            # tax
            $this->store_tax($id, $request);

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
