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

//----------------------------------------------




// OUT APP -

    Route::get('/login', [Main::class, 'login'])->name('login')->middleware(CheckLogout::class); // Nova maneira de referenciar um Middleware no Laravel 11, que não traz o script de Kernel na pasta HTTP
    Route::post('/login_submit', [Main::class, 'login_submit'])->name('login_submit'); // Método post



// IN APP - Route witch Middleware [Rotas abrangidas pela verificação]

    Route::get('/', [Main::class, 'index'])->name('index')->middleware(CheckLogin::class); // O acesso à rota Main requer verificação da regra do Middleware. Caso 'caia na malha fina' do Middleware, executa a regra do Middleware. Do contrário, executa o método que está previsto no controller.
    Route::get('/logout', [Main::class, 'logout'])->name('logout')->middleware(CheckLogin::class); // Se tentar fazer logout sem estar logado, executa o CheckLogin



// TASKS - NEWS
    Route::get('/new_task', [Main::class, 'new_task'])->name('new_task')->middleware(CheckLogin::class); // Formulário de inserção de nova task
    Route::post('/new_task_submit', [Main::class, 'new_task_submit'])->name('new_task_submit')->middleware(CheckLogin::class); // Submissão de inserção de nova task



// TASKS - EDIT
    Route::get('/edit_task/{id}', [Main::class, 'edit_task'])->name('edit_task')->middleware(CheckLogin::class); // Formulário de inserção de nova task
    Route::post('/edit_task_submit', [Main::class, 'edit_task_submit'])->name('edit_task_submit')->middleware(CheckLogin::class); // Submissão de inserção de nova task



// TASKS - DELETE
Route::get('/delete_task/{id}', [Main::class, 'delete_task'])->name('delete_task')->middleware(CheckLogin::class); // Formulário de inserção de nova task
Route::post('/delete_task_confirm/{id}', [Main::class, 'delete_task_confirm'])->name('delete_task_confirm')->middleware(CheckLogin::class); // Submissão de inserção de nova task




