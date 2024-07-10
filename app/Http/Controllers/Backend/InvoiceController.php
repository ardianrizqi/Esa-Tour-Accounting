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
        $action             = 'Tambah';
        $customers          = Customer::all();
        $products           = Product::all();
        $physical_invoie    = PhysicalInvoice::all();
        $bank               = Bank::all();

        if ($id) {
            $data   = Invoice::find($id);
            $data_d = InvoiceDetail::where('invoice_id', $data->id)->get();
        }
                
        return view('backend.invoice.form', compact('title', 'action', 'provinces', 'customers', 'products', 'physical_invoie', 'bank', 'data'));
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
                'created_user'          => Auth::user()->id,
                'updated_user'          => Auth::user()->id
            ];

            $insert_h = Invoice::create($param_h);

            foreach ($request->category_id as $key => $value) {
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
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id
                ];

                $insert_d = InvoiceDetail::create($param_d);
            }

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

}
