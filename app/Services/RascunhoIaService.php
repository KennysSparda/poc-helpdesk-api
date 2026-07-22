<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RascunhoIaService
{
    /**
     * Dispara a (re)geração do rascunho de resposta via n8n.
     *
     * É "fire-and-forget": não esperamos o texto de volta aqui. O n8n
     * processa em background e devolve o resultado depois, chamando de
     * volta PATCH /api/tickets/{ticket}/rascunho-ia.
     */
    public function solicitarRascunho(Ticket $ticket): void
    {
        try {
            Http::timeout(3)->post('http://192.168.193.2:5678/webhook/gerar-rascunho', [
                'id' => $ticket->id,
                'titulo' => $ticket->titulo,
                'descricao_cliente' => $ticket->descricao_cliente,
            ]);
        } catch (\Exception $e) {
            Log::warning('Não foi possível notificar o n8n: ' . $e->getMessage());
        }
    }
}
