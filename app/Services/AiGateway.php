<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

// Talks to the Free AI Gateway, which speaks the OpenAI chat completions format.
class AiGateway
{
    public static function isConfigured(): bool
    {
        return filled(config('services.ai_gateway.url')) && filled(config('services.ai_gateway.key'));
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     */
    public function chat(array $messages): string
    {
        if (! self::isConfigured()) {
            throw new RuntimeException('The AI gateway is not configured: set AI_GATEWAY_URL and AI_GATEWAY_KEY.');
        }

        $response = Http::withToken(config('services.ai_gateway.key'))
            ->acceptJson()
            ->timeout(60)
            ->post(rtrim(config('services.ai_gateway.url'), '/') . '/v1/chat/completions', array_filter([
                'model' => config('services.ai_gateway.model'),
                'messages' => $messages,
            ]));

        $reply = trim((string) $response->json('choices.0.message.content'));

        if ($response->failed() || $reply === '') {
            throw new RuntimeException($response->json('detail') ?? $response->json('error.message') ?? 'AI gateway returned HTTP ' . $response->status() . '.');
        }

        return $reply;
    }
}
