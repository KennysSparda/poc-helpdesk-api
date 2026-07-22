<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class RespostaFinalController extends Controller
{
    /**
     * POST /api/tickets/{ticket}/resposta-final
     *
     * Publica a resposta final pro cliente e resolve o chamado.
     * Antes disso, o cliente só enxerga "em análise" — o rascunho
     * (rascunho_ia) nunca é exposto a ele diretamente.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'resposta_final' => 'required|string',
        ]);

        $ticket->update([
            'resposta_final' => $validated['resposta_final'],
            'status' => 'resolvido',
            'respondido_em' => now(),
        ]);

        return response()->json($ticket);
    }
}
