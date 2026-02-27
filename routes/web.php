<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Importacao\NcmClassificacaoController;
use App\Http\Controllers\Importacao\ClassificacaoTributariaController;
use App\Http\Controllers\Consulta\NcmController as NcmConsultaController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('consulta')->name('consulta.')->middleware('auth')->group(function () {
    Route::get('/ncm',    [NcmConsultaController::class, 'index'])->name('ncm.index');
    Route::post('/ncm',   [NcmConsultaController::class, 'buscar'])->name('ncm.buscar');
});

Route::prefix('importacao')->name('importacao.')->middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('importacao.index');
    })->name('index');

    Route::prefix('ncm-classificacao')->name('ncm-classificacao.')->group(function () {
        Route::get('/',          [NcmClassificacaoController::class, 'index'])->name('index');
        Route::post('/importar', [NcmClassificacaoController::class, 'importar'])->name('importar');
    });

    Route::prefix('classificacao-tributaria')->name('classificacao-tributaria.')->group(function () {
        Route::get('/',          [ClassificacaoTributariaController::class, 'index'])->name('index');
        Route::post('/importar', [ClassificacaoTributariaController::class, 'importar'])->name('importar');
    });

});
