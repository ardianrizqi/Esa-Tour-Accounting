<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Modal';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.asset.index', compact('title'));
    }

    public function data()
    {
        // dd('masok');
        $data = Asset::with('bank')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $banks   = Bank::all();

        if ($id) {
            $action = 'Edit';
            $data = Asset::find($id);
        }
                
        return view('backend.asset.form', compact('title', 'action', 'data', 'banks'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->asset_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Asset::find($request->bank_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Asset::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.asset.index');
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
            $data = Asset::find($id);
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
