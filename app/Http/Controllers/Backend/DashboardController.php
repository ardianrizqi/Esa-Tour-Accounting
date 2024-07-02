<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Home';
    }

    public function index()
    {
        $title = $this->title;
                
        return view('backend.dashboard.index', compact('title'));
    }
}
