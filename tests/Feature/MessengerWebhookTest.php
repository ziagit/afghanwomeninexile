<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MessengerWebhookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'cache.default' => 'array',
            'services.meta.graph_version' => 'v27.0',
            'services.meta.app_secret' => 'app-secret',
            'services.meta.page_access_token' => 'page-token',
            'services.meta.webhook_verify_token' => 'verify-me',
            'services.ai_gateway.url' => 'https://gateway.test',
            'services.ai_gateway.key' => 'gateway-key',
        ]);
    }

    public function test_meta_can_verify_the_webhook_with_the_right_token(): void
    {
        $this->get('/webhooks/messenger?hub_mode=subscribe&hub_verify_token=verify-me&hub_challenge=12345')
            ->assertOk()
            ->assertSeeText('12345');

        $this->get('/webhooks/messenger?hub_mode=subscribe&hub_verify_token=wrong&hub_challenge=12345')
            ->assertForbidden();
    }

    public function test_unsigned_messages_are_rejected(): void
    {
        Http::fake();

        $this->postJson('/webhooks/messenger', $this->payload('Hello'), ['X-Hub-Signature-256' => 'sha256=nope'])
            ->assertForbidden();

        Http::assertNothingSent();
    }

    public function test_a_message_is_answered_by_the_ai_and_the_conversation_is_remembered(): void
    {
        Http::fake([
            'gateway.test/*' => Http::sequence()
                ->push(['choices' => [['message' => ['content' => 'Salaam! How can I help?']]]])
                ->push(['choices' => [['message' => ['content' => 'Visit our website.']]]]),
            'graph.facebook.com/*' => Http::response(['message_id' => 'm1']),
        ]);

        $this->sendSigned($this->payload('Hello'))->assertOk()->assertSeeText('EVENT_RECEIVED');
        $this->sendSigned($this->payload('How do I join?'))->assertOk();

        Http::assertSent(fn (Request $request) => $request->url() === 'https://graph.facebook.com/v27.0/me/messages'
            && $request['recipient'] === ['id' => 'user-1']
            && ($request->data()['message'] ?? null) === ['text' => 'Salaam! How can I help?']
            && $request['access_token'] === 'page-token');

        // The second AI call carries the first exchange as context.
        Http::assertSent(function (Request $request) {
            if ($request->url() !== 'https://gateway.test/v1/chat/completions' || count($request['messages']) !== 4) {
                return false;
            }

            return $request->hasHeader('Authorization', 'Bearer gateway-key')
                && $request['messages'][0]['role'] === 'system'
                && array_slice($request['messages'], 1) === [
                    ['role' => 'user', 'content' => 'Hello'],
                    ['role' => 'assistant', 'content' => 'Salaam! How can I help?'],
                    ['role' => 'user', 'content' => 'How do I join?'],
                ];
        });
    }

    public function test_page_echoes_are_not_answered(): void
    {
        Http::fake();

        $payload = $this->payload('Our own reply');
        $payload['entry'][0]['messaging'][0]['message']['is_echo'] = true;

        $this->sendSigned($payload)->assertOk();

        Http::assertNothingSent();
    }

    private function payload(string $text): array
    {
        return [
            'object' => 'page',
            'entry' => [[
                'id' => '111',
                'messaging' => [[
                    'sender' => ['id' => 'user-1'],
                    'recipient' => ['id' => '111'],
                    'message' => ['mid' => 'mid.1', 'text' => $text],
                ]],
            ]],
        ];
    }

    private function sendSigned(array $payload)
    {
        $body = json_encode($payload);

        return $this->call('POST', '/webhooks/messenger', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_HUB_SIGNATURE_256' => 'sha256=' . hash_hmac('sha256', $body, 'app-secret'),
        ], $body);
    }
}
