<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\CategoryExpense;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Pengeluaran';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.expense.index', compact('title'));
    }

    public function data()
    {
        // dd('masok');
        $data = Expense::with('bank', 'category_expense')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $banks   = Bank::all();
        $category_expense = CategoryExpense::all();

        if ($id) {
            $action = 'Edit';
            $data = Expense::find($id);
        }
                
        return view('backend.expense.form', compact('title', 'action', 'data', 'banks', 'category_expense'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->expense_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Expense::find($request->bank_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Expense::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.expense.index');
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
            $data = Expense::find($id);
            $data->delete();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Dihapus.',
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

    public function category_store(Request $request)
    {
        // dd($request);
        DB::beginTransaction();

        try {
            $requestData = array_merge($request->all(), [
                'name'          => $request->category_name,
                'created_user'  => Auth::user()->id,
                'updated_user'  => Auth::user()->id,
            ]);

            $data = CategoryExpense::create($requestData);

            DB::commit();

            $data = CategoryExpense::all();

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
}
