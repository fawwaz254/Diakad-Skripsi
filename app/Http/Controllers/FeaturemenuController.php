<?php

namespace App\Http\Controllers;

use App\Models\Featuremenu;
use App\Models\Modul;
use Barryvdh\Debugbar\Controllers\BaseController;
use Illuminate\Http\Request;

class FeaturemenuController extends BaseController
{
    public function index(){
        $menu = Featuremenu::get();
        $modul = Modul::get();
        return view('administrator/manajemen-menu/setting-feature/view-setting-feature',compact('menu','modul'));
    }
    public function save(Request $request){
        // dd($request->all());
        $menu = Featuremenu::get();
        $modul = Modul::get();
        $input = $request->except('_token','auth_data');
        foreach($input as $key => $value) {
  
            // dump($key, $value);
            $menu1 = Featuremenu::where('id_modul',$key)->first();
            $menu1->is_aktif = $value;
            $menu1->save();
        }
        
        return view('administrator/manajemen-menu/setting-feature/view-setting-feature',compact('menu','modul'));
    }
}
