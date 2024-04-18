<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main;

// -----> Modelos de rota

// Route::get('/view-main', function(){
//     return view('primeira-view', $dados);
// });

// Route::get('/', [Main::class, 'index']);


//----------------------------------------------------------------------------------------------


// Entrada da aplicação
Route::get('/', [Main::class, 'index'])->name('index'); // atribuindo um nome para esta rota




