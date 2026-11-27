<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_activities_are_listed_by_their_date_not_by_when_they_were_entered(): void
    {
        Activity::create(['name' => 'Refugee Day', 'title' => 'Refugee Day statement', 'body' => 'Text', 'published_at' => '2026-06-21']);
        Activity::create(['name' => 'Kabul anniversary', 'title' => 'Kabul anniversary statement', 'body' => 'Text', 'published_at' => '2026-08-15']);
        Activity::create(['name' => 'Eid message', 'title' => 'Eid message of congratulations', 'body' => 'Text', 'published_at' => '2026-05-27']);

        $this->get(route('activities'))
            ->assertOk()
            ->assertSeeInOrder(['Kabul anniversary statement', 'Refugee Day statement', 'Eid message of congratulations']);

        $this->actingAs(User::factory()->create());

        $this->get(route('admin.activities.index'))
            ->assertOk()
            ->assertSeeInOrder(['15 Aug 2026', '21 Jun 2026', '27 May 2026']);
    }

    public function test_home_page_features_the_newest_event_stamped_with_its_day_and_name(): void
    {
        Activity::create(['name' => 'Refugee Day Gathering', 'type' => 'event', 'title' => 'Refugee Day gathering', 'body' => 'Older event', 'published_at' => '2026-06-20']);
        Activity::create(['name' => 'Stop Hazara Genocide', 'type' => 'event', 'title' => 'Stop Hazara Genocide programme', 'body' => 'Newest event', 'published_at' => '2026-09-25']);
        Activity::create(['name' => 'Teachers Day', 'title' => 'Teachers Day statement', 'body' => 'Older statement', 'published_at' => '2026-10-05']);
        Activity::create(['name' => 'Open Letter', 'title' => 'Open letter on the ICC', 'body' => 'Newest statement', 'published_at' => '2026-11-18']);

        $home = $this->get(route('home'))->assertOk();

        $home->assertSeeInOrder([
            __('Featured Observance'),
            '<span class="day">25</span>',
            'Stop Hazara Genocide',
            'Stop Hazara Genocide programme</h3>',
            'Teachers Day',
            __('Latest activity'),
            'Open Letter</h3>',
        ], false);
        $home->assertDontSee('Refugee Day gathering');
        $this->assertSame(1, substr_count($home->getContent(), 'Open Letter</h3>'));
    }

    public function test_admin_sets_and_changes_the_date(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.activities.create'))
            ->assertOk()
            ->assertSee('value="'.now()->format('Y-m-d').'"', false);

        $this->post(route('admin.activities.store'), [
            'name' => 'Refugee Day',
            'type' => 'general',
            'published_at' => '2026-06-21',
            'title' => 'Refugee Day statement',
            'body' => 'Text',
        ])->assertRedirect(route('admin.activities.index'));

        $activity = Activity::firstOrFail();
        $this->assertSame('2026-06-21', $activity->published_at->format('Y-m-d'));

        $this->put(route('admin.activities.update', $activity), [
            'name' => 'Refugee Day',
            'type' => 'general',
            'published_at' => '2026-06-20',
            'title' => 'Refugee Day statement',
            'body' => 'Text',
        ])->assertRedirect(route('admin.activities.index'));

        $this->assertSame('2026-06-20', $activity->fresh()->published_at->format('Y-m-d'));

        $this->post(route('admin.activities.store'), [
            'name' => 'No date',
            'title' => 'No date',
            'body' => 'Text',
        ])->assertSessionHasErrors('published_at');
    }

    public function test_an_activity_made_without_a_date_gets_today(): void
    {
        $activity = Activity::create(['name' => 'A', 'title' => 'T', 'body' => 'B']);

        $this->assertSame(now()->format('Y-m-d'), $activity->fresh()->published_at->format('Y-m-d'));
    }
}
