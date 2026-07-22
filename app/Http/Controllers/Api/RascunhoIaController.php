<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\RascunhoIaService;
use Illuminate\Http\Request;

class RascunhoIaController extends Controller
{
    public function __construct(private RascunhoIaService $rascunhoIaService)
    {
    }

    /**
     * POST /api/tickets/{ticket}/rascunho-ia
     *
     * Dispara uma (re)geração do rascunho via IA. Não devolve o texto na
     * hora porque a geração é assíncrona (feita pelo n8n) — por isso o
     * 202 Accepted em vez de 200/201.
     */
    public function store(Ticket $ticket)
    {
        $this->rascunhoIaService->solicitarRascunho($ticket);

        return response()->json([
            'message' => 'Geração do rascunho solicitada.',
        ], 202);
    }

    /**
     * PATCH /api/tickets/{ticket}/rascunho-ia
     *
     * Salva o texto do rascunho. Usado tanto pelo agente (edição manual,
     * botão "Salvar Rascunho") quanto pelo próprio n8n, como callback,
     * quando o texto gerado pela IA fica pronto.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'rascunho_ia' => 'required|string',
        ]);

        $ticket->update($validated);

        return response()->json($ticket);
    }
}
