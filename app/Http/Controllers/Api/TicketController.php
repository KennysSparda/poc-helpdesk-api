<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Ticket::orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao_cliente' => 'required|string',
            'status' => 'nullable|string',
            'rascunho_ia' => 'nullable|string',
        ]);

        // Garante o status inicial padrão se não vier preenchido
        $validated['status'] = $validated['status'] ?? 'aberto';

        $ticket = Ticket::create($validated);

        // Dispara o webhook para o n8n na máquina da RTX de forma assíncrona
        try {
            Http::timeout(3)->post('http://192.168.193.2:5678/webhook/gerar-rascunho', [
                'id' => $ticket->id,
                'titulo' => $ticket->titulo,
                'descricao_cliente' => $ticket->descricao_cliente,
            ]);
        } catch (\Exception $e) {
            Log::warning('Não foi possível notificar o n8n: ' . $e->getMessage());
        }

        return response()->json($ticket, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return response()->json($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'descricao_cliente' => 'sometimes|string',
            'status' => 'sometimes|string',
            'rascunho_ia' => 'nullable|string',
        ]);

        $ticket->update($validated);

        return response()->json([
            'message' => 'Chamado atualizado com sucesso',
            'ticket' => $ticket
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json(null, 204);
    }
}
