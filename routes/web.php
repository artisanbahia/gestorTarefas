<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main;






// Testando a conexão com a base de dados: 



Route::get('/testando-conexao', function () {
    
    
      try{

         DB::connection()->getPdo();
          echo "Conexão realizada com sucesso com o DB: " . DB::connection()->getDatabaseName();

      } catch (\Exception $e){
          die("Não foi possível acessar a base de dados. Erro: " . $e->getMessage());
      }

});



// Rota chamando classe/método controller
Route::get('/main', [Main::class, 'index']);


// Rota chamando uma view
Route::get('/view-main', function(){

    $dados = [
        'title' => 'Artisan Bahia',
        'description' => 'Site do grupo Artisan Bahia',
        'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit porro repudiandae ut doloribus fuga rem asperiores numquam optio molestias praesentium! Dignissimos officia repudiandae quidem nostrum. Asperiores enim nesciunt illum vel!
        '
    ];

    return view('primeira-view', $dados);
});


Route::get('users', [Main::class, 'users']);