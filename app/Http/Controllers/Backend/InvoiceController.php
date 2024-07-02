<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Invoice';
    }

    public function index()
    {
        $title = $this->title;
                
        return view('backend.invoice.index', compact('title'));
    }

    public function form($id = null)
    {
        $title  = $this->title;
        $action = 'Tambah';
                
        return view('backend.invoice.form', compact('title', 'action'));
    }
}
