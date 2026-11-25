<?php

namespace App\Jobs;

use App\Services\AiGateway;
use App\Services\FacebookPage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

// Answers a Messenger message with the AI gateway. The last few turns of each
// conversation are kept in the cache for a day so follow-up questions make sense.
class ReplyToMessenger implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    /** @var array<int, int> */
    public array $backoff = [10];

    private const HISTORY_TURNS = 10;

    public function __construct(public string $senderId, public string $text)
    {
    }

    public function handle(AiGateway $ai, FacebookPage $facebook): void
    {
        $facebook->showTyping($this->senderId);

        $historyKey = 'messenger:history:' . $this->senderId;
        $history = Cache::get($historyKey, []);
        $history[] = ['role' => 'user', 'content' => $this->text];

        $reply = $ai->chat([['role' => 'system', 'content' => $this->systemPrompt()], ...$history]);

        $facebook->sendMessage($this->senderId, $reply);

        $history[] = ['role' => 'assistant', 'content' => $reply];
        Cache::put($historyKey, array_slice($history, -self::HISTORY_TURNS * 2), now()->addDay());
    }

    public function failed(Throwable $exception): void
    {
        Log::warning('Messenger AI reply failed', ['sender' => $this->senderId, 'error' => $exception->getMessage()]);
    }

    private function systemPrompt(): string
    {
        return implode("\n", [
            'You are the Messenger assistant for MAWIE, the Movement of Afghan Women in Exile, founded and directed by Farzana Rezaei.',
            'In Dari the organization is called «جنبش زنان افغان در تبعید»; always use exactly this name, never your own translation.',
            'MAWIE advances human rights, gender equality and the protection of Afghan women through peaceful advocacy, education and international cooperation.',
            'Reply in the language the person writes in (Dari, Pashto or English). Keep replies short, warm and respectful, suitable for a chat window.',
            'Only state facts about MAWIE given here. For anything else about the organization, membership, donations or specific cases, point people to ' . url('/') . ' or ' . config('mail.contact.address') . '.',
            'Never ask for passwords, bank details or other sensitive personal information. If someone is in danger, tell them to contact local emergency services.',
        ]);
    }
}
