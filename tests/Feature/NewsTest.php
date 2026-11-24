<?php

namespace Tests\Feature;

use App\Models\Galery;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_page_lists_news_newest_first(): void
    {
        News::create(['title' => 'Older news', 'body' => 'Older text', 'published_at' => '2026-01-10']);
        $latest = News::create(['title' => 'Latest news', 'body' => 'Latest text', 'published_at' => '2026-11-20']);

        $this->get(route('media'))
            ->assertOk()
            ->assertSeeInOrder(['Latest news', 'Older news'])
            ->assertSee(route('media.show', $latest), false);

        $this->get(route('media.show', $latest))
            ->assertOk()
            ->assertSee('Latest text')
            ->assertSee('20 November 2026');
    }

    public function test_media_page_without_news_says_so(): void
    {
        $this->get(route('media'))->assertOk()->assertSee('No news has been published yet.');
    }

    public function test_news_shows_dari_copy_on_dari_site(): void
    {
        $news = News::create([
            'title' => 'Statement',
            'title_fa' => 'اعلامیه تازه',
            'body' => 'English text',
            'body_fa' => 'متن دری',
            'published_at' => '2026-11-20',
        ]);

        $this->get(route('fa.media'))->assertOk()->assertSee('اعلامیه تازه')->assertSee('20 نومبر 2026');
        $this->get(route('fa.media.show', $news))->assertOk()->assertSee('متن دری')->assertDontSee('English text');
    }

    public function test_gallery_page_has_photos_and_is_linked_from_footer_only(): void
    {
        Galery::create(['name' => 'Herat protest', 'details' => 'Photos']);

        $this->get(route('gallery'))->assertOk()->assertSee('Herat protest')->assertSee('More videos on YouTube');

        $home = $this->get(route('home'))->assertOk()->getContent();
        $header = substr($home, strpos($home, '<header'), strpos($home, '</header>') - strpos($home, '<header'));
        $footer = substr($home, strpos($home, '<footer'));

        $this->assertStringNotContainsString(route('gallery'), $header);
        $this->assertStringContainsString(route('gallery'), $footer);
        $this->assertStringContainsString(route('media'), $header);
    }

    public function test_admin_manages_news_in_both_languages(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.news.store'), [
            'title' => 'New office opened',
            'title_fa' => 'دفتر تازه گشایش یافت',
            'body' => 'English body',
            'published_at' => '2026-11-24',
        ])->assertRedirect(route('admin.news.index'));

        $news = News::firstOrFail();
        $this->assertSame('دفتر تازه گشایش یافت', $news->title_fa);

        $this->put(route('admin.news.update', $news), [
            'title' => 'New office opened',
            'body' => 'Updated body',
            'published_at' => '2026-11-25',
        ])->assertRedirect(route('admin.news.index'));

        $this->assertSame('Updated body', $news->fresh()->body);
        $this->get(route('admin.news.index'))->assertOk()->assertSee('New office opened');

        $this->delete(route('admin.news.destroy', $news))->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseCount('news', 0);
    }
}
