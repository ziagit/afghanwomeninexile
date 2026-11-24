<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FacebookShareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.meta.graph_version' => 'v27.0',
            'services.meta.page_id' => '111',
            'services.meta.page_access_token' => 'page-token',
        ]);
        $this->actingAs(User::factory()->create());
    }

    public function test_new_news_with_share_ticked_is_posted_to_the_page_feed(): void
    {
        Http::fake(['graph.facebook.com/*' => Http::response(['id' => '111_222'])]);

        $this->post(route('admin.news.store'), [
            'title' => 'New office opened',
            'title_fa' => 'دفتر تازه',
            'body' => "First paragraph.\n\nSecond paragraph.\n\nThird paragraph.",
            'published_at' => '2026-11-25',
            'share_facebook' => '1',
        ])->assertRedirect(route('admin.news.index'))
            ->assertSessionHas('status', 'News created successfully. It will be shared to Facebook shortly.');

        $news = News::firstOrFail();
        $this->assertSame('111_222', $news->facebook_post_id);

        Http::assertSent(function (Request $request) use ($news) {
            return $request->url() === 'https://graph.facebook.com/v27.0/111/feed'
                && $request['link'] === route('media.show', $news)
                && $request['message'] === "New office opened\n\nFirst paragraph.\n\nSecond paragraph.\n\nدفتر تازه"
                && $request['access_token'] === 'page-token';
        });

        $this->get(route('admin.news.index'))->assertSee('https://www.facebook.com/111_222', false);
    }

    public function test_activity_with_image_is_uploaded_as_a_photo_post(): void
    {
        Storage::fake('public');
        Http::fake(['graph.facebook.com/*' => Http::response(['id' => '333', 'post_id' => '111_444'])]);

        $this->post(route('admin.activities.store'), [
            'name' => 'Advocacy',
            'type' => 'event',
            'title' => 'Rally in Berlin',
            'body' => 'We marched.',
            'image' => UploadedFile::fake()->image('rally.jpg'),
            'share_facebook' => '1',
        ])->assertRedirect(route('admin.activities.index'));

        $activity = Activity::firstOrFail();
        $this->assertSame('111_444', $activity->facebook_post_id);

        Http::assertSent(fn (Request $request) => $request->url() === 'https://graph.facebook.com/v27.0/111/photos'
            && $request->isMultipart());
    }

    public function test_nothing_is_posted_when_share_is_not_ticked(): void
    {
        Http::fake();

        $this->post(route('admin.news.store'), [
            'title' => 'Quiet news',
            'body' => 'Text',
            'published_at' => '2026-11-25',
        ])->assertSessionHas('status', 'News created successfully.');

        Http::assertNothingSent();
        $this->assertNull(News::firstOrFail()->facebook_post_id);
    }

    public function test_a_facebook_error_is_saved_and_shown_in_the_admin(): void
    {
        Http::fake(['graph.facebook.com/*' => Http::response(['error' => ['message' => 'Invalid OAuth access token.']], 400)]);

        try {
            $this->post(route('admin.news.store'), [
                'title' => 'Broken token news',
                'body' => 'Text',
                'published_at' => '2026-11-25',
                'share_facebook' => '1',
            ]);
        } catch (\RuntimeException) {
            // The sync queue in tests rethrows; a real worker retries and then records the error.
        }

        $news = News::firstOrFail();
        $this->assertNull($news->facebook_post_id);

        // The worker calls failed() once the retries run out.
        (new \App\Jobs\ShareToFacebook($news))->failed(new \RuntimeException('Invalid OAuth access token.'));

        $this->get(route('admin.news.index'))->assertSee('Failed');
        $this->get(route('admin.news.edit', $news))->assertSee('Last attempt failed: Invalid OAuth access token.');
    }

    public function test_an_item_already_on_facebook_is_not_posted_again(): void
    {
        Http::fake();
        $news = News::create(['title' => 'Posted', 'body' => 'Text', 'published_at' => '2026-11-25']);
        $news->forceFill(['facebook_post_id' => '111_9'])->save();

        $this->put(route('admin.news.update', $news), [
            'title' => 'Posted, edited',
            'body' => 'Text',
            'published_at' => '2026-11-25',
            'share_facebook' => '1',
        ]);

        Http::assertNothingSent();
    }
}
