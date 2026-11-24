<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MetaPageToken extends Command
{
    protected $signature = 'meta:page-token';

    protected $description = 'Exchange a Graph API Explorer user token for a non-expiring Facebook Page token and save it to .env';

    public function handle(): int
    {
        $config = config('services.meta');

        if (! $config['app_id'] || ! $config['app_secret']) {
            $this->error('Set META_APP_ID and META_APP_SECRET in .env first.');

            return self::FAILURE;
        }

        $graph = 'https://graph.facebook.com/' . $config['graph_version'];
        $userToken = trim((string) $this->secret('Paste the user access token from Graph API Explorer'));

        // A long-lived user token makes the Page tokens derived from it never expire.
        $exchange = Http::get($graph . '/oauth/access_token', [
            'grant_type' => 'fb_exchange_token',
            'client_id' => $config['app_id'],
            'client_secret' => $config['app_secret'],
            'fb_exchange_token' => $userToken,
        ]);

        if ($exchange->failed()) {
            $this->error('Token exchange failed: ' . $exchange->json('error.message', $exchange->body()));

            return self::FAILURE;
        }

        $pages = Http::get($graph . '/me/accounts', [
            'fields' => 'id,name,access_token,instagram_business_account{id,username}',
            'access_token' => $exchange->json('access_token'),
        ]);

        $pageList = collect($pages->json('data', []));

        if ($pages->failed() || $pageList->isEmpty()) {
            $this->error('No Pages found: ' . $pages->json('error.message', 'grant access to the Page when generating the token.'));

            return self::FAILURE;
        }

        $choice = $pageList->count() === 1
            ? $pageList->first()['name']
            : $this->choice('Which Page should the website post to?', $pageList->pluck('name')->all());
        $page = $pageList->firstWhere('name', $choice);

        $debug = Http::get($graph . '/debug_token', [
            'input_token' => $page['access_token'],
            'access_token' => $config['app_id'] . '|' . $config['app_secret'],
        ]);
        $expiresAt = (int) $debug->json('data.expires_at', -1);

        $this->writeEnv([
            'META_PAGE_ID' => $page['id'],
            'META_PAGE_ACCESS_TOKEN' => $page['access_token'],
            'META_INSTAGRAM_USER_ID' => $page['instagram_business_account']['id'] ?? '',
        ]);

        $this->info('Saved token for Page "' . $page['name'] . '" (' . $page['id'] . ').');
        $this->line('Expires: ' . ($expiresAt === 0 ? 'never' : ($expiresAt > 0 ? date('Y-m-d H:i', $expiresAt) : 'unknown')));
        $this->line('Instagram: ' . (isset($page['instagram_business_account'])
            ? '@' . ($page['instagram_business_account']['username'] ?? '?') . ' (' . $page['instagram_business_account']['id'] . ')'
            : 'no Instagram professional account linked to this Page'));
        $this->line('Granted scopes: ' . implode(', ', $debug->json('data.scopes', [])));

        return self::SUCCESS;
    }

    /** @param array<string, string> $values */
    private function writeEnv(array $values): void
    {
        $path = base_path('.env');
        $contents = file_get_contents($path);

        foreach ($values as $key => $value) {
            $line = $key . '=' . $value;
            $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';

            $contents = preg_match($pattern, $contents)
                ? preg_replace($pattern, $line, $contents)
                : rtrim($contents) . PHP_EOL . $line . PHP_EOL;
        }

        file_put_contents($path, $contents);
    }
}
