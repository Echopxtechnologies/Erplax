<?php

use Illuminate\Support\Facades\Route;
use Modules\Todo\Http\Controllers\Client\ClientTodoController;

/*
|--------------------------------------------------------------------------
| Client Routes - Todo Module
|--------------------------------------------------------------------------
|
| These routes are for the client portal (authenticated clients).
| Prefix: /client/todo
| Middleware: web, client (handled by RouteServiceProvider)
|
*/

Route::prefix('todo')->name('todo.')->group(function () {
    Route::get('/', [ClientTodoController::class, 'index'])->name('index');
    Route::get('/data', [ClientTodoController::class, 'dataTable'])->name('data');
    Route::get('/{id}', [ClientTodoController::class, 'show'])->name('show');
    Route::post('/{id}/toggle-status', [ClientTodoController::class, 'toggleStatus'])->name('toggle-status');
});
