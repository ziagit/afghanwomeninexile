<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class YouTubeGalleryTest extends TestCase
{
    use RefreshDatabase;

    private const FEED = <<<'XML'
        <?xml version="1.0" encoding="UTF-8"?>
        <feed xmlns:yt="http://www.youtube.com/xml/schemas/2015" xmlns="http://www.w3.org/2005/Atom">
            <entry>
                <yt:videoId>cObZc8-PfJQ</yt:videoId>
                <title>August 15: A Defining Moment for Afghan Women</title>
                <published>2026-08-22T10:00:00+00:00</published>
            </entry>
            <entry>
                <yt:videoId>45kJn4C77Pk</yt:videoId>
                <title>صدای خاموش | روایت کابل</title>
                <published>2026-08-15T10:00:00+00:00</published>
            </entry>
        </feed>
        XML;

    public function test_gallery_shows_latest_channel_videos(): void
    {
        Http::fake(['www.youtube.com/feeds/*' => Http::response(self::FEED)]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSeeInOrder(['August 15: A Defining Moment for Afghan Women', 'صدای خاموش | روایت کابل'])
            ->assertSee('data-video-link="https://www.youtube.com/watch?v=cObZc8-PfJQ"', false)
            ->assertSee('https://i.ytimg.com/vi/45kJn4C77Pk/hqdefault.jpg', false)
            ->assertSee('href="https://www.youtube.com/@movementofAfgwomeninexile"', false);

        $this->get(route('fa.gallery'))->assertOk()->assertSee('22 اگست 2026')->assertSee('ویدیوهای بیشتر در یوتیوب');

        Http::assertSentCount(1);
    }

    public function test_gallery_reads_videos_back_from_a_persistent_cache(): void
    {
        // The live site caches in the database, which only restores plain values.
        config(['cache.default' => 'database']);
        Http::fake(['www.youtube.com/feeds/*' => Http::response(self::FEED)]);

        $this->get(route('gallery'))->assertOk();

        $this->get(route('fa.gallery'))
            ->assertOk()
            ->assertSee('August 15: A Defining Moment for Afghan Women')
            ->assertSee('22 اگست 2026');

        Http::assertSentCount(1);
    }

    public function test_gallery_keeps_last_videos_when_youtube_is_down(): void
    {
        Http::fakeSequence('www.youtube.com/feeds/*')->push(self::FEED)->push('', 500);

        $this->get(route('gallery'))->assertSee('August 15: A Defining Moment for Afghan Women');

        $this->travel(2)->hours();

        $this->get(route('gallery'))->assertOk()->assertSee('August 15: A Defining Moment for Afghan Women');
    }

    public function test_gallery_without_any_videos_points_to_youtube(): void
    {
        Http::fake(['www.youtube.com/feeds/*' => Http::response('', 500)]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Videos could not be loaded right now. Please watch them on our YouTube channel.');
    }
}
