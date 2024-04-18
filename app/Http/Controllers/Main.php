<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserModel;

class Main extends Controller
{
    
    public function index(){
        
        echo "Primeira rota criada";
    }



    public function users(){

        // ---------------------------------
        // ATENÇÃO - o método dd() (dump and die) interrompe a execução do código imediatamente após exibir os dados fornecidos. Isso significa que qualquer código que venha após a chamada do dd() não será executado. Por isso, todos os dds estão comentados para que o print_r e o foreach sejam processados. 
        // ---------------------------------
    
        // ----> forma elementar de uso da classe e método DB::select para realizar uma consulta select e apresentar dados
       $users = DB::select('SELECT * FROM users');
       
            // Maneira 1 de apresentar os dados, usando a função dd()
            // dd($users); // Uma função nativa que apresenta os resultados na tela
    
            // Maneira 2 de apresentar os dados
            echo "<pre>";
            print_r($users);
            echo "</pre>";

            // Maneira 3 de apresentar os dados
            echo $users[0]->username;


        // ----> Usando Query Builder
        // $users = DB::table('users')->get(); // Consulta sem uso de cláusula
        $users = DB::table('users')->where('username', 'user2')->orWhere('username', 'user3')->get(); // Consulta mais complexa passando cláusulas
        dd($users);

            // ---------------------------------------------------------------------------------------------------------
            // POSSO ENCADEAR MÚLTIPLAS CLÁUSULAS WHERE QUE SIMULARIA USO DO AND E POSSO USAR orWhere PARA A CLÁUSULA OR
            // ---------------------------------------------------------------------------------------------------------


       // Retorando os dados com Query Builder - RETORNA ARRAY
       $users = DB::table('users')->get()->toArray();
       // dd($users);

        
        // ----> Usando outro método para realizar a consulta e retornar os dados
        $model = new UserModel();
        $users = $model->all(); // Como a classe UserModel implementa protected $table = 'users', virá todos os dados da tabela 'users'
        // dd($users);

            // Apresentando os dados através de uma iteração
            foreach($users as $user)
            {
                echo "<pre>";
                echo $user->password;
                echo "   |   ";
                echo $user->id;
                echo "   |   ";
                echo $user->created_at;
                echo "</pre>";
            }
       
    }
}


