<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{
    
    public function index(){
        
        echo "Gestor de tarefas";

    }

    public function login(){

        $data = [
            'title' => 'Login'
        ];

        return view('login_frm', $data);
    }

    public function login_frm(){
        echo "login_frm";
    }



    
}


