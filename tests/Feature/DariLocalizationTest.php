<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Galery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DariLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dari_pages_are_right_to_left_and_translated(): void
    {
        $this->get('/fa')
            ->assertOk()
            ->assertSee('<html lang="fa-AF" dir="rtl">', false)
            ->assertSee('جنبش زنان افغان در تبعید')
            ->assertSee(route('fa.activities'), false)
            ->assertSee('href="'.route('home').'" lang="en" hreflang="en"', false)
            ->assertSee('aria-label="زبان: دری"', false);

        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="en" dir="ltr">', false)
            ->assertSee('href="'.route('fa.home').'" lang="fa" hreflang="fa-AF"', false)
            ->assertSee('aria-label="Language: English"', false);
    }

    public function test_activity_shows_dari_copy_on_dari_site_and_english_on_english_site(): void
    {
        $activity = Activity::create([
            'name' => 'Commemoration',
            'name_fa' => 'بزرگداشت',
            'type' => 'statement',
            'title' => 'On the Occasion of Nowruz',
            'title_fa' => 'به مناسبت نوروز',
            'body' => 'Nowruz is a celebration of renewal.',
            'body_fa' => 'نوروز جشن نو شدن است.',
        ]);

        $this->get(route('fa.activities.show', $activity))
            ->assertOk()
            ->assertSee('به مناسبت نوروز')
            ->assertSee('نوروز جشن نو شدن است.')
            ->assertSee('اعلامیه')
            ->assertDontSee('On the Occasion of Nowruz');

        $this->get(route('activities.show', $activity))
            ->assertOk()
            ->assertSee('On the Occasion of Nowruz')
            ->assertDontSee('به مناسبت نوروز');
    }

    public function test_dari_site_falls_back_to_english_when_no_dari_copy(): void
    {
        $activity = Activity::create([
            'name' => 'Advocacy',
            'title' => 'Meeting with the Special Rapporteur',
            'body' => 'A discussion of restrictions on women.',
        ]);
        Galery::create(['name' => 'Herat protest', 'details' => 'Photos']);

        $this->get(route('fa.activities'))
            ->assertOk()
            ->assertSee('Meeting with the Special Rapporteur')
            ->assertSee(route('fa.activities.show', $activity), false);

        $this->get(route('fa.gallery'))->assertOk()->assertSee('Herat protest');
    }

    public function test_language_switch_keeps_the_current_page(): void
    {
        $activity = Activity::create(['name' => 'A', 'title' => 'T', 'body' => 'B']);

        $this->get(route('activities.show', $activity))
            ->assertSee('href="'.route('fa.activities.show', $activity).'"', false);

        $this->get(route('fa.activities.show', $activity))
            ->assertSee('href="'.route('activities.show', $activity).'"', false);
    }

    public function test_contact_errors_are_in_dari_on_dari_site(): void
    {
        $this->from(route('fa.contact'))
            ->post(route('fa.contact.send'), [])
            ->assertRedirect(route('fa.contact'))
            ->assertSessionHasErrors(['email' => 'لطفاً ایمیل را بنویسید.']);
    }

    public function test_admin_saves_both_language_versions(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.activities.store'), [
            'name' => 'Commemoration',
            'name_fa' => 'بزرگداشت',
            'type' => 'general',
            'title' => 'Nowruz',
            'title_fa' => 'نوروز',
            'body' => 'English body',
            'body_fa' => 'متن دری',
        ])->assertRedirect(route('admin.activities.index'));

        $this->assertDatabaseHas('activities', ['title' => 'Nowruz', 'title_fa' => 'نوروز', 'body_fa' => 'متن دری']);

        $this->post(route('admin.galeries.store'), [
            'name' => 'Event',
            'name_fa' => 'رویداد',
            'details' => 'Details',
        ])->assertRedirect(route('admin.galeries.index'));

        $this->assertDatabaseHas('galeries', ['name' => 'Event', 'name_fa' => 'رویداد', 'details_fa' => null]);

        $this->get(route('admin.activities.create'))
            ->assertOk()
            ->assertSee('name="title_fa"', false)
            ->assertSee('<html lang="en">', false);
    }
}
