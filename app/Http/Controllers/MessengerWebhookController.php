<?php

namespace App\Http\Controllers;

use App\Jobs\ReplyToMessenger;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Receives Messenger messages sent to the Facebook Page and queues an AI reply for each one.
class MessengerWebhookController extends Controller
{
    // Meta calls this once when the webhook is set up in the app dashboard.
    public function verify(Request $request): Response
    {
        $token = config('services.meta.webhook_verify_token');

        if ($request->query('hub_mode') === 'subscribe' && filled($token) && hash_equals($token, (string) $request->query('hub_verify_token'))) {
            return response((string) $request->query('hub_challenge'));
        }

        return response('Forbidden', 403);
    }

    public function receive(Request $request): Response
    {
        if (! $this->signatureIsValid($request)) {
            return response('Invalid signature', 403);
        }

        if ($request->input('object') !== 'page') {
            return response('Ignored');
        }

        foreach ($request->input('entry', []) as $entry) {
            foreach ($entry['messaging'] ?? [] as $event) {
                $senderId = $event['sender']['id'] ?? null;
                $text = trim((string) ($event['message']['text'] ?? ''));

                // Echoes are the Page's own replies; attachments without text are skipped.
                if (! $senderId || $text === '' || ! empty($event['message']['is_echo'])) {
                    continue;
                }

                ReplyToMessenger::dispatch($senderId, $text);
            }
        }

        // Meta retries anything that isn't a quick 200, so the reply itself happens in the queue.
        return response('EVENT_RECEIVED');
    }

    private function signatureIsValid(Request $request): bool
    {
        $secret = config('services.meta.app_secret');
        $signature = (string) $request->header('X-Hub-Signature-256');

        return filled($secret)
            && hash_equals('sha256=' . hash_hmac('sha256', $request->getContent(), $secret), $signature);
    }
}
