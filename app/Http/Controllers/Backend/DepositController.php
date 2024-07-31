<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Deposit';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.deposit.index', compact('title'));
    }

    public function data()
    {
        $data = Deposit::with('bank')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $banks   = Bank::all();

        if ($id) {
            $data = Deposit::find($id);
        }
                
        return view('backend.deposit.form', compact('title', 'action', 'data', 'banks'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $nominal = $request->nominal;
        $nominal = str_replace('.', '', $nominal);
        $nominal = str_replace(',', '', $nominal);
        $nominal = preg_replace('/[^0-9]/', '', $nominal);
    
        // Convert the nominal to a float if needed
        $nominal = floatval($nominal);
        // dd($nominal);

        try {
            if ($request->deposit_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                    'nominal'   => $nominal,
                ]);

                $data = Deposit::find($request->bank_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                    'nominal'   => $nominal
                ]);

                $data = Deposit::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.deposit.index');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $data = Deposit::find($id);
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
