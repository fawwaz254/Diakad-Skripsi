<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankController extends Controller
{

    public function kbbs(Request $request)
    {
        $input = (object) $request->input();
        dd('test');
    }
}
