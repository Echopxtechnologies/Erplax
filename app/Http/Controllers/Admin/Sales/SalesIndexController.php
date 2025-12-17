<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;

class SalesIndexController extends AdminController
{
    public function index()
    {
        return view('admin.sales.index');
    }
}
