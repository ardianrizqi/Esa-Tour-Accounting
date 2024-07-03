<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Customer;
use App\Models\District;
use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

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
        $title      = $this->title;
        $provinces  = Province::all();
        $action     = 'Tambah';
        $customers  = Customer::all();
                
        return view('backend.invoice.form', compact('title', 'action', 'provinces', 'customers'));
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
}
