<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

// Publishes to the organization's Facebook Page with the Page token from .env.
class FacebookPage
{
    public static function isConfigured(): bool
    {
        return filled(config('services.meta.page_id')) && filled(config('services.meta.page_access_token'));
    }

    // Returns the new post's ID. A picture is uploaded from the public disk, so posting
    // also works before the site is reachable from the internet.
    public function publish(string $message, string $link, ?string $imagePath = null): string
    {
        if (! self::isConfigured()) {
            throw new RuntimeException('Facebook is not configured: set META_PAGE_ID and META_PAGE_ACCESS_TOKEN.');
        }

        $endpoint = 'https://graph.facebook.com/' . config('services.meta.graph_version') . '/' . config('services.meta.page_id');
        $token = config('services.meta.page_access_token');

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            // Photo posts can't carry a link preview, so the link goes at the end of the caption.
            $response = Http::attach('source', Storage::disk('public')->get($imagePath), basename($imagePath))
                ->post($endpoint . '/photos', [
                    'caption' => $message . "\n\n" . $link,
                    'access_token' => $token,
                ]);

            return $this->postId($response, 'post_id');
        }

        $response = Http::asForm()->post($endpoint . '/feed', [
            'message' => $message,
            'link' => $link,
            'access_token' => $token,
        ]);

        return $this->postId($response, 'id');
    }

    private function postId(Response $response, string $key): string
    {
        if ($response->failed() || blank($response->json($key))) {
            throw new RuntimeException($response->json('error.message') ?? 'Facebook returned HTTP ' . $response->status() . '.');
        }

        return (string) $response->json($key);
    }
}
