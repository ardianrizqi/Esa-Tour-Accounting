<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TaxController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Pajak';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.tax.index', compact('title'));
    }

    public function data()
    {
        $data = Tax::with('invoice')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $invoice   = Invoice::all();

        if ($id) {
            $data = Tax::find($id);
        }
                
        return view('backend.tax.form', compact('title', 'action', 'data', 'invoice'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->tax_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Tax::find($request->tax_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Tax::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.tax.index');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $data = Tax::find($id);
            $data->delete();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Dihapus.',
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
