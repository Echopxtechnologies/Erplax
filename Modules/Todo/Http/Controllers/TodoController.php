<?php

namespace Modules\Todo\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;

class TodoController extends AdminController
{
    public function index()
    {
        $this->authorizeAdmin();
        return view('todo::index');
    }
}
