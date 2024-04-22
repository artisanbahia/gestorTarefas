<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main;
use App\Http\Middleware\CheckLogin; // Trazendo o middleware para uso conjunto com as rotas de login
use App\Http\Middleware\CheckLogout;

// -----> Modelos de rota

// Route::get('/view-main', function(){
//     return view('primeira-view', $dados);
// });

// Route::get('/', [Main::class, 'index']);





//----------------------------------------------------------------------------------------------




// Rota para formulário de Login


// OUT APP -

    Route::get('/login', [Main::class, 'login'])->name('login')->middleware(CheckLogout::class); // Nova maneira de referenciar um Middleware no Laravel 11, que não traz o script de Kernel na pasta HTTP
    Route::post('/login_submit', [Main::class, 'login_submit'])->name('login_submit'); // Método post



// IN APP - Route witch Middleware [Rotas abrangidas pela verificação]

    Route::get('/main', [Main::class, 'main'])->name('main')->middleware(CheckLogin::class); // O acesso à rota Main requer verificação da regra do Middleware. Caso 'caia na malha fina' do Middleware, executa a regra do Middleware. Do contrário, executa o método que está previsto no controller.
    Route::get('/', [Main::class, 'main'])->name('index')->middleware(CheckLogin::class);
    Route::get('/logout', [Main::class, 'logout'])->name('logout')->middleware(CheckLogin::class); // Se tentar fazer logout sem estar logado, executa o CheckLogin




