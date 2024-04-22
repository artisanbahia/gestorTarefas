<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{

    public function index()
    {
        echo "Gestor de tarefas";
    }



// -----------------------------------------------------------------------------------
// Método de login
//------------------------------------------------------------------------------------

    public function login()
    {
        $data = [
            'title' => 'Login'
        ];
        return view('login_frm', $data);
    }


    public function login_submit()
    {
        echo "Logado";
    }

    public function login_frm()
    {
        session()->put('username', 'admin'); // Maneira de setar (colocar) uma variável
        echo "usuário Logado";
    }


    public function logout()
    {
        session()->forget('username'); // Esqueça a variável username
        return redirect()->route('login'); // Redirecione para  página de login
    }

// -------------------------------------------------------------------------------------
// Método de acesso pós logoinz
//--------------------------------------------------------------------------------------


    public function main()
    {
        $data = [
            'title' => 'Gestor de Tarefas'
        ];

        return view('main', $data);
    }




}


