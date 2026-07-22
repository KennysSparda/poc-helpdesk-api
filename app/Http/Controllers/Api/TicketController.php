<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\RascunhoIaService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private RascunhoIaService $rascunhoIaService)
    {
    }

    public function index()
    {
        return response()->json(Ticket::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao_cliente' => 'required|string',
        ]);

        $validated['status'] = 'aberto';

        $ticket = Ticket::create($validated);

        $this->rascunhoIaService->solicitarRascunho($ticket);

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket)
    {
        return response()->json($ticket);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'descricao_cliente' => 'sometimes|string',
            'rascunho_ia' => 'nullable|string',
            'resposta_final' => 'nullable|string',
            'status' => 'sometimes|string|in:aberto,respondido,resolvido',
        ]);

        $ticket->update($validated);

        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json(null, 204);
    }
}
