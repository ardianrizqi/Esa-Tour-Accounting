<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;


class BankController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Bank';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.bank.index', compact('title'));
    }

    public function data()
    {
        $data = Bank::orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $accounts   = Customer::all();

        if ($id) {
            $data = Bank::find($id);
        }
                
        return view('backend.bank.form', compact('title', 'action', 'data', 'accounts'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->product_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                    'balance'       => $request->beginning_balance
                ]);

                $data = Bank::find($request->bank_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                    'balance'       => $request->beginning_balance
                ]);

                $data = Bank::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.bank.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $data = Bank::find($id);
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

    public function history($id)
    {
        $title      = 'Riwayat Bank';
                
        return view('backend.bank.history', compact('title', 'id'));
    }

    public function history_data(Request $request)
    {
        // dd($request);
        $data = BankHistory::where('bank_id', $request->bank_id);

        if ($request->date) {
            $data->whereDate('date', $request->date);
        }
     
        return response()->json(['data' => $data->get()]);
    }
}
