<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

// Latest uploads of the movement's YouTube channel, read from its public feed (no API key needed).
class YouTubeChannel
{
    private const CACHE_KEY = 'youtube.videos';

    // The feed lists the 15 most recent uploads.
    /** @return array<int, array{id: string, title: string, url: string, thumbnail: string, published_at: Carbon}> */
    public static function latestVideos(): array
    {
        $videos = Cache::get(self::CACHE_KEY);

        if (is_array($videos)) {
            return $videos;
        }

        try {
            $videos = self::fetch();
            Cache::put(self::CACHE_KEY, $videos, now()->addHour());
            Cache::forever(self::CACHE_KEY.'.last', $videos);
        } catch (\Throwable $exception) {
            report($exception);

            // Keep showing the last good list, and try YouTube again in a few minutes.
            $videos = Cache::get(self::CACHE_KEY.'.last', []);
            Cache::put(self::CACHE_KEY, $videos, now()->addMinutes(5));
        }

        return $videos;
    }

    private static function fetch(): array
    {
        $response = Http::timeout(5)
            ->get('https://www.youtube.com/feeds/videos.xml', ['channel_id' => config('mawie.youtube.channel_id')])
            ->throw();

        $feed = simplexml_load_string($response->body());

        if ($feed === false) {
            throw new \RuntimeException('The YouTube feed could not be read.');
        }

        $videos = [];

        foreach ($feed->entry as $entry) {
            $id = (string) $entry->children('yt', true)->videoId;

            if ($id === '') {
                continue;
            }

            $videos[] = [
                'id' => $id,
                'title' => trim((string) $entry->title),
                'url' => 'https://www.youtube.com/watch?v='.$id,
                'thumbnail' => 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg',
                'published_at' => Carbon::parse((string) $entry->published),
            ];
        }

        return $videos;
    }
}
