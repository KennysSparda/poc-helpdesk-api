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
        $baseUrl = config('services.n8n.uri');
        $port = config('services.n8n.port');
        
        $url = "{$baseUrl}:{$port}/webhook/gerar-rascunho";
        
        try {
            Http::timeout(3)->post($url, [
                'id' => $ticket->id,
                'titulo' => $ticket->titulo,
                'descricao_cliente' => $ticket->descricao_cliente,
            ]);
        } catch (\Exception $e) {
            Log::warning('Não foi possível notificar o n8n: ' . $e->getMessage());
        }
    }
}
