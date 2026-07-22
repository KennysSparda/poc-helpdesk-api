<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\RascunhoIaController;
use App\Http\Controllers\Api\RespostaFinalController;

// CRUD puro do recurso "tickets"
Route::apiResource('tickets', TicketController::class);

Route::apiSingleton('tickets.rascunho-ia', RascunhoIaController::class)
    ->creatable()
    ->only(['store', 'update']);

Route::apiSingleton('tickets.resposta-final', RespostaFinalController::class)
    ->creatable()
    ->only(['store']);
