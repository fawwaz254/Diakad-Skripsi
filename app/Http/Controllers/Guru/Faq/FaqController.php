<?php

namespace App\Http\Controllers\Guru\Faq;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends BaseController
{
    public function viewIndex(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return (view('guru/faq/index',compact('auth_data')));
    }
}
